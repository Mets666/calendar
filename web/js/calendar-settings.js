$(function () {
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    $('#calendar-holder').fullCalendar({
        header: {
            left: 'prev, next',
            center: 'title',
            right: 'month, basicWeek, basicDay,'
        },
        eventRender: function eventRender( event, element, view ) {
            element.attr('href', 'javascript:void(0);');
            element.click(function() {
                openEditEventModal(event);
            });
            var project_filter = $('#project_filter_selector').val();

            var category_filter = $('#category_filter_selector').val();

            if((!category_filter || (event.category && category_filter == event.category.id)) && (!project_filter || (event.project && project_filter == event.project.id))){
                return true;
            }
            else{
                return false;
            }
            
        },
        dayClick: function(date, jsEvent, view) {
            $(".new-input-eventStart").val(moment(date).format('YYYY-MM-DD HH:mm'));
            openAddEventModal();
        },
        timeFormat: {
            // for agendaWeek and agendaDay
            agenda: 'h:mmtt',

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

    $('#category_filter_selector').on('change',function(){
        $('#calendar-holder').fullCalendar('rerenderEvents');
    });

    $('#project_filter_selector').on('change',function(){
        $('#calendar-holder').fullCalendar('rerenderEvents');
    });
});
