function onSubmit() {
    $form = document.getElementById('contactForm');
    var name = $("input#name").val();
    var email = $("input#email").val();
    var phone = $("input#phone").val();
    var message = $("textarea#message").val();
    var firstName = name; // For Success/Failure Message
    // Check for white space in name for Success/Fail message
    if (firstName.indexOf(' ') >= 0) {
        firstName = name.split(' ').slice(0, -1).join(' ');
    }
    var recaptcha = $('textarea#g-recaptcha-response').val();
    $.ajax({
        url: "././mail/contact_me.php",
        type: "POST",
        data: {
            name: name,
            phone: phone,
            email: email,
            message: message,
            grecaptcharesponse: recaptcha
        },
        cache: false,
        success: function () {
            // Success message
            $('#success').html("<div class='alert alert-success'>");
            $('#success > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                .append("</button>");
            $('#success > .alert-success')
                .append("<strong>Ваше сообщение было отправлено. В близжайшее время с Вами свяжутся.</strong>");
            $('#success > .alert-success')
                .append('</div>');

            //clear all fields
            $('#contactForm').trigger("reset");
            grecaptcha.reset();
        },
        error: function () {
            // Fail message
            $('#success').html("<div class='alert alert-danger'>");
            $('#success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                .append("</button>");
            $('#success > .alert-danger').append("<strong>Извините, " + firstName + ", Кажется произожла непредвиденная ошибка. Попробуйте позже или позвоните по номеру +7XXXXXXXXXX");
            $('#success > .alert-danger').append('</div>');
            //clear all fields
            //$('#contactForm').trigger("reset");
            grecaptcha.reset();
        },
    })

};
