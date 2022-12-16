<?php

namespace Framework\Storage\SQL;

use PDO;
use Framework\Storage\RepositoryManager;

class RepositoryManagerMySQL extends RepositoryManagerSQL
{
    public function __construct($dataBaseConfig)
    {
        parent::__construct();
        $hostname = $dataBaseConfig['hostname'];
        $databaseName = $dataBaseConfig['database'];
        $login = $dataBaseConfig['login'];
        $password = $dataBaseConfig['password'];
        $this->pdo = new PDO("mysql:host=$hostname;dbname=$databaseName",$login,$password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}