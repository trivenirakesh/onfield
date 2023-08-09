function openModals(modal){
    console.log("#"+modal+"");
    $("#"+modal+"").modal('show');
}

// Create engineer form validation 
$.validator.addMethod("pwcheck", function (value) {
    return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of only these
        && /[a-z]/.test(value) // has a lowercase letter
        && /\d/.test(value) // has a digit
});

$("#engineerFrm").validate({
    rules: {
        first_name: {
            required: true,
        },
        last_name: {
            required: true,
        },
        email: {
            required: true,
            email: true,
        },
        mobile: {
            required: true,
            number: true,
            minlength: 10,
            maxlength: 10
        },
        password: {
            required: true,
            minlength: 8,
            pwcheck: true
        },
    },
    messages: {
        first_name: {
            required: "Please enter first name",
        },
        last_name: {
            required: "Please enter last name",
        },
        email: {
            required: "Please enter email",
            email: "Please enter valid email",
        },
        mobile: {
            required: "Please enter mobile",
            number: "Please enter numbers only",
            minlength: "Mobile should be minimum 10 characters",
            maxlength: "Mobile should be max 10 characters",
        },
        password: {
            required: "Please enter password",
            minlength: "Password must 8 characters",
            pwcheck: 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.'
        },
    },
    submitHandler: function (form, e) {
        $(".text-danger").text("");
        e.preventDefault();
        const formBtn = $("#module_form_btn");
        const formLoader = $("#module_form_loader");
        console.log(formLoader);
        $.ajax({
            url: form.action,
            type: "POST",
            data: new FormData(form),
            dataType: "json",
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": csrf_token,
            },
            beforeSend: function () {
                formLoader.show();
                formBtn.prop("disabled", true);
            },
            success: function (result) {
                formLoader.hide();
                formBtn.prop("disabled", false);
                if (result.status) {
                    $("#engineerFrm")[0].reset();
                    toastr.success(result.message);
                    setInterval(window.location.href = 'http://localhost/onfieldnew/admin/engineer/7/edit',2000)
                } else {
                    toastr.error(result.message);
                }
            },
            error: function (result) {
                var errors = result.responseJSON.errors;
                // Clear previous error messages
                $(".error-message").text("");
                // Display validation errors in form fields
                $.each(errors, function (field, messages) {
                    var inputField = $('[name="' + $.trim(field) + '"]');
                    $(".form-group .text-danger").css("display", "block");
                    inputField.closest(".form-group").find(".text-danger").text(messages[0]);
                });
                formLoader.hide();
                formBtn.prop("disabled", false);
            },
        });
        return false;
    },
});