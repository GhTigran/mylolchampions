$(document).ready(function() {
    $("#resend-verification").click(function(){
        var base_url = $("#base-url").val();
        var username = $("#username").val();

        $("#alerts").html('');

        $.ajax({
            url: base_url+'user/resend_verification',  //Server script to process data
            type: 'POST',

            // Form data
            data: {username: username},

            success: function (response) {
                if(response == 1) {
                    var alert_msg =
                        '<div class="alert alert-success">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                            '<p>Verification email has been resend.</p>' +
                        '</div>';
                    $("#alerts").html(alert_msg);
                } else {
                    var alert_msg =
                        '<div class="alert alert-danger">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                            '<p>Failed to resend verification email. Please contact our support team ' +
                            '<a href="'+base_url+'contact/" title="Contact Us">here</a></p>' +
                            '</div>';
                    $("#alerts").html(alert_msg);
                }
            },

            error: function(message) {
                var alert_msg =
                    '<div class="alert alert-danger">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                        '<p>Failed to resend verification email. Please contact our support team ' +
                        '<a href="'+base_url+'contact/" title="Contact Us">here</a></p>' +
                        '</div>';
                $("#alerts").html(alert_msg);
            }
        });
    });
});