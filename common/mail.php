<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  require 'forgot/PHPMailer-master/src/Exception.php';
  require 'forgot/PHPMailer-master/src/PHPMailer.php';
  require 'forgot/PHPMailer-master/src/SMTP.php';

function send_mail($recipient,$subject,$message)
{

  $mail = new PHPMailer();
  $mail->IsSMTP();

  $mail->SMTPDebug  = 0;  
  $mail->SMTPAuth   = TRUE;
  $mail->SMTPSecure = "tls";
  $mail->Port       = 587;
  $mail->Host       = "smtp.gmail.com";
  //$mail->Host       = "smtp.mail.yahoo.com";
  $mail->Username   = "schema.feedback@gmail.com";
  $mail->Password   = "bthltpqtrqeandff";

  $mail->IsHTML(true);
  $mail->AddAddress($recipient, "recipient-name");
  $mail->SetFrom("schema.feedback@gmail.com", "Schema");
  //$mail->AddReplyTo("reply-to-email", "reply-to-name");
  //$mail->AddCC("cc-recipient-email", "cc-recipient-name");
  $mail->Subject = $subject;
  $content = $message;

  $mail->MsgHTML($content); 
  if(!$mail->Send()) {
    //echo "Error while sending Email.";
    //var_dump($mail);
    return false;
  } else {
    //echo "Email sent successfully";
    return true;
  }

}