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
            editModal.find('[name="event-color"]').minicolors();
            var editModalTrigger = $('#edit-event-modal-trigger');
            var editing = null;


            var storeModal = $('#store-event-modal');
            storeModal.find('[name="event-color"]').minicolors();
            var storeModalTrigger = $('#store-event-modal-trigger');
            var storing = null;

            var calendar = $('#events-calendar');

            function getDataFromModal(modal) {
                var data = {};
                //Creating moment object for date
                var eventDate = moment(modal.find('[name="event-date"]').val());
                var eventTitle = modal.find('[name="event-title"]').val();
                var eventColor = modal.find('[name="event-color"]').val();
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
                    title: eventTitle,
                    date: eventDate.format('YYYY-MM-DD'),
                    color: eventColor
                };
                return data;
            }


            //Get data from modal and send it to create/add an event
            $('#event-store').on('click', function () {
                var modalData = getDataFromModal(storeModal);
                if(!modalData) {
                    return;
                }
                $.ajax({
                    url: base_url + '/events/store',
                    type: 'POST',
                    data: modalData.dataForAjax,
                    dataType: 'JSON',
                    success: function (response) {
                        if(response.status === 'success') {
                            //Store the event
                            storing.title = response.event.title;
                            storing.date = response.event.date;
                            storing.start = moment(response.event.date);
                            storing.color = response.event.color;
                            calendar.fullCalendar('storeEvent', storing);
                            calendar.fullCalendar('refetchEvents');
                        } else {
                            swal({
                                title: 'Event could not be stored!',
                                type: 'warning'
                            });
                        }
                        storeModal.find('#store-event-modal-close').trigger('click');
                        storing = null;
                    }
                });
            });


            //Get data from modal and send it to update an event
            $('#event-edit').on('click', function () {
                var modalData = getDataFromModal(editModal);
                if(!modalData) {
                    return;
                }
                $.ajax({
                    url: base_url + '/events/' + editing.id,
                    type: 'PUT',
                    data: modalData.dataForAjax,
                    dataType: 'JSON',
                    success: function (response) {
                        if(response.status === 'success') {
                            //Update the event
                            editing.title = response.event.title;
                            editing.date = response.event.date;
                            editing.start = moment(response.event.date);
                            editing.color = response.event.color;
                            calendar.fullCalendar('updateEvent', editing);
                        } else {
                            swal({
                                title: 'Event could not be updated!',
                                type: 'warning'
                            });
                        }
                        editModal.find('#edit-event-modal-close').trigger('click');
                        editing = null;
                    }
                });
            });

            var h = {
                left: 'title',
                center: '',
                right: 'prev, next, month, listWeek, listDay'
            };

            calendar.fullCalendar({
                header: h,
                defaultView: 'month',
                selectable: true,
                eventLimit: 2,
                buttonText: {
                    today:    'today',
                    month:    'month',
                    listWeek: 'week',
                    listDay:  'day'
                },
                events: {
                    url: base_url + '/calendar/show-all',
                    type: 'GET'
                },
                /*dayClick: function(date, jsEvent, view) {

                    alert('Clicked on: ' + date.format());

                    alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);

                    alert('Current view: ' + view.name);

                    // change the day's background color just for fun
                    $(this).css('background-color', 'red');

                },*/

                //Creating Event Object from JSON
                eventDataTransform: function (eventData) {
                    var returnData = [];
                    returnData.id = eventData.id;
                    returnData.transaction_id = eventData.transaction_id;
                    returnData.date = eventData.date;
                    returnData.title = eventData.title;//required
                    returnData.start = moment(eventData.date);//required
                    returnData.color = eventData.color;
                    returnData.allDay = true;
                    return returnData;
                },
                //Show event details in a Modal for editing
                eventClick: function (event, jsEvent, view) {
                    editModal.find('[name="event-title"]').val(event.title);
                    editModal.find('[name="event-date"]').val(event.start.format('YYYY-MM-DD'));
                    editModal.find('[name="event-color"]').minicolors('value', event.color);

                    editing = event;
                    editModalTrigger.trigger('click');
                },
                dayClick: function (date, event) {
                    storeModal.find('[name="event-title"]').val("");
                    storeModal.find('[name="event-date"]').val(date.format());
                    storeModal.find('[name="event-color"]').minicolors('value', '#111');

                    storing = event;
                    storeModalTrigger.trigger('click');
                },

                timeFormat: 'h(:mm) A'
            });

        }

    };
}();