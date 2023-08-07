// Login form validation 
$("#loginFrm").validate({

    rules: {
        mobile: {
            required: true,
            number: true,
            minlength: 10,
            maxlength: 10
        },
        password: {
            required: true,
        },
    },
    messages: {
        mobile: {
            required: "Please enter mobile",
            number: "Please enter numbers only",
            minlength: "Mobile should be minimum 10 characters",
            maxlength: "Mobile should be max 10 characters",
        },
        password: {
            required: "Please enter password",
        },
    },
    errorPlacement: function (error, element) {

        if (element.parent().hasClass('input-group')) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }

    },

});

$("body").on('click', '.input-group-text .far', function () {
    $(this).toggleClass("fa-eye");
    var input = $("#password");
    if (input.attr("type") === "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }

});

$("#forgotPasswordFrm").validate({

    rules: {
        mobile: {
            required: true,
            number: true,
            minlength: 10,
            maxlength: 10
        },
    },
    messages: {
        mobile: {
            required: "Please enter mobile",
            number: "Please enter numbers only",
            minlength: "Mobile should be minimum 10 characters",
            maxlength: "Mobile should be max 10 characters",
        },
    },
    errorPlacement: function (error, element) {

        if (element.parent().hasClass('input-group')) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }

    },

});