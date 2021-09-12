<?php

namespace Models;

use Exception;

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
    public function __setTo()
    {
        return $this->to = "lucas.greard07@gmail.com";
    }

    public function setTo($to) : void {
        $this->to = $to;
    }

    public function getTo() : string {
        // aller cherche l'email du user avec id 1 ou droit admin
        return $this->to;
    }

    function sendMessage()
    {
        if (isset($_POST['emailContact']) && isset($_POST['messageContact'])) :
            $emailContact = htmlentities($_POST['emailContact']);
            $messageContact = htmlentities($_POST['messageContact']);

            $messageSend = '
                <html>
                    <head>
                        <title>Message de la part de ' . $emailContact;
            $messageSend .= '
                    <body>
                        <p> Email de contact : ' . $emailContact . '</p>
                        <p>Message : ' . $messageContact . '</p>
                    </body>
                </html>
                        ';
            // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=iso-8859-1';

            mail($this->TO, $emailContact, $messageSend, $headers);
        endif;
    }
}
