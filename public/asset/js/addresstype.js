let module = $("#page_module").val();
let module_index_url = $("#module_index_url").val();

function removeValidationErrors(){
    var alert = $("#module_form");
    alert.validate().resetForm();
    alert.find(".error").removeClass("error");
    alert.find(".text-danger").text("");
}   
function addModel() {
    removeValidationErrors();
    $("#module_form")[0].reset();
    $("#modal-add-update").modal("show");
    $("#id").val(0);
    $("#modal_title").text(`Add ${module}`);
    $("#preview_div").hide();
    $("#project_btn").html(
        'Add <span style="display: none" id="loader"><i class="fa fa-spinner fa-spin"></i></span>'
    );
}
$(document).ready(function () {
    // View resource
    $(document).on("click", ".module_view_record", function () {
        const id = $(this).parent().data("id");
        const url = $(this).parent().data("url");
        $("#show_modal_title").text(`${module} Detail`);
        $("#view_module_modal").modal("show");
        $("#view_module_modal .loader").addClass("d-flex");
        $.ajax({
            type: "GET",
            data: {
                id: id,
                _method: "SHOW",
            },
            url: `${url}/${id}`,
            headers: {
                "X-CSRF-TOKEN": csrf_token,
            },
            success: function (response) {
                $("#view_module_modal .loader").removeClass("d-flex");
                if (response.status) {
                    $.each(response.data, function (key, value) {
                        $(`#info_${key}`).text(value);
                        if (key == "image") {
                            $(`#info_${key}`).attr("src", value);
                        }
                    });
                } else {
                    toastr.error(response.message);
                }
            },
            error: function () {
                $("#view_module_modal .loader").removeClass("d-flex");
                toastr.error("Please Reload Page.");
            },
        });
    });

    // delete resource
    $(document).on("click", ".module_delete_record", function () {
        const id = $(this).parent().data("id");
        const url = $(this).parent().data("url");
        deleteRecordModule(id, `${url}/${id}`);
    });

    // edit resource
    $(document).on("click", ".module_edit_record", function () {
        removeValidationErrors();
        const id = $(this).parent().data("id");
        const url = $(this).parent().data("url");
        $("#modal_title").text(`Edit ${module}`);
        $("#modal-add-update").modal("show");
        $("#image_preview").attr("");
        $.ajax({
            type: "GET",
            data: {
                id: id,
                _method: "SHOW",
            },
            url: `${url}/${id}`,
            headers: {
                "X-CSRF-TOKEN": csrf_token,
            },
            success: function (response) {
                if (response.status) {
                    $.each(response.data, function (key, value) {
                        if (key == "image") {
                            $("#image_preview").attr("src", value);
                        } else {
                            $(`#${key}`).val(value);
                        }
                    });
                } else {
                    toastr.error(response.message);
                }
            },
            error: function () {
                toastr.error("Please Reload Page.");
            },
        });
        $("#module_form_btn").html(
            'Update <span style="display: none" id="module_form_loader"><i class="fa fa-spinner fa-spin"></i></span>'
        );
    });

    $("#module_form").validate({
        rules: {
            name: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter name",
            },
        },
        submitHandler: function (form, e) {
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
                        $("#module_form")[0].reset();
                        $("#modal-add-update").modal("hide");
                        $("#data_table_main").DataTable().ajax.reload();
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                },
                error: function () {
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

    var table = $("#data_table_main").DataTable({
        processing: true,
        serverSide: true,
        dom: '<f<t><"cm-dataTables-footer d-flex align-items-center float-right"lip>>',
        oLanguage: {
            "sInfo": "_START_-_END_ of _TOTAL_",// text you want show for info section
            "sLengthMenu": "_MENU_"
        },
        buttons: [],
        ajax: module_index_url,
        order: [],
        select: {
            style: "multi",
        },
        columns: [
            {
                data: "action_edit",
                name: "action_edit",
                searchable: false,
                orderable: false,
            },
            {
                data: "name",
                name: "name",
            },
            {
                data: "status_text",
                name: "status",
            },
            {
                data: "action_delete",
                name: "action_delete",
                searchable: false,
                orderable: false,
            },
        ],
    });
});
