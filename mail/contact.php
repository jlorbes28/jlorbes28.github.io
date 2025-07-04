<?php
// Load Composer's autoloader or include PHPMailer classes manually
require '../lib/PHPMailer/PHPMailer.php';
require '../lib/PHPMailer/SMTP.php';
require '../lib/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(
    empty($_POST['name']) ||
    empty($_POST['subject']) ||
    empty($_POST['message']) ||
    !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
) {
    http_response_code(400);
    echo "Please complete the form and provide a valid email address.";
    exit();
}

$name = strip_tags(htmlspecialchars($_POST['name']));
$email = strip_tags(htmlspecialchars($_POST['email']));
$m_subject = strip_tags(htmlspecialchars($_POST['subject']));
$message = strip_tags(htmlspecialchars($_POST['message']));

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'lorbes90@gmail.com'; // Your Gmail address
    $mail->Password   = 'your_app_password';   // Your Gmail App Password (not your Gmail password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('yourgmail@gmail.com', 'Website Contact');
    $mail->addAddress('lorbes90@gmail.com'); // Your receiving email

    // Content
    $mail->isHTML(false);
    $mail->Subject = "$m_subject: $name";
    $mail->Body    = "You have received a new message from your website contact form.\n\n"
                   . "Here are the details:\n\n"
                   . "Name: $name\n"
                   . "Email: $email\n"
                   . "Subject: $m_subject\n"
                   . "Message: $message";

    $mail->send();
    http_response_code(200);
    echo "Message sent successfully!";
} catch (Exception $e) {
    error_log("Mailer Error: {$mail->ErrorInfo}");
    http_response_code(500);
    echo "Mail sending failed. Please try again later.";
}
?>
