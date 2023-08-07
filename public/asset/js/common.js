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

function load_preview_image(input, div = "preview_div",imgDiv = "image_preview") {
    let imgParentDiv = `#${div}`;
    let imgPreiwDiv = `#${imgDiv}`;
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(imgParentDiv).show();
            $(imgPreiwDiv).attr("src", e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        $(imgParentDiv).hide();
    }
}

$('.letter-accept').keypress(function (e) {
    var regex = new RegExp("^[a-zA-Z ]+$");
    var strigChar = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(strigChar)) {
        return true;
    }
    return false
});

$('.numbers-accept').on('keypress',function(e){
    var deleteCode = 8;  var backspaceCode = 46;
    var key = e.which;
    if ((key>=48 && key<=57) || key === deleteCode || key === backspaceCode || (key>=37 &&  key<=40) || key===0)    
    {    
        character = String.fromCharCode(key);
        if( character != '.' && character != '%' && character != '&' && character != '(' && character != '\'' ) 
        { 
            return true; 
        }
        else { return false; }
     }
     else   { return false; }
});