<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

if(isset($_POST["send"])){

 $message = '
        <h3 align="center"> Form Details</h3>
        <table border="1" width="100%" cellpadding="5" cellspacing="5">
          <tr>
            <td width="30%"> Name</td>
            <td width="70%">'.$_POST["name"].'</td>
          </tr>
          <tr>
            <td width="30%">Email Id</td>
            <td width="70%">'.$_POST["email"].'</td>
          </tr>
          <tr>
            <td width="30%">Subject</td>
            <td width="70%">'.$_POST["subject"].'</td>
          </tr>
          <tr>
            <td width="30%">Message</td>
            <td width="70%">'.$_POST["message"].'</td>
          </tr>
          <tr>
            <td width="30%">Phone</td>
            <td width="70%">'.$_POST["mobile"].'</td>
          </tr>
          
        </table>
      ';
  

  $mail = new PHPMailer(true);

  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'complaint@legalforumindia.com'; // Your gmail
  $mail->Password = 'cbpmlotlznybhapm'; // Your gmail app password
  $mail->SMTPSecure = 'ssl';
  $mail->Port = 465;

  $mail->setFrom('complaint@legalforumindia.com'); // Your gmail where send mail

  $mail->addAddress("complaint@legalforumindia.com"); // Your gmail where send mail

  $mail->isHTML(true);

  $mail->Subject = $_POST["subject"];
  $mail->Body = $message;

  $mail->send();

  echo
  "
  <script>
  alert('Sent Successfully');
  document.location.href = 'index.php';
  </script>
  ";
}
?>

