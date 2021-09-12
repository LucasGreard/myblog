<?php

namespace Models;

use Error;
use Exception;

class Dbconnect
{

    public $connection;

    public function __construct()
    {
        try {
            $this->connection = new \PDO('mysql:host=localhost;dbname=projet_5;charset=utf8', 'root', '');
        } catch (Error | Exception $e) {
            throw new Exception("Unable to connect to database. Message is : " . $e->getMessage());
        }
    }

    public static function dbConnect()
    {
        try {
            return new \PDO('mysql:host=localhost;dbname=projet_5;charset=utf8', 'root', '');
        } catch (Error | Exception $e) {
            throw new Exception("Unable to connect to database. Message is : " . $e->getMessage());
        }
    }
    public static function dbCloseConnection($db)
    {
        try {
            return $db = null;
        } catch (Error | Exception $e) {
            throw new Exception("Unable to disconnect to database. Message is : " . $e->getMessage());
        }
    }
}
