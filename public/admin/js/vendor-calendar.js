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


            var editModal = $('#edit-event-modal');
            var editModalTrigger = $('#edit-event-modal-trigger');
            var editing = null;

            var h = {};
            var calendar = $('#events-calendar');

            function getDataFromModal(modal) {
                var data = {};

                //Creating moment object for date
                var eventDate = moment(modal.find('[name="event_date"]').val());

                //Return null if invalid data and show error swal
                if(!eventDate.isValid()) {
                    swal({
                        title: 'Invalid date',
                        type: 'warning'
                    });
                    return null;
                }

                //Preparing data to be sent through AJAX
                data.dataForAjax = {
                    _token: csrf,
                    date: eventDate.format('YYYY-MM-DD'),

                };

                return data;
            }

            //Get data from modal and send it to update an appointment
            $('#event-edit').on('click', function () {
                var modalData = getDataFromModal(editModal);
                if(!modalData) {
                    return;
                }
                $.ajax({
                    url: base_url + '/vendor/events/' + editing.id,
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
                        editModal.find('#edit-event-modal-close').trigger('click');
                        editing = null;
                    }
                });
            });


            h = {
                left: 'title',
                center: '',
                right: 'prev, next, month, agendaWeek, listDay'
            };

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
                events: {
                    url: base_url + '/vendor/events',
                    type: 'GET'
                },
                dayClick: function(date, jsEvent, view) {

                    alert('Clicked on: ' + date.format());

                    alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);

                    alert('Current view: ' + view.name);

                    // change the day's background color just for fun
                    $(this).css('background-color', 'red');

                },
                //Creating Event Object from JSON
                eventDataTransform: function (eventData) {
                    var returnData = [];
                    returnData.id = eventData.id;
                    returnData.transactionid = eventData.id;
                    returnData.title = eventData.title;//required
                    returnData.start = moment(eventData.start);//required
                    returnData.end = returnData.start.clone().add(eventData.duration, 'm');
                    returnData.color = '#' + eventData.color;
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
                        editModal.find('[name="event_vendor"]').val(event.title.substring(0, event.title.indexOf(' - ')));
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
                        editModal.find('[name="event_vendor"]').val(patientsNameArray[patientsIdArray.indexOf(event.patient_id)]);
                    }

                    editing = event;
                    editModalTrigger.trigger('click');
                },
                timeFormat: 'h(:mm) A'
            });

        }

    };
}();