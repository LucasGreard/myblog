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
    * $mail->isSMTP();                  Set mailer to use SMTP
    * $mail->Host                       Specify main and backup SMTP servers
    * $mail->SMTPAuth                   Enable SMTP authentication
    * $mail->Username                   SMTP username
    * $mail->Password                   SMTP password
    * $mail->Port                       TCP port to connect to
    * $mail->addAddress();              Name is optional
    * $mail->isHTML();                  Set email format to HTML
    */
    function sendMessage($mailUserSend, $messageUserSend)
    {
        try {
            $mail = new PHPMailer;
            $mail->isSMTP();                                        
            $mail->Host = 'smtp.gmail.com';                         
            $mail->SMTPAuth = true;                                 
            $mail->Username = self::TO;                             
            $mail->Password = 'monProjet5';                         
            $mail->Port = 587;                                     


            $mail->setFrom($mailUserSend, 'Mailer');
            $mail->addAddress(trim(self::TO));     
            $mail->addReplyTo($mailUserSend, 'Mailer');
            $mail->isHTML(true);                                   
            $mail->Subject = 'Mail send from lucasgreard.fr/contact';
            $mail->Body = $messageUserSend;

            return $mail->send();
        } catch (\Error | \Exception $e) {
            return false;
        }
    }
}
