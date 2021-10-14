$(document).ready(function () {

    /***************************************/
    /* Form validation */
    /***************************************/
    $('#forms-login').validate({

        /* @validation states + elements */
        errorClass: 'error-view',
        validClass: 'success-view',
        errorElement: 'span',
        onkeyup: false,
        onclick: false,

        /* @validation rules */
        rules: {
            username: {
                required: true
            },
            password: {
                required: true
            }
        },
        messages: {
            username: {
                required: 'Especifique el Usuario'
            },
            password: {
                required: 'Especifique la Clave'
            }
        },
        // Add class 'error-view'
        highlight: function (element, errorClass, validClass) {
            $(element).closest('.input').removeClass(validClass).addClass(errorClass);
            if ($(element).is(':checkbox') || $(element).is(':radio')) {
                $(element).closest('.check').removeClass(validClass).addClass(errorClass);
            }
        },
        // Add class 'success-view'
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest('.input').removeClass(errorClass).addClass(validClass);
            if ($(element).is(':checkbox') || $(element).is(':radio')) {
                $(element).closest('.check').removeClass(errorClass).addClass(validClass);
            }
        },
        // Error placement
        errorPlacement: function (error, element) {
            if ($(element).is(':checkbox') || $(element).is(':radio')) {
                $(element).closest('.check').append(error);
            } else {
                $(element).closest('.unit').append(error);
            }
        },
        // Submit the form
        submitHandler: function () {
            $('#forms-login').ajaxSubmit({

                // Server response placement
                target: '#forms-login .response',

                // If error occurs
                error: function (xhr) {
                    $('#forms-login .response').html('An error occured: ' + xhr.status + ' - ' + xhr.statusText);
                },

                // Before submiting the form
                beforeSubmit: function () {
                    // Add class 'processing' to the submit button
                    $('#forms-login button[type="submit"]').attr('disabled', true).addClass('processing');
                },

                // If success occurs
                success: function (data) {
                    // Remove class 'processing'
                    $('#forms-login button[type="submit"]').attr('disabled', false).removeClass('processing');

                    // Remove classes 'error-view' and 'success-view'
                    $('#forms-login .input').removeClass('success-view error-view');

                    if (data.status == 'fail') {
                        // Prevent submitting the form while success message is shown
                        $('#forms-login button[type="submit"]').attr('disabled', true);

                        // Reset form
                        $('#forms-login').resetForm();

                        $('#forms-login .response').html(data.message);
                        setTimeout(function () {
                            // Delete success message after 5 seconds
                            $('#forms-login .response').removeClass('success-message').html('');

                            // Make submit button available
                            $('#forms-login button[type="submit"]').attr('disabled', false);
                        }, 3000);
                    }

                    // If response for the server is a 'success-message'
                    else if (data.status == 'success') {
                       // window.location.replace("./index.php?view=home");
                        //./?action = processlogin
                        window.location = './?view=home';
                    }
                }
            });
        }
    });
    /***************************************/
    /* end form validation */
    /***************************************/
});