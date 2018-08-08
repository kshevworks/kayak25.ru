<?php
// Check for empty fields
if (empty($_POST['name']) ||
    empty($_POST['phone']) ||
    empty($_POST['message'])) {
    echo "No arguments Provided!";
    return false;
}
if (isset($_POST['grecaptcharesponse'])) {
    $result = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfroVoUAAAAABbkedhuUNPcbjuOnMpa2DpMIgLa&response=" . $_POST['grecaptcharesponse'] . "&remoteip=" . $_SERVER['REMOTE_ADDR']), TRUE);

    if ($result['success'] == 1) {
        /*/$token = "349573929:AAFMlL7xlcHJ_NXVE9-oSvmN9DvLa7v466k";
        $chat = "-215721614";


        azaza
        $msg = urlencode("*".$name."*(".$phone."):\n".$message);

        fopen("https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat.
            "&text=".$msg."&parse_mode=Markdown", "");
        fopen("https://api.telegram.org/bot".$token."/sendContact?chat_id=".$chat.
            "&phone_number=".$phone."&first_name=".$name."&disable_notification=true", "");
        */
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $message = $_POST['message'];

        $to = 'info@kayak25.ru'; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
        $email_subject = "Клиентом $name ($phone) оставлено сообщение";
        $email_body = "Клиентом $name оставлено сообщение следующего содержания:\n\n$message\n\nТелефон: $phone";
        $headers = "From: no-reply@kayak25.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
        mail($to, $email_subject, $email_body, $headers);

        return true;
    } else {
        echo "ReCaptcha failed";
        return false;
    }


    /*// Create the email and send the message
    $to = 'yourname@yourdomain.com'; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
    $email_subject = "Website Contact Form:  $name";
    $email_body = "You have received a new message from your website contact form.\n\n"."Here are the details:\n\nName: $name\n\nEmail: $email_address\n\nPhone: $phone\n\nMessage:\n$message";
    $headers = "From: noreply@yourdomain.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
    $headers .= "Reply-To: $email_address";
    mail($to,$email_subject,$email_body,$headers);*/
}

?>