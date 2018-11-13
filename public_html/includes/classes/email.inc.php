<?php

    /**
     * email class
     * Class email
     */
  class email {
    public function sendEmail($toEmail,$subject='',$messageBody='') {

        $smtp_host = settings::get('smtp_host');
        $smtp_port = settings::get('smtp_port');
        $smtp_user_name = settings::get('smtp_username');
        $smtp_password = settings::get('smtp_password');
        $char_set = language::$selected['charset'];

        $mail = new PHPMailer();
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
        $mail->SMTPDebug = 2;
//Set the hostname of the mail server
        $mail->Host = $smtp_host;
//Set the SMTP port number - likely to be 25, 465 or 587
        $mail->Port = $smtp_port;
        $mail->CharSet = $char_set;
//Whether to use SMTP authentication
        $mail->SMTPAuth = true;
//Username to use for SMTP authentication
        $mail->Username = $smtp_user_name;
//Password to use for SMTP authentication
        $mail->Password = $smtp_password;
//Set who the message is to be sent from
        $mail->setFrom($smtp_user_name);
//Set an alternative reply-to address
//    $mail->addReplyTo('zhangning_holley@126.com', 'First Last');
//Set who the message is to be sent to
        $mail->addAddress($toEmail);
//Set the subject line
        $mail->Subject = $subject;
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
        //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
        $mail->msgHTML($messageBody);
//Replace the plain text body with one created manually
//        $mail->AltBody = 'This is a plain-text message body';
//Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');
//send the message, check for errors
        if (!$mail->send()) {
            trigger_error('Failed sending email to '. $toEmail . ", error info:".$mail->ErrorInfo, E_USER_WARNING);
            return false;
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return true;
            //echo 'Message sent!';
        }
    }
  }
