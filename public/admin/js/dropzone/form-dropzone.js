var FormDropzone = function () {


    return {
        //main function to initiate the module
        init: function (base_url, csrf) {

            Dropzone.options.myDropzone = {
                url: base_url + '/a/products/bulk',
                dictDefaultMessage: "",
                uploadMultiple: true,
                acceptedFiles: "image/*",
                autoProcessQueue: false,
                init: function() {

                    var wrapperThis = this;

                    $('#bulk-upload-form').on('submit', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        wrapperThis.processQueue();
                    });

                    this.on('sendingmultiple', function(data, xhr, formData) {
                        formData.append('import_file', jQuery('#import-file')[0].files[0]);
                        formData.append('lender_id', jQuery('#lender_id').val());
                        formData.append('_token', csrf);
                    });

                    this.on('addedfile', function(file) {
                        // Create the remove button
                        var removeButton = Dropzone.createElement("<a href='javascript:;'' class='btn red btn-sm btn-block'>Remove</a>");

                        // Capture the Dropzone instance as closure.
                        var _this = this;

                        // Listen to the click event
                        removeButton.addEventListener("click", function(e) {
                            // Make sure the button click doesn't submit the form:
                            e.preventDefault();
                            e.stopPropagation();

                            // Remove the file preview.
                            _this.removeFile(file);
                            // If you want to the delete the file on the server as well,
                            // you can do the AJAX request here.
                        });

                        // Add the button to the file preview element.
                        file.previewElement.appendChild(removeButton);
                    });
                }
            }
        }
    };
}();