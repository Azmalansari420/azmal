<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require APPPATH.'libraries/phpmailer/Exception.php';
require APPPATH.'libraries/phpmailer/PHPMailer.php';
require APPPATH.'libraries/phpmailer/SMTP.php';

class Mail extends CI_Model
{

  public function sendmail($body)
  {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'liftyfylogistics.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'noreply@liftyfylogistics.com'; // Your gmail
    $mail->Password = 'Admin@123[];'; // Your gmail app password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('noreply@liftyfylogistics.com'); // Your gmail

    $mail->addAddress("noreply@liftyfylogistics.com");

    $mail->isHTML(true);

    $mail->Subject = $_POST["subject"];
    $mail->Body = $body;

    $mail->send();

    echo
    "
    <script>
    alert('Sent Successfully');
    document.location.href = 'index';
    </script>
    ";
  }
}
?>


