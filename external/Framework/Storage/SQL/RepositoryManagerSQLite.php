<?php

namespace Framework\Storage\SQL;

use PDO;
use Framework\Storage\RepositoryManager;

class RepositoryManagerSQLite extends RepositoryManagerSQL
{
    public function __construct($dataBaseConfig)
    {
        parent::__construct();
        $fileName = $dataBaseConfig['file'];
        $this->pdo = new PDO("sqlite:$fileName");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}