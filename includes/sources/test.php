<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// LOAD PHPMailer FILES
require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';
// CREATE NEW MAIL INSTANCE
$mail = new PHPMailer(true);

try {

    /*
    |--------------------------------------------------------------------------
    | SMTP SETTINGS
    |--------------------------------------------------------------------------
    */

    $mail->isSMTP();

    // Gmail SMTP Server
 
    $mail->Host = 'smtp.gmail.com';


    // Enable SMTP Authentication
    $mail->SMTPAuth = true;

    // YOUR GMAIL ADDRESS
    $mail->Username = 'veronicmuthiga@gmail.com';

    // YOUR GOOGLE APP PASSWORD
    $mail->Password = 'lbcp sxnr izbt gjhr';

    // Encryption
   
    $mail->SMTPSecure = 'ssl';


    // TCP Port
    $mail->Port = 465;

    /*
    |--------------------------------------------------------------------------
    | EMAIL SETTINGS
    |--------------------------------------------------------------------------
    */

    // Sender
    $mail->setFrom('veronicmuthiga@gmail.com', 'SMTP Test');

    // Receiver
    $mail->addAddress('veronicmuthiga@gmail.com');

    // Email Format
    $mail->isHTML(true);

    // Subject
    $mail->Subject = 'SMTP Test Email';

    // Message
    $mail->Body = '
        <h2>SMTP Test Successful</h2>
        <p>Your PHPMailer SMTP configuration is working correctly.</p>
    ';
$mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';


    // SEND EMAIL
    $mail->send();

    echo "
        <div style='padding:20px;background:#d4edda;color:#155724;'>
            EMAIL SENT SUCCESSFULLY
        </div>
    ";

} catch (Exception $e) {

    echo "
        <div style='padding:20px;background:#f8d7da;color:#721c24;'>
            MAIL ERROR: {$mail->ErrorInfo}
        </div>
    ";
}
?>
