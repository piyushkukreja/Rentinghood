var AppCalendar = function() {

    return {
        //main function to initiate the module
        init: function(base_url, csrf) {
            this.initCalendar(base_url, csrf);
        },

        initCalendar: function(base_url, csrf) {

            if (!jQuery().fullCalendar) {
                return;
            }

            var createModal = $('#create-appointment-modal');
            var createModalTrigger = $('#create-appointment-modal-trigger');
            var editModal = $('#edit-appointment-modal');
            var editModalTrigger = $('#edit-appointment-modal-trigger');
            var editing = null;
            var unavailable = false;

            var h = {};
            var calendar = $('#appointments-calendar');
            var patientsIdArray = [];
            var patientsNameArray = [];
            var proceduresArray = [];
            var newPatient = null;

            //Auto-complete for Patients
            $.ajax({
                url: base_url + '/patients/show-all',
                type: 'GET',
                dataType: 'JSON',
                success: function (response) {
                    $.each(response.data, function (i, patient) {
                        addPatient(patient);
                    });
                    $('[name="event_patient"]').autocomplete({
                        source: patientsNameArray,
                        minLength: 0,
                        position: { my : "left top", at: "left bottom" }
                    });
                }
            });

            //Auto-complete for procedures
            $.ajax({
                url: base_url + '/procedures/show-all',
                type: 'GET',
                dataType: 'JSON',
                success: function (response) {
                    $.each(response.data, function (i, procedure) {
                        proceduresArray.push(procedure.name);
                    });
                    $('[name="event_procedure"]').autocomplete({
                        source: proceduresArray,
                        minLength: 0,
                        position: { my : "left top", at: "left bottom" }
                    });
                }
            });

            function getUnavailableUsers(start, end, eventId) {
                var eventsArray = calendar.fullCalendar('clientEvents', function (event) {
                    var before = event.start.isBefore(start) && (event.end.isBefore(start) || event.end.isSame(start));
                    var after = (event.start.isAfter(end) || event.start.isSame(end)) && event.end.isAfter(end);
                    var currentEvent = eventId === event.id;
                    return !(before || after || currentEvent);
                });
                var unavailableUsers = [];
                $.each(eventsArray, function (i, event) {
                    if(unavailableUsers.indexOf(event.user_id) === -1)
                        unavailableUsers.push(event.user_id);
                });
                return unavailableUsers;
            }

            function disableUnavailableUsers(modal, eventId = 0) {
                //Creating moment objects for data, start time and end time
                var startDate = moment(modal.find('[name="event_date"]').val());
                var startTime = moment(modal.find('[name="event_start_time"]').val(), 'HH:mm');
                var endTime = moment(modal.find('[name="event_end_time"]').val(), 'HH:mm');

                if(!startDate.isValid() || !startTime.isValid() || !endTime.isValid())
                    return;
                //Create moments for start and end
                var start = startDate.clone().hour(startTime.hour()).minute(startTime.minute());
                var end = startDate.clone().hour(endTime.hour()).minute(endTime.minute());
                var unavailableUsers = getUnavailableUsers(start, end, eventId);
                unavailable = false;
                $.each(unavailableUsers, function (i, userId) {
                    if(parseInt(modal.find('[name="event_user"]').val()) === userId) {
                        modal.find('.unavailable-user-alert').slideDown(200);
                        unavailable = true;
                        return false;
                    }
                });
                if(!unavailable)
                    modal.find('.unavailable-user-alert').slideUp(200);
            }

            //Add patient to array
            function addPatient(patient) {
                patientsIdArray.push(patient.id);
                patientsNameArray.push(patient.first_name + ((patient.last_name !== '') ? (' ' + patient.last_name) : '') + ' (' + patient.patient_id + ')');
            }

            function getDataFromModal(modal) {
                var data = {};

                //Creating moment objects for data, start time and end time
                var startDate = moment(modal.find('[name="event_date"]').val());
                var startTime = moment(modal.find('[name="event_start_time"]').val(), 'HH:mm');
                var endTime = moment(modal.find('[name="event_end_time"]').val(), 'HH:mm');

                //Create moments for start and end
                data.start = startDate.clone().hour(startTime.hour()).minute(startTime.minute());
                data.end = startDate.clone().hour(endTime.hour()).minute(endTime.minute());

                //Getting input field values, trimming and converting to empty string if input is empty
                data.procedure = (modal.find('[name="event_procedure"]').val() + '').trim();
                data.userId = (modal.find('[name="event_user"]').val()) ? parseInt(modal.find('[name="event_user"]').val()) : null;
                data.patientName = (modal.find('[name="event_patient"]').val() + '').trim();
                data.patientId = patientsIdArray[patientsNameArray.indexOf(data.patientName)];
                data.notes = modal.find('[name="event_notes"]').val().trim() + '';

                //Preparing data to be sent through AJAX
                data.dataForAjax = {
                    _token: csrf,
                    title: data.procedure,
                    user_id: data.userId,
                    new_patient: newPatient,
                    patient_name: data.patientName,
                    patient_id: data.patientId,
                    start: data.start.format('YYYY-MM-DD HH:mm:ss'),
                    duration: data.end.diff(data.start, 'minutes'),
                    notes: data.notes
                };

                //Return null if invalid data and show error swal
                if(data.userId == null || data.patientName === '' || data.procedure === '' || !startDate.isValid() || !startTime.isValid() || !endTime.isValid() || unavailable || endTime.isBefore(startTime)) {
                    if(unavailable) {
                        swal({
                            title: 'Specified doctor is unavailable for the selected duration.',
                            type: 'warning'
                        });
                    } else if(data.userId == null) {
                        swal({
                            title: 'Please select a doctor',
                            type: 'warning'
                        });
                    } else if(data.patientName === '') {
                        swal({
                            title: 'Please choose a patient',
                            type: 'warning'
                        });
                    } else if(data.procedure === '') {
                        swal({
                            title: 'Please choose a procedure',
                            type: 'warning'
                        });
                    } else if(!startDate.isValid()) {
                        swal({
                            title: 'Invalid date',
                            type: 'warning'
                        });
                    } else if(!startTime.isValid()) {
                        swal({
                            title: 'Invalid start time',
                            type: 'warning'
                        });
                    } else if(!endTime.isValid()) {
                        swal({
                            title: 'Invalid end time',
                            type: 'warning'
                        });
                    } else if(endTime.isBefore(startTime)) {
                        swal({
                            title: 'Invalid start and end time',
                            type: 'warning'
                        });
                    }
                    return null;
                }

                return data;
            }

            //Get data from modal and send it to create an appointment
            $('#appointments-create').on('click', function () {
                var modalData = getDataFromModal(createModal);
                if(!modalData) {
                    console.log('Invalid Data');
                    return;
                }
                $.ajax({
                    url: base_url + '/appointments',
                    type: 'POST',
                    data: modalData.dataForAjax,
                    dataType: 'JSON',
                    success: function (response) {
                        if(response.status === 'success') {
                            //If a new patient was created
                            if(response.new_patient)
                                addPatient(response.patient);

                            //Create event object
                            var event = {
                                id : response.id,
                                title: response.patient.name + ' - ' + modalData.procedure,
                                procedure: modalData.procedure,
                                user_id: modalData.userId,
                                patient_id: response.patient.id,
                                start: modalData.start,
                                end: modalData.end,
                                notes: modalData.notes,
                                color: '#' + response.user.color
                            };
                            calendar.fullCalendar('renderEvent', event);
                        } else {
                            swal({
                                title: 'Appointment could not be created!',
                                type: 'warning'
                            });
                        }
                        createModal.find('#create-appointments-modal-close').trigger('click');
                    }
                });
            });

            //Get data from modal and send it to update an appointment
            $('#appointments-edit').on('click', function () {
                var modalData = getDataFromModal(editModal);
                if(!modalData) {
                    return;
                }
                $.ajax({
                    url: base_url + '/appointments/' + editing.id,
                    type: 'PUT',
                    data: modalData.dataForAjax,
                    dataType: 'JSON',
                    success: function (response) {
                        if(response.status === 'success') {
                            //If a new patient was created
                            if(response.new_patient)
                                addPatient(response.patient);

                            //Update the event
                            editing.title = response.patient.name + ' - ' + modalData.procedure;
                            editing.procedure = modalData.procedure;
                            editing.user_id = modalData.userId;
                            editing.patient_id = response.patient.id;
                            editing.start = modalData.start;
                            editing.end = modalData.end;
                            editing.notes = modalData.notes;
                            editing.color =  '#' + response.user.color;
                            calendar.fullCalendar('updateEvent', editing);
                        } else {
                            swal({
                                title: 'Appointment could not be updated!',
                                type: 'warning'
                            });
                        }
                        editModal.find('#edit-appointments-modal-close').trigger('click');
                        editing = null;
                    }
                });
            });

            if (App.isRTL()) {
                h = {
                    right: 'title',
                    center: '',
                    left: 'listDay, agendaWeek, month, prev, next'
                };
            } else {
                h = {
                    left: 'title',
                    center: '',
                    right: 'prev, next, month, agendaWeek, listDay'
                };
            }

            calendar.fullCalendar('destroy'); // destroy the calendar
            calendar.fullCalendar({ //re-initialize the calendar
                header: h,
                defaultView: 'month', // change default view with available options from http://arshaw.com/fullcalendar/docs/views/Available_Views/
                selectable: true,
                eventLimit: 2,
                buttonText: {
                    today:    'today',
                    month:    'month',
                    week:     'week',
                    day:      'day',
                    list:     'day'
                },
                select: function(startDate, endDate) {

                    //Un-select if selections spans across multiple days
                    switch(endDate.date() - startDate.date()) {
                        case 0:
                            break;
                        case 1:
                            if(startDate.format('HH:mm') === '00:00' && endDate.format('HH:mm') === '00:00') {
                                startDate.hours(moment().hours());
                                endDate = startDate.clone().hours(moment().hours() + 1);
                                break;
                            }
                        default:
                            calendar.fullCalendar( 'unselect' );
                            return;
                    }
                    createModal.find('[name="event_date"]').val(startDate.format('YYYY-MM-DD'));
                    createModal.find('[name="event_start_time"]').val(startDate.format('HH:mm'));
                    createModal.find('[name="event_end_time"]').val(endDate.format('HH:mm'));

                    //Adjusting alert on the basis of selected patient (New or Existing)
                    newPatient = createModal.find('[name="event_patient"]').val() !== '' && patientsNameArray.indexOf(createModal.find('[name="event_patient"]').val()) === -1;
                    if(newPatient)
                        createModal.find('.new-patient-alert').show();
                    else
                        createModal.find('.new-patient-alert').hide();

                    createModal.find('[name="event_patient"]').off('change').on('change', function () {
                        newPatient = $(this).val() !== '' && patientsNameArray.indexOf($(this).val()) === -1;
                        if(newPatient)
                            createModal.find('.new-patient-alert').slideDown(200);
                        else
                            createModal.find('.new-patient-alert').slideUp(200);
                    });

                    //Bind changes in range of appointment to function which checks for unavailable doctors
                    disableUnavailableUsers(createModal);
                    createModal.find('[name="event_date"],[name="event_start_time"],[name="event_end_time"],[name="event_user"]').on('change', function () {
                        disableUnavailableUsers(createModal);
                    });

                    createModalTrigger.trigger('click');
                },
                events: {
                    url: base_url + '/appointments/show-all',
                    type: 'GET'
                },
                //Creating Event Object from JSON
                eventDataTransform: function (eventData) {
                    var returnData = [];
                    returnData.id = eventData.id;
                    returnData.title = eventData.patient.name + ' - ' + eventData.title;
                    returnData.procedure = eventData.title;
                    returnData.user_id = eventData.user_id;
                    returnData.patient_id = eventData.patient_id;
                    returnData.start = moment(eventData.start);
                    returnData.end = returnData.start.clone().add(eventData.duration, 'm');
                    returnData.notes = eventData.notes;
                    returnData.color = '#' + eventData.user.color;
                    returnData.user_name = eventData.user.name;
                    return returnData;
                },
                //Show event details in a Modal for editing
                eventClick: function (event, jsEvent, view) {
                    editModal.find('[name="event_procedure"]').val(event.procedure);
                    editModal.find('[name="event_date"]').val(event.start.format('YYYY-MM-DD'));
                    editModal.find('[name="event_start_time"]').val(event.start.format('HH:mm'));
                    editModal.find('[name="event_end_time"]').val(event.end.format('HH:mm'));
                    editModal.find('[name="event_notes"]').val(event.notes);

                    //If patient or user(doctor) is deleted, fill the fields and disable everything in the modal
                    //If thats not the case enable everything.
                    if(patientsIdArray.indexOf(event.patient_id) === -1 || editModal.find('[name="event_user"]').find('option[value="' + event.user_id + '"]').length === 0) {
                        editModal.find('[name="event_user"]').hide();
                        editModal.find('#deleted_user').show().html(event.user_name);
                        editModal.find('[name="event_patient"]').val(event.title.substring(0, event.title.indexOf(' - ')));
                        editModal.find('input,select,textarea').prop('readonly', true).prop('disabled', true);
                        editModal.find('#appointments-edit').prop('disabled', true);
                        editModal.find('.cannot-edit-alert').show();
                    } else {
                        editModal.find('input,select,textarea').prop('readonly', false).prop('disabled', false);
                        editModal.find('[name="event_user"]').show();
                        editModal.find('#deleted_user').html('').hide();
                        editModal.find('#appointments-edit').prop('disabled', false);
                        editModal.find('.cannot-edit-alert').hide();
                        editModal.find('[name="event_user"]').val(event.user_id);
                        editModal.find('[name="event_patient"]').val(patientsNameArray[patientsIdArray.indexOf(event.patient_id)]);
                    }

                    //Binding delete button to a function which deletes the appointment
                    editModal.find('#appointments-delete').off('click').on('click', function () {
                        swal({
                            title: 'Are you sure you want to delete this appointment?',
                            type: 'warning',
                            showCancelButton: true,
                            buttonsStyling: false,
                            allowEnterKey: false,
                            confirmButtonClass: 'btn btn-warning',
                            cancelButtonClass: 'btn btn-info',
                        }).then((result) => {
                            if (result.value) {
                            $.ajax({
                                url: base_url + '/appointments/' + event.id,
                                type: 'DELETE',
                                data: { _token: csrf },
                                success: function (response) {
                                    if(response.status === 'success')
                                        calendar.fullCalendar('removeEvents', event.id);
                                }
                            });
                            editModal.find('#edit-appointments-modal-close').trigger('click');
                        }
                    });
                    });

                    //Adjusting alert on the basis of selected patient (New or Existing)
                    newPatient = false;
                    editModal.find('.new-patient-alert').hide();
                    editModal.find('[name="event_patient"]').off('change').on('change', function () {
                        newPatient = $(this).val() !== '' && patientsNameArray.indexOf($(this).val()) === -1;
                        if(newPatient)
                            editModal.find('.new-patient-alert').slideDown(200);
                        else
                            editModal.find('.new-patient-alert').slideUp(200);
                    });

                    //Bind changes in range of appointment to function which checks for unavailable doctors
                    unavailable = false;
                    editModal.find('.unavailable-user-alert').hide();
                    editModal.find('[name="event_date"],[name="event_start_time"],[name="event_end_time"],[name="event_user"]').off('change').on('change', function () {
                        disableUnavailableUsers(editModal, event.id);
                    });

                    editing = event;
                    editModalTrigger.trigger('click');
                },
                eventLongPressDelay: 100,
                selectLongPressDelay: 100,
                timeFormat: 'h(:mm) A'
            });

        }

    };
}();