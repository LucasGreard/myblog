<?php

namespace Models;

use Exception;
use Models\SuperglobalManager;

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
        $userName = SuperglobalManager::getSession('userLastName') . " " . SuperglobalManager::getSession('userFirstName');
        return mail($mailUserSend, "Mail from" . $userName, $messageUserSend);
    }
}
