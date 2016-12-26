$(function () {
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    $('#calendar-holder').fullCalendar({
        // events: basepath + "json-events.php",
        header: {
            left: 'prev, next',
            center: 'title',
            right: 'month, basicWeek, basicDay,'
        },
        eventRender: function eventRender( event, element, view ) {
            element.attr('href', 'javascript:void(0);');
            element.click(function() {

                revertEditCalendarEvent();

                $(".input-eventId").val(event.id);

                $(".input-eventTitle").val(event.title);

                if(event.category){
                    $("#text-eventCategory").html(event.category.title);
                    $(".input-eventCategory").val(event.category.id);
                }
                else {
                    $("#text-eventCategory").html("None");
                }

                $("#text-eventStartTime").html(moment(event.start).format('MMM Do h:mm A'));
                $(".input-eventStartTime").val(moment(event.start).format('YYYY-MM-DD hh:mm'));

                $("#text-eventEndTime").html(moment(event.end).format('MMM Do h:mm A'));
                $(".input-eventEndTime").val(moment(event.end).format('YYYY-MM-DD hh:mm'));



                $("#eventLink").attr('href', event.url);
                $("#eventContent").dialog({ modal: true, title: event.title, width:400});
                $("#eventDeleteLink").attr('href',
                    Routing.generate('delete_event', {'eventId': event.id})
                );
            });
            var filter = $('#filter_selector').val();

            if(!filter || (event.category && filter == event.category.id)){
                return true;
            }
            else{
                return false;
            }
            
        },
        lazyFetching: true,
        timeFormat: {
            // for agendaWeek and agendaDay
            agenda: 'h:mmtt',    // 5:00 - 6:30

            // for all other views
            '': 'h:mmtt'
        },
        eventSources: [
            {
                url: Routing.generate('fullcalendar_loader'),
                type: 'POST',
                // A way to add custom filters to your event listeners
                data: {
                },
                error: function() {
                   //alert('There was an error while fetching Google Calendar!');
                }
            }
        ]
    });

    $('#filter_selector').on('change',function(){
        $('#calendar-holder').fullCalendar('rerenderEvents');
    })
});
