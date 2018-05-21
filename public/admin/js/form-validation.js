var FormValidation = function () {

    // basic validation
    var handleValidation2 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation

        var form2 = $('#create-form');
        var error2 = $('.alert-danger', form2);
        var success2 = $('.alert-success', form2);

        form2.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                gender: {
                    required: true
                },
                phone_num: {
                    minlength: 10,
                    required: true,
                    number: true
                },
                language_id: {
                    required: true
                },
                privileges: {
                    required: true
                },
                type_id: {
                    required: true
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                success2.hide();
                error2.show();
                App.scrollTo(error2, -200);
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if($(element).prop('tagName') !== 'SELECT') {
                    var icon = $(element).parent('.input-icon').children('i');
                    icon.removeClass('fa-check').addClass("fa-warning");
                    icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
                }
            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group
            },

            unhighlight: function (element) { // revert the change done by hightlight

            },

            success: function (label, element) {
                if($(element).prop('tagName') !== 'SELECT') {
                    var icon = $(element).parent('.input-icon').children('i');
                    icon.removeClass("fa-warning").addClass("fa-check");
                }
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
            },

            submitHandler: function (form) {
                success2.show();
                error2.hide();
                form[0].submit(); // submit the form
            }
        });


    }

    return {
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            gender: {
                required: true,
            },
            phone_num: {
                minlength: 10,
                required: true,
                number: true
            },
            language: {
                required: true,
            },
            birth_date: {
                required: true,
                date: true
            },
            privileges: {
                required: true
            },
            type_id: {
                required: true
            },
        },
        //main function to initiate the module
        init: function () {
            handleValidation2();
        }

    };

}();

jQuery(document).ready(function() {
    FormValidation.init();
});