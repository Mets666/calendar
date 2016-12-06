$(document).ready(function() {

    $('.datetimepicker-identifier').each(function(i, obj) {
        $(this).datetimepicker();
    });

    $('#addCategoryContent').dialog({ modal: true, autoOpen: false, title: 'New Category', width:400 });
    
    $('#openAddCategoryModal').click(function(){ $('#addCategoryContent').dialog('open'); return false;});

});

function editCalendarEvent() {
    document.getElementById("form-event-edit").classList.add("collapse");

    document.getElementById("text-eventCategory").classList.add("collapse");
    document.getElementById("text-eventStartTime").classList.add("collapse");
    document.getElementById("text-eventEndTime").classList.add("collapse");


    document.getElementById("form-event-save").classList.remove("collapse");

    document.getElementById("form-eventTitle").classList.remove("collapse");
    document.getElementById("form-eventCategory").classList.remove("collapse");
    document.getElementById("form-eventStartTime").classList.remove("collapse");
    document.getElementById("form-eventEndTime").classList.remove("collapse");

}

function revertEditCalendarEvent() {
    document.getElementById("form-event-edit").classList.remove("collapse");

    document.getElementById("text-eventCategory").classList.remove("collapse");
    document.getElementById("text-eventStartTime").classList.remove("collapse");
    document.getElementById("text-eventEndTime").classList.remove("collapse");


    document.getElementById("form-event-save").classList.add("collapse");

    document.getElementById("form-eventTitle").classList.add("collapse");
    document.getElementById("form-eventCategory").classList.add("collapse");
    document.getElementById("form-eventStartTime").classList.add("collapse");
    document.getElementById("form-eventEndTime").classList.add("collapse");

}