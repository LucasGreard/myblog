<?php

namespace Models;

use Exception;
use Models\SuperglobalManager;
use PHPMailer\PHPMailer\PHPMailer;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

class ContactManager extends Dbconnect
{
    private $dbConnect;
    const TO =  "lucas.greard07@gmail.com";

    public function __construct()
    {
        $this->dbConnect = $this->dbConnect();
    }
    /**
     * @throws exception
     */
    function sendMessage($mailUserSend, $messageUserSend)
    {
        $mail = new PHPMailer;
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'monprojet5.oc@gmail.com';                 // SMTP username
        $mail->Password = 'monProjet5';                           // SMTP password
        $mail->Port = 587;                                    // TCP port to connect to


        $mail->setFrom($mailUserSend, 'Mailer');
        $mail->addAddress(trim('monprojet5.oc@gmail.com'));               // Name is optional
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Mail send from lucasgreard.fr/contact';
        $mail->Body    = $messageUserSend;

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }
}
