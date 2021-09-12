<?php

namespace Models;

use Exception;

class HomeManager extends Dbconnect
{
    private $dbConnect;

    public function __construct()
    {
        $this->dbConnect = $this->dbConnect();
    }
    /**
     * @throws exception
     */
    public function listHome()
    {
        return $req = $this->dbConnect->query('SELECT * FROM superadmin');
    }
}
