let csrf_token = $("meta[name=csrf-token]").attr("content");
let APP_URL = $("#appurl").val();

function globalFunctionModal(moduleName = '',title,route){
    $.ajax({
        url : APP_URL+'/'+route,
        type : 'GET',
        headers: {
            "X-CSRF-TOKEN": csrf_token,
        },
        success : function(res){
            $("#globalCrudModal").modal('show');
            $(".createupdatemodal").html(res);
            $("#modal_title").text(title);
        }
    })
}

// delete modal
function deleteRecordModule(id, url) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#32297c",
        cancelButtonColor: "#dc3545",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "DELETE",
                data: {
                    _method: "DELETE",
                },
                url: url,
                headers: {
                    "X-CSRF-TOKEN": csrf_token,
                },
                success: function (response) {
                    if (response.status) {
                        $("#data_table_main").DataTable().ajax.reload();
                        toastr.success(response.message);
                    } else {
                        Swal.fire("error!", response.message, "error");
                    }
                },
                error: function () {
                    toastr.error("Please Reload Page.");
                },
            });
        }
    });
}