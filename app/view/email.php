<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if(isset($_REQUEST['to'])){
    $to = $_REQUEST['to'];
    $name = $_REQUEST['name']; 
    $subject = $_REQUEST['subject'];
    $content = $_REQUEST['message'];
    send_otp($to, $name, $subject, $content);
}
   
function send_otp($to, $name, $subject, $content){

    //Load Composer's autoloader
    require '../vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'fyp24s215@gmail.com';                     //SMTP username
        $mail->Password   = 'nsbdfjrgxjlosnfm';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        # For the following, only include in development. Once hosted, put in if-else statement to check if it is in development or production
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        //Recipients
        $mail->setFrom('fyp24s215@gmail.com', 'FaceLock');
        $mail->addAddress($to, $name);     //change recipient to $to

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = "Dear $name,<br><br><font size='4'>Your OTP For Login: <b>".$content."</b><br>
        This OTP is valid for 5 minutes.
        </font>";
        
        $mail->send();
        
        // echo 'OTP has been sent';
    } catch (Exception $e) {
        echo "OTP could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

}