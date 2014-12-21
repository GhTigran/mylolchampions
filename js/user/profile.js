$(document).ready(function() {

    $("#add-summoner").click(function(){
        var base_url = $("#base-url").val();
        var name = $("#new-summoner-name").val();
        var region = $("#new-summoner-region").val();

        $("#add-summoner-loader").show();

        $("#alerts").html('');

        $.ajax({
            url: base_url+'user/add_summoner',  //Server script to process data
            type: 'POST',

            // Form data
            data: {name: name, region: region},

            success: function (response) {
                if(response.indexOf("{") == 0) {
                    var summoner = jQuery.parseJSON(response);
                    var container = $("#summoners-container .table");
                    var str =
                        '<tr>' +
                            '<td>' +
                            '<a href="' + base_url + 'summoner/' + summoner.region+'/' + summoner.name + '">' + summoner.name+' ('+summoner.region+')'+
                            '</td>' +
                            '<td>' +
                            '<input type="hidden" name="sid" value="'+summoner.sid+'" />'+
                            '<input type="hidden" name="sname" value="'+summoner.name+'" />'+
                            '<input type="hidden" name="sregion" value="'+summoner.region+'" />'+
                            '<input type="hidden" name="verification_key" value="' + summoner.verification_key + '" />' +
                            '<input type="button" class="btn btn-sm btn-primary verify-button" value="Verify" /> ' +
                            '<input type="button" class="btn btn-sm btn-danger unlink-summoner" value="Unlink" />'+
                            '</td>' +
                        '</tr>';
                    container.append(str);
                    $("#add-summoner-loader").hide();
                } else {
                    var alert_msg =
                        '<div class="alert alert-danger">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                            '<p>Failed to add summoner.</p>' +
                            '</div>';
                    $("#alerts").append(alert_msg);
                    $("#add-summoner-loader").hide();
                }
            },

            error: function(message) {
                var alert_msg =
                    '<div class="alert alert-danger">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                        '<p>Failed to get summoner.</p>' +
                        '</div>';
                $("#alerts").append(alert_msg);
                $("#add-summoner-loader").hide();
            }
        });
    });

    $("#summoners-container").delegate('.unlink-summoner', 'click', function() {
        var base_url = $("#base-url").val(),
            row = $(this).closest("tr"),
            sid = row.find('[name="sid"]').val(),
            region = row.find('[name="sregion"]').val();
        row.fadeOut('slow', function(){
            row.remove();
        });
        $.ajax({
            url: base_url+'user/unlink_summoner',
            type: 'POST',

            // Form data
            data: {sid: sid, region: region}
        });
    });

    $("#summoners-container").delegate('.verify-button', 'click', function() {
        var sblock = $(this).parent();
        $("#sid").val(sblock.find( "input[name=sid]" ).val());
        $("#sverkey").val(sblock.find( "input[name=verification_key]" ).val());
        $("#sregion").val(sblock.find( "input[name=sregion]" ).val());
        $("#sname").html(sblock.find( "input[name=sname]" ).val());
        $("#verification_key").html($("#sverkey").val());
        $( "#verification-dialog" ).modal("show");
    });

    $("#verify-summoner").click(function() {
        verify_summoner();
        $("#verification-dialog").modal("hide");
    });
});

function verify_summoner() {
    var base_url = $("#base-url").val(),
        sid = $("#sid").val(),
        region = $("#sregion").val(),
        ver_key = $("#sverkey").val();

    $("#summoners .loader").show();
    $.ajax({
        url: base_url+'user/verify_summoner',  //Server script to process data
        type: 'POST',

        // Data passed
        data: {sid: sid, region: region, code: ver_key},

        success: function (response) {
            if(response == 1) {
                location.reload();
            } else {
                $("#summoners .loader").hide();
                $("#fail-ver-key").html(ver_key);
                $( "#fail-dialog" ).modal( "show" );
            }
        },

        error: function(message) {
            $("#summoners .loader").hide();
            $("#fail-ver-key").html(ver_key);
            $( "#fail-dialog" ).modal( "show" );
        }
    });
}