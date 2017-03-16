$(document).ready(function () {

    $('.datetimepicker-identifier').each(function (i, obj) {
        $(this).datetimepicker();
    });

    $('#addCategoryContent').dialog({modal: true, autoOpen: false, title: 'Category', width: 400, position: {at: 'top'}});

    $('#openAddCategoryModal').click(function () {

        $(".input-categoryId").val("");
        $(".input-categoryTitle").val("");
        $(".input-categoryColor")[0].jscolor.fromString("FFFFFF");

        $('#addCategoryContent').dialog('open');
        return false;
    });

    $('.openEditCategoryModal').each(function () {
        $(this).click(function () {

            $(".input-categoryId").val($(this).data('id'));
            $(".input-categoryTitle").val($(this).data('title'));
            $(".input-categoryColor")[0].jscolor.fromString($(this).data('color'));

            $('#addCategoryContent').dialog('open');
            return false;
        });
    });

    $('#addListContent').dialog({modal: true, autoOpen: false, title: 'Add new list', width: 400, position: {at: 'top'}});

    $('#openAddListModal').click(function () {
        $('#addListContent').dialog('open');
        return false;
    });

    $('#addProjectContent').dialog({modal: true, autoOpen: false, title: 'Add new project', width: 400, position: {at: 'top'}});

    $('#openAddProjectModal').click(function () {
        $('#addProjectContent').dialog('open');
        return false;
    });
    
});

function editCalendarEvent() {
    document.getElementById("form-event-edit").classList.add("collapse");

    document.getElementById("text-eventNote").classList.add("collapse");
    document.getElementById("text-eventProject").classList.add("collapse");
    document.getElementById("text-eventCategory").classList.add("collapse");
    document.getElementById("text-eventStartTime").classList.add("collapse");
    document.getElementById("text-eventEndTime").classList.add("collapse");


    document.getElementById("form-event-save").classList.remove("collapse");

    document.getElementById("form-eventTitle").classList.remove("collapse");
    document.getElementById("form-eventNote").classList.remove("collapse");
    document.getElementById("form-eventProject").classList.remove("collapse");
    document.getElementById("form-eventCategory").classList.remove("collapse");
    document.getElementById("form-eventStartTime").classList.remove("collapse");
    document.getElementById("form-eventEndTime").classList.remove("collapse");

}

function revertEditCalendarEvent() {
    document.getElementById("form-event-edit").classList.remove("collapse");

    document.getElementById("text-eventNote").classList.remove("collapse");
    document.getElementById("text-eventProject").classList.remove("collapse");
    document.getElementById("text-eventCategory").classList.remove("collapse");
    document.getElementById("text-eventStartTime").classList.remove("collapse");
    document.getElementById("text-eventEndTime").classList.remove("collapse");


    document.getElementById("form-event-save").classList.add("collapse");

    document.getElementById("form-eventTitle").classList.add("collapse");
    document.getElementById("form-eventNote").classList.add("collapse");
    document.getElementById("form-eventProject").classList.add("collapse");
    document.getElementById("form-eventCategory").classList.add("collapse");
    document.getElementById("form-eventStartTime").classList.add("collapse");
    document.getElementById("form-eventEndTime").classList.add("collapse");

}

function editTodoList() {
    document.getElementById("text-list-edit").classList.add("collapse");
    document.getElementById("text-list-title").classList.add("collapse");
    document.getElementById("text-list-description").classList.add("collapse");
    var checkboxes = document.getElementsByClassName("list-item-checkbox");
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes.item(i).disabled = false;
    }
    ;
    var texts = document.getElementsByClassName("list-item-text");
    for (i = 0; i < texts.length; i++) {
        texts.item(i).disabled = false;
    }
    ;

    var items = document.getElementsByClassName("disabled-btn");
    while (items.length) {
        items[0].classList.remove("disabled-btn");
    }
    ;

    document.getElementById("form-list-save").classList.remove("collapse");
    document.getElementById("form-list-title").classList.remove("collapse");
    document.getElementById("form-list-description").classList.remove("collapse");
    document.getElementById("form-add-item").classList.remove("collapse");
}

function editProject() {
    document.getElementById("text-project-edit").classList.add("collapse");

    document.getElementById("text-project-title").classList.add("collapse");
    document.getElementById("text-project-acronym").classList.add("collapse");
    document.getElementById("text-project-limit").classList.add("collapse");
    document.getElementById("text-project-description").classList.add("collapse");


    document.getElementById("form-project-save").classList.remove("collapse");


    document.getElementById("form-project-title").classList.remove("collapse");
    document.getElementById("form-project-acronym").classList.remove("collapse");
    document.getElementById("form-project-limit").classList.remove("collapse");
    document.getElementById("form-project-description").classList.remove("collapse");

}

function openEditEventModal(event, editable) {

    editable = editable || false;

    console.log(event);

    if(editable){
        editCalendarEvent();
    }
    else {
        revertEditCalendarEvent();
    }

    $(".input-eventId").val(event.id);

    $(".input-eventTitle").val(event.title);

    $("#text-eventNote").html(event.note);
    $(".input-eventNote").val(event.note);

    if(event.project){
        $("#text-eventProject").html(event.project.title);
        $(".input-eventProject").val(event.project.id);
    }
    else {
        $("#text-eventProject").html("None");
    }

    if(event.category){
        $("#text-eventCategory").html(event.category.title);
        $(".input-eventCategory").val(event.category.id);
    }
    else {
        $("#text-eventCategory").html("None");
    }

    $("#text-eventStartTime").html(moment(event.start).utc().format('MMM Do hh:mm A'));
    $(".input-eventStartTime").val(moment(event.start).utc().format('YYYY-MM-DD HH:mm'));

    $("#text-eventEndTime").html(moment(event.end).utc().format('MMM Do hh:mm A'));
    $(".input-eventEndTime").val(moment(event.end).utc().format('YYYY-MM-DD HH:mm'));

    $("#eventDeleteLink").attr('href',
        Routing.generate('delete_event', {'eventId': event.id})
    );

    if(event.mainTitle)    {
        $('#eventContent').dialog({modal: true, title: event.mainTitle, width: 400, position: {at: 'top'}});
    }
    else {
        $('#eventContent').dialog({modal: true, title: event.title, width: 400, position: {at: 'top'}});
    }
    return false;
}

