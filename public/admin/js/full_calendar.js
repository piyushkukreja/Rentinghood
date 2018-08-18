var initialize_calendar;
var createModal = $('#create-appointment-modal');
var createModalTrigger = $('#create-appointment-modal-trigger');
var editModal = $('#edit-appointment-modal');
var editModalTrigger = $('#edit-appointment-modal-trigger');
initialize_calendar = function () {
    $.ajaxSetup({
        beforeSend: function(xhr, type) {
            if (!type.crossDomain) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            }
        }
    });
    $('.calendar').each(function () {
        var calendar = $(this);
        calendar.fullCalendar({
            header: {
                left: 'prev, next today',
                center: 'title',
                right: 'month, agendaWeek, agendaDay'
            },
            selectable: true,
            selectHelper: true,
            editable: true,
            eventLimit: true,
            height:400,
            contentHeight: 400,

            select: function(start, end, allDay){
                var eventTitle = prompt("Enter Event Title");
                if(eventTitle){
                    window.alert(eventTitle);
                    start = $.fullCalendar.formatDate(start, "YYYY-MM-DD HH:mm");
                    end = $.fullCalendar.formatDate(end, "YYYY-MM-DD HH:mm");
                    window.alert('after start end');
                    $.ajax({
                        url: "/vendor/events/insert",
                        type:"POST",
                        dataType: 'JSON',
                        data: {title:eventTitle, start: start, end: end},
                        success: function () {
                            calendar.fullCalendar('refetchEvents');
                            alert("Added Successfully!");
                        },
                        error: function (data) {
                            window.alert("failed to insert");
                            console.log(data);
                        }

                    })
                }
                /*$.getScript('/calendar', function () {
                    $('#event_date_range').val(moment(start).format("MM/DD/YYYY HH:mm") + ' = ' + moment(end).format("MM/DD/YYYY HH:mm"));
                    date_range_picker();
                    $('.start_hidden').val(moment(start).format('YYYY-MM-DD HH:mm'));
                    $('.end_hidden').val(moment(start).format('YYYY-MM-DD HH:mm'));
                });
                calendar.fullCalendar('unselect');*/
            },
        });
    })
};
window.onload = initialize_calendar;
