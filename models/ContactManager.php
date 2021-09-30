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

    const TO =  "monprojet5.oc@gmail.com";

    /**
     * @throws exception
     */
    function sendMessage($mailUserSend, $messageUserSend)
    {
        try {
            $mail = new PHPMailer;
            $mail->isSMTP();                                        // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                         // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                                 // Enable SMTP authentication
            $mail->Username = self::TO;                             // SMTP username
            $mail->Password = 'monProjet5';                         // SMTP password
            $mail->Port = 587;                                      // TCP port to connect to


            $mail->setFrom($mailUserSend, 'Mailer');
            $mail->addAddress(trim(self::TO));     // Name is optional
            $mail->addReplyTo($mailUserSend, 'Mailer');
            $mail->isHTML(true);                                    // Set email format to HTML
            $mail->Subject = 'Mail send from lucasgreard.fr/contact';
            $mail->Body = $messageUserSend;

            return $mail->send();
        } catch (\Error | \Exception $e) {
            return false;
        }
    }
}
