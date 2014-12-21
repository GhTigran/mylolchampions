$(document).ready(function() {

    $("#username").change (function() {
        checkUsername();
    });

    $("#email").change (function() {
        checkEmail();
    });

    $("#retype-pass").change (function() {
        checkPass();
    });

    $("#reg-form").submit( function() {
        var is_valid;
        is_valid = checkUsername();
        is_valid = checkEmail() && is_valid;
        is_valid = checkPass() && is_valid;
        console.log(is_valid);
        return is_valid;
    });

});

function checkUsername() {
    var base_url = $("#base-url").val();
    var field = $("#username");
    var form_group = field.parents(".form-group");
    var helper_text = form_group.find(".help-block");

    if(!field.val()) {
        form_group.removeClass("has-success").addClass('has-error');
        helper_text.html("This field is required");
        return false;
    }

    $.ajax({
        url: base_url+'user/check_existence',  //Server script to process data
        type: 'POST',

        // Form data
        data: {username: field.val()},

        success: function (response) {
            if(response == 1) {
                form_group.removeClass("has-success").addClass('has-error');
                helper_text.html("Username already exists");
                return false;
            } else {
                form_group.removeClass("has-error").addClass('has-success');
                helper_text.html('<span class="glyphicon glyphicon-ok"></span>');
                return true;
            }
        },

        error: function(message) {
            form_group.removeClass("has-success").addClass('has-error');
            return false;
        }
    });
    return true;
}

function checkEmail() {
    var field = $("#email");
    var form_group = field.parents(".form-group");
    var helper_text = form_group.find(".help-block");

    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    if(pattern.test(field.val())) {
        form_group.removeClass("has-error").addClass("has-success");
        helper_text.html('<span class="glyphicon glyphicon-ok"></span>');
        return true;
    } else {
        form_group.removeClass("has-success").addClass("has-error");
        helper_text.html("Please provide a valid email address");
        return false;
    }
}

function checkPass() {
    var field = $("#pass"),
        form_group = field.parents(".form-group"),
        helper_text = form_group.find(".help-block"),
        pass = field.val();
    if(pass) {
        form_group.removeClass("has-error").addClass("has-success");
        helper_text.html('<span class="glyphicon glyphicon-ok"></span>');

        field = $("#retype-pass");
        form_group = field.parents(".form-group");
        helper_text = form_group.find(".help-block");

        if(field.val() == pass) {
            form_group.removeClass("has-error").addClass("has-success");
            helper_text.html('<span class="glyphicon glyphicon-ok"></span>');
            return true;
        } else {
            form_group.removeClass("has-success").addClass("has-error");
            helper_text.html('<span class="glyphicon glyphicon-remove"></span>');
            return false;
        }
    } else {
        form_group.removeClass("has-success").addClass("has-error");
        helper_text.html("This field is required");
        return false;
    }
}