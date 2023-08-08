$(".serviceSkills").select2({multiple:true});
$(".items").select2();
function initializeSelect2(){
    $(".unitOfMeasurement").select2();
    $(".serviceCategory").select2();
}
initializeSelect2();
const fileInput = document.getElementById('select-image');
const images = document.getElementById('images');
const totalImages = document.getElementById('total-images');

// Listen to the change event on the <input> element
fileInput.addEventListener('change', (event) => {
    // Get the selected image file
    const imageFiles = event.target.files;

    // Show the number of images selected
    totalImages.innerText = imageFiles.length;

    // Empty the images div
    images.innerHTML = '';

    if (imageFiles.length > 0) {
        // Loop through all the selected images
        for (const imageFile of imageFiles) {
            const reader = new FileReader();

            // Convert each image file to a string
            reader.readAsDataURL(imageFile);

            // FileReader will emit the load event when the data URL is ready
            // Access the string using reader.result inside the callback function
            reader.addEventListener('load', () => {
                // Create new <img> element and add it to the DOM
                images.innerHTML += `
                <div class="image_box">
                    <img width="100" height="100" src='${reader.result}'>
                    <span class='image_name'>${imageFile.name}</span>
                </div>
            `;
            });
        }
    } else {
        // Empty the images div
        images.innerHTML = '';
    }
});


$("body").on("click",".add-more",function(){ 
    let htmlContent = `<div class="row"><div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Select Product <span class="red">*</span></label>
                                <select name="sub_service_products[]" id="" class="form-control items">
                                    <option value="">Select</option>
                                </select>
                                <label id="name-error" class="error" for="mobile"></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Select unit of measurement <span class="red">*</span></label>
                                <select name="sub_service_uom[]" id="" class="form-control unitOfMeasurement">
                                    <option value="">Select</option>
                                </select>
                                <label id="name-error" class="error" for="mobile"></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="name">In Require from client  <span class="red">*</span></label>
                            <div class="form-group">
                                <input type="checkbox" name="sub_service_qty_require[]">
                                <label id="name-error" class="error" for="mobile"></label>
                            </div>
                        </div>
                        <div class="col-md-3 change">
                            <label for=''> </label><br/><a class='btn btn-danger remove'>- Remove</a>
                        </div></div>`;
    $(".add-more-section").append(htmlContent);
});

$("body").on("click",".remove",function(){ 
    $(this).parent().parent().remove();
});

