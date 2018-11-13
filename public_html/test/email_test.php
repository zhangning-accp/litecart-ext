<?php
    /**
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/11/13
     * Time: 10:30
     */
    require_once ('email_tools/PHPMailer.php');
    require_once ('email_tools/OAuth.php');
    require_once ('email_tools/SMTP.php');
    require_once ('email_tools/Exception.php');
    //function sendEmail
    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
    $mail->SMTPDebug = 0;
//Set the hostname of the mail server
    $mail->Host = 'smtp.126.com';
//Set the SMTP port number - likely to be 25, 465 or 587
    $mail->Port = 25;
//Whether to use SMTP authentication
    $mail->SMTPAuth = true;
//Username to use for SMTP authentication
    $mail->Username = 'zhangning_holley@126.com';
//Password to use for SMTP authentication
    $mail->Password = '140103zxy';
//Set who the message is to be sent from
    $mail->setFrom('zhangning_holley@126.com', 'First Last');
//Set an alternative reply-to address
//    $mail->addReplyTo('zhangning_holley@126.com', 'First Last');
//Set who the message is to be sent to
    $mail->addAddress('909604945@qq.com', 'John Doe');
//Set the subject line
    $mail->Subject = 'PHPMailer SMTP test';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
    //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
    $mail->msgHTML("<h1>Hi error!");
//Replace the plain text body with one created manually
    $mail->AltBody = 'This is a plain-text message body';
//Attach an image file
    //$mail->addAttachment('images/phpmailer_mini.png');
//send the message, check for errors
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message sent!';
    }