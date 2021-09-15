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

    public function setTo($to): void
    {
        $this->to = $to;
    }

    public function getTo(): string
    {
        // aller cherche l'email du user avec id 1 ou droit admin
        return $this->to;
    }

    function sendMessage()
    {
        $emailContact = filter_input(INPUT_POST, 'emailContact', FILTER_SANITIZE_EMAIL);
        $messageContact = filter_input(INPUT_POST, 'messageContact', FILTER_SANITIZE_STRING);
        if (isset($emailContact) && isset($messageContact)) :

        endif;
    }
}
