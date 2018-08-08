onCompleted = function () {
    event.preventDefault();
    $form = document.getElementById('order-form');
    var name = $("input#user-name").val();
    var email = $("input#user-email").val();
    var phone = $("input#user-phone").val();
    var firstName = name; // For Success/Failure Message
    // Check for white space in name for Success/Fail message
    if (firstName.indexOf(' ') >= 0) {
        firstName = name.split(' ').slice(0, -1).join(' ');
    }
    var recaptcha = $('textarea#g-recaptcha-response').val();
    $.ajax({
        url: "add-order",
        type: "POST",
        data: {
            'user-name': name,
            'user-phone': phone,
            'user-email': email,
            items: basket,
            grecaptcharesponse: recaptcha
        },
        cache: false,
        success: function (data) {
            console.log(data);
            if (data["result_code"] == 1) {
                // Success message
                $('#success').html("<div class='alert alert-success'>");
                $('#success > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                    .append("</button>");
                $('#success > .alert-success')
                    .append("<strong>Спасибо за заказ, " + firstName + "! В ближайшее время с вами свяжутся.</strong><br>Номер вашего заказа: #" + data['orderNumber']);
                $('#success > .alert-success')
                    .append('</div>');
                $('#contactForm').trigger("reset");

            }
            else {
                $('#success').html("<div class='alert alert-danger'>");
                $('#success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                    .append("</button>");
                $('#success > .alert-danger').append("<strong>Извините, " + firstName + ", Кажется произошла ошибка " + data['result'] + ". Попробуйте обновить страницу.<br>");
                $('#success > .alert-danger').append('</div>');
                //clear all fields

            }
            grecaptcha.reset();

        },
        error: function () {
            // Fail message
            $('#success').html("<div class='alert alert-danger'>");
            $('#success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                .append("</button>");
            $('#success > .alert-danger').append("<strong>Извините, " + firstName + ", Кажется произожла непредвиденная ошибка. Попробуйте позже");
            $('#success > .alert-danger').append('</div>');
            //clear all fields
            //$('#contactForm').trigger("reset");
            grecaptcha.reset();
        },
    });
}


$('#order-form').submit(function (event) {
    event.preventDefault();
    grecaptcha.execute();
});
