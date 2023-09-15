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
            <td width="30%">School Name</td>
            <td width="70%">'.$_POST["school_name"].'</td>
          </tr>
          <tr>
            <td width="30%">Roll No</td>
            <td width="70%">'.$_POST["roll_no"].'</td>
          </tr>
          
        </table>
      ';
  

  $mail = new PHPMailer(true);

  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'noreplymailconfig@gmail.com'; // Your gmail
  $mail->Password = 'mnfkxrtwltxukxyk'; // Your gmail app password
  $mail->SMTPSecure = 'ssl';
  $mail->Port = 465;

  $mail->setFrom('azmal.codediffusion@gmail.com'); // Your gmail where send mail

  $mail->addAddress("azmal.codediffusion@gmail.com"); // Your gmail where send mail

  $mail->isHTML(true);

  $mail->Subject = 'Contact Form';
  $mail->Body = $message;

  $mail->send();

  echo
  "
  <script>
  alert('Sent Successfully');
  document.location.href = 'thankyou.php';
  </script>
  ";
}
?>

