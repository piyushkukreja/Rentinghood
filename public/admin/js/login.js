var Login = function() {

    var handleLogin = function() {

        var loginForm = $('#login-form');
        loginForm.on('submit', function (e) {

            e.preventDefault();
            e.stopPropagation();
            var submitButton = loginForm.find('button[type="submit"]');
            submitButton.html('Logging in');
            submitButton.attr('disabled', true);
            $('.alert-danger.alert').hide();

            var formData = loginForm.serialize();

            $.ajax({
                    type: 'POST',
                    url: loginForm.attr('action'),
                    data: formData,
                    success: function (response) {

                        if(response.message = 'success')
                            window.location.href = '/appointments';
                        submitButton.html('Login Successful');

                    },
                    error: function (xhr, status, error) {

                        submitButton.html('Login');
                        submitButton.attr('disabled', false);
                        var errors = JSON.parse(xhr.responseText);
                        var emailError = errors["errors"]["email"][0];
                        $('.alert-danger.alert').find('span').html(emailError);
                        $('.alert-danger.alert').slideDown();

                    }
                });

        })

    };

    return {
        //main function to initiate the module
        init: function() {

            handleLogin();

        }

    };

}();

jQuery(document).ready(function() {
    Login.init();
});