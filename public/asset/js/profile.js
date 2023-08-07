$("#profile_frm").validate({
    rules: {
        first_name: {
            required: true,
        },
        last_name: {
            required: true,
        },
        mobile: {
            required: true,
            digits: true,
            minlength: 10,
            maxlength: 10,
        },
        email: {
            required: true,
            email: true,
        },
    },
    messages: {
        first_name: {
            required: "Please enter firstname",
        },
        last_name: {
            required: "Please enter lastname",
        },
        email: {
            required: "Please enter email",
            email: "Please enter valid email",
        },
        mobile: {
            required: "Please enter your mobile number.",
            digits: "Please enter a valid mobile number.",
            minlength: "Mobile number must be at least 10 digits.",
            maxlength: "Mobile number must not exceed 10 digits.",
        },
    },
    submitHandler: function(form, e) {
        $(".text-danger").text("");
        e.preventDefault();
        console.log(form)
        const formbtn = $('#profile_frm_btn');
        const formloader = $('#profile_frm_loader');
        $.ajax({
            url: form.action,
            type: "POST",
            data: new FormData(form),
            dataType: 'json',
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            beforeSend: function() {
                formloader.show();
                formbtn.prop('disabled', true);
            },
            success: function(result) {
                formloader.hide();
                formbtn.prop('disabled', false);
                if (result.status) {
                    toastr.success(result.message);
                } else {
                    toastr.error(result.message);
                }
            },
            error: function(result) {
                var errors = result.responseJSON.errors;
                console.log("result", errors);
                // Clear previous error messages
                $(".error-message").text("");
                // Display validation errors in form fields
                $.each(errors, function(field, messages) {
                    console.log(field, messages);
                    var inputField = $('[name="' + field + '"]');
                    $(".form-group .error").css("display", "block");
                    inputField.closest(".form-group").find(".text-danger").text(messages[0]);
                });
                // toastr.error("Please Reload Page.");
                formloader.hide();
                formbtn.prop("disabled", false);
            },
        });
        return false;
    }
});

$("#password_frm").validate({
    rules: {
        old_password: {
            required: true,
        },
        password: {
            minlength: 8,
        },
        password_confirmation: {
            minlength: 8,
            equalTo: '[name="password"]'
        },
    },
    messages: {

        old_password: {
            required: "Please enter old password",
            minlength: "Please enter old password atleast 8 character!"
        },
        password: {
            required: "Please enter password",
            minlength: "Please enter password atleast 8 character!"
        },
        password_confirmation: {
            required: "Please enter confirm password"
        },

    },
    submitHandler: function(form, e) {
        e.preventDefault();
        const formbtn = $('#password_frm_btn');
        const formloader = $('#password_frm_loader');
        $.ajax({
            url: form.action,
            type: "POST",
            data: new FormData(form),
            dataType: 'json',
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            beforeSend: function() {
                $(formloader).show();
                $(formbtn).prop('disabled', true);
            },
            success: function(result) {
                $(formloader).hide();
                $(formbtn).prop('disabled', false);
                if (result.status) {
                    $("#password,#password_confirmation,#old_password").val('');
                    toastr.success(result.message);
                } else {
                    toastr.error(result.message);
                }
            },
            error: function(result) {
                var errors = result.responseJSON.errors;
                console.log("result", errors);
                // Clear previous error messages
                $(".error-message").text("");
                // Display validation errors in form fields
                $.each(errors, function(field, messages) {
                    console.log(field, messages);
                    var inputField = $('[name="' + field + '"]');
                    $(".form-group .error").css("display", "block");
                    inputField.closest(".form-group").find(".text-danger").text(messages[0]);
                });
                // toastr.error("Please Reload Page.");
                formloader.hide();
                formbtn.prop("disabled", false);
            },
        });
        return false;
    }
});