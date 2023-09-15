<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

if(isset($_POST["submit"])){

  $message = '
       <body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
          <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"font-family: sans-serif;">
              <tr>
                  <td>
                      <table style="background-color: #f2f3f8; max-width:670px; margin:0 auto;" width="100%" border="0"
                          align="center" cellpadding="0" cellspacing="0">
                          <tr>
                              <td style="height:80px;">&nbsp;</td>
                          </tr>
                          <!-- Logo -->
                          <tr>
                              <td style="text-align:center;">
                                <a href="http://jupitorcash.com/" title="logo" target="_blank">
                                  <img width="60" src="http://jupitorcash.com/assets/images/logo.png" title="logo" alt="logo">
                                </a>
                              </td>
                          </tr>
                          <tr>
                              <td style="height:20px;">&nbsp;</td>
                          </tr>
                          <!-- Email Content -->
                          <tr>
                              <td>
                                  <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                      style="max-width:670px; background:#fff; border-radius:3px;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);padding:0 40px;">
                                      <tr>
                                          <td style="height:40px;">&nbsp;</td>
                                      </tr>
                                      <!-- Title -->
                                      <tr>
                                          <td style="padding:0 15px; text-align:center;">
                                              <h1 style="color:#1e1e2d; font-weight:400; margin:0;font-size:32px;font-family:sans-serif;">Car Loan Form</h1>
                                              <span style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; 
                                              width:100px;"></span>
                                          </td>
                                      </tr>
                                      <!-- Details Table -->
                                      <tr>
                                          <td>
                                              <table cellpadding="0" cellspacing="0"
                                                  style="width: 100%; border: 1px solid #ededed">
                                                  <tbody>
                                                      <tr>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed; border-right: 1px solid #ededed; width: 35%; font-weight:500; color:rgba(0,0,0,.64)">
                                                              Name:</td>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">
                                                              '.$_POST["name"].'</td>
                                                      </tr>
                                                      <tr>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed; border-right: 1px solid #ededed; width: 35%; font-weight:500; color:rgba(0,0,0,.64)">
                                                              Father Name:</td>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">
                                                              '.$_POST["father_name"].'</td>
                                                      </tr>
                                                      <tr>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed; border-right: 1px solid #ededed; width: 35%; font-weight:500; color:rgba(0,0,0,.64)">
                                                              Mother Name:</td>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">
                                                              '.$_POST["mother_name"].'</td>
                                                      </tr>
                                                      
                                                      <tr>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed;border-right: 1px solid #ededed; width: 35%; font-weight:500; color:rgba(0,0,0,.64)">
                                                             Pancard:</td>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">
                                                              '.$_POST["pancard"].'</td>
                                                      </tr>
                                                                                                     
                                                      <tr>
                                                          <td
                                                              style="padding: 10px; border-right: 1px solid #ededed; width: 35%;font-weight:500; color:rgba(0,0,0,.64)">
                                                              Aadhar Card:</td>
                                                          <td style="padding: 10px; color: #455056;">'.$_POST["adhar_card"].'</td>
                                                      </tr>


                                                      <tr>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed;border-right: 1px solid #ededed; width: 35%; font-weight:500; color:rgba(0,0,0,.64)">
                                                             Residence Address:</td>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">
                                                              '.$_POST["address"].'</td>
                                                      </tr>

                                                      <tr>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed;border-right: 1px solid #ededed; width: 35%; font-weight:500; color:rgba(0,0,0,.64)">
                                                             Office Address:</td>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">
                                                              '.$_POST["office_addrss"].'</td>
                                                      </tr>

                                                      <tr>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed;border-right: 1px solid #ededed; width: 35%; font-weight:500; color:rgba(0,0,0,.64)">
                                                             Gender:</td>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">
                                                              '.$_POST["gender"].'</td>
                                                      </tr>

                                                      <tr>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed;border-right: 1px solid #ededed; width: 35%; font-weight:500; color:rgba(0,0,0,.64)">
                                                             Email:</td>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">
                                                              '.$_POST["email"].'</td>
                                                      </tr>

                                                      <tr>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed;border-right: 1px solid #ededed; width: 35%; font-weight:500; color:rgba(0,0,0,.64)">
                                                             Mobile:</td>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">
                                                              '.$_POST["mobile"].'</td>
                                                      </tr>

                                                     
                                                      <tr>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed;border-right: 1px solid #ededed; width: 35%; font-weight:500; color:rgba(0,0,0,.64)">
                                                             DOB:</td>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">
                                                              '.$_POST["dob"].'</td>
                                                      </tr>

                                                      <tr>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed;border-right: 1px solid #ededed; width: 35%; font-weight:500; color:rgba(0,0,0,.64)">
                                                             Company Name:</td>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">
                                                              '.$_POST["company_name"].'</td>
                                                      </tr>

                                                       <tr>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed;border-right: 1px solid #ededed; width: 35%; font-weight:500; color:rgba(0,0,0,.64)">
                                                             Designation:</td>
                                                          <td
                                                              style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">
                                                              '.$_POST["designation"].'</td>
                                                      </tr>

                                                  </tbody>
                                              </table>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td style="height:40px;">&nbsp;</td>
                                      </tr>
                                  </table>
                              </td>
                          </tr>
                          <tr>
                              <td style="height:20px;">&nbsp;</td>
                          </tr>
                       
                      </table>
                  </td>
              </tr>
          </table>
      </body>
       ';

  $mail = new PHPMailer(true);

  $mail->isSMTP();
  $mail->Host = 'jupitorcash.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'info@jupitorcash.com'; // Your gmail
  $mail->Password = 'Admin@123'; // Your gmail app password
  $mail->SMTPSecure = 'ssl';
  $mail->Port = 465;

   $mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);


  $file_name = $_FILES['adhar_card']['name'];
  $file_tmp = $_FILES['adhar_card']['tmp_name'];
  move_uploaded_file($file_tmp,"uploads/".$file_name); 
  $mail->addAttachment("uploads/".$file_name);


  $file_name2 = $_FILES['pancard']['name'];
  $file_tmp2 = $_FILES['pancard']['tmp_name'];
  move_uploaded_file($file_tmp2,"uploads/".$file_name2); 
  $mail->addAttachment("uploads/".$file_name2);



  $mail->setFrom($_POST['email']); // Your gmail where send mail

  $mail->addAddress("info@jupitorcash.com"); // Your gmail where send mail

  $mail->isHTML(true);

  $mail->Subject = "Car Loan Enquiry";
  $mail->Body = $message;

  if($mail->send())
  {
    unlink("uploads/".$file_name);
    unlink("uploads/".$file_name2);
    echo
  "
 <script>
  alert('Sent Successfully');
  document.location.href = 'index.php';
  </script>
  ";
  }


// die;
  
}
?>

