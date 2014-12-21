$(document).ready(function() {

    var getFieldSortValue = function(node)
    {
        return $(node).attr('data-sort');
    }

    $(".tablesorter").tablesorter({
        textExtraction: getFieldSortValue,
        sortList: [[1,1]],
	sortInitialOrder: 'desc'
    });

    $("#search-champ-form").submit(function(event) {
        event.preventDefault();
        var base_url = $('#base-url').val();
        $("#search-champ-box .loader").show();
        var data = $( this ).serialize();
        data +="&sid=" + $("#sid").val();
        data +="&region=" + $("#region").val();
        $( "#group-search .panel-body" ).load( base_url+'groups/search', data, function(response, status) {
            if(status == "success") {
                $( "#group-search" ).removeClass("hidden");
                if(!$("#collapse-search").hasClass("in")) {
                    $( "#group-search a" ).click();
                }
                //$("#groups-list").accordion("option", "active", 0);
                $(".tablesorter").tablesorter();
            }
            $("#search-champ-box .loader").hide();
        });
    });

    $("#all-champions .row div img").click(function(){
        var elem = $(this).parent().children('input[type="checkbox"]');
        elem.click();
    });

    $('input[type="checkbox"]').click(function() {
        var elem = $(this).parent().children("img");
        elem.toggleClass("desaturate");
    });

    $("#edit-group-form").submit(function(event) {
        event.preventDefault();
        var base_url = $('#base-url').val();
        var data = $(this).serialize();
        $.ajax({
            url: base_url+'groups/save',  //Server script to process data
            type: 'POST',

            // Data passed
            data: data,

            success: function (response) {
                //console.log(response);
                location.reload();
            },

            error: function(message) {
                //$("#summoners .loader").hide();
            }
        });
    });

    $("#add-group-button").click(function() {
        resetChampionsGroupDialog();
        $("#edit-group-dialog").modal();
    });

    $(".edit-group-button").click(function () {
        event.stopPropagation();
        var panel = $(this).parents(".panel");
        var data  = {
                champs: panel.find(".group-champs").val(),
                name: panel.find(".group-name").val(),
                access: panel.find(".group-access").val(),
                id: panel.find(".group-id").val()
        };

        resetChampionsGroupDialog();
        populateChampionsGroupDialog(data);

        $("#edit-group-dialog").modal();
    });

    $(".delete-group-button").click(function () {
        event.stopPropagation();
        var id = $(this).parents(".panel").find(".group-id").val();
        $("#group-id").val(id);
        $("#group-delete-confirm-dialog").modal();
    });

    $("#delete-group").click(function() {
        var id = $("#group-id").val();
        var base_url = $('#base-url').val();
        $.ajax({
            url: base_url+'groups/delete',  //Server script to process data
            type: 'POST',

            // Data passed
            data: {group_id: id},

            success: function (response) {
                //console.log(response);
                location.reload();
            },

            error: function(message) {
                //$("#summoners .loader").hide();
            }
        });
        $( this ).dialog( "close" );
    });

});

function resetChampionsGroupDialog() {
    $("#group-id").val("");
    $("#group-name").val("");
    $( '#all-champions input[type="checkbox"]' ).each(function( index ) {
        if($(this).prop("checked")) {
            $(this).click();
        }
    });
}

function populateChampionsGroupDialog(data) {
    data.champs = ',' + data.champs + ',';
    $("#group-id").val(data.id);
    $("#group-name").val(data.name);
    $("#group-access").val(data.access);
    $( '#all-champions input[type="checkbox"]' ).each(function( index ) {
        champ_id = ',' + $(this).attr("id").substring(15) + ',';
        if(data.champs.indexOf(champ_id) != -1) {
            $(this).click();
        }
    });
}