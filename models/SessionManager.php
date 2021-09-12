<?php

namespace Models;

class SessionManager
{

    public static function CheckAndSet(): void
    {

        if (session_status() === PHP_SESSION_DISABLED) :
            die("sessions are disabled.");
        endif;
        if (session_status() === PHP_SESSION_NONE) :
            session_start();
        endif;
    }
}
