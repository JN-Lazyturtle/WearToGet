<?php

namespace Framework\Storage\SQL;

use Framework\Storage\RepositoryManager;

abstract class RepositoryManagerSQL extends RepositoryManager
{
    protected $pdo;

    public function __construct()
    {
        $this->repositories = [];
    }

    public function registerRepository($entityClass, $repositoryClass) {
        $repository = new $repositoryClass($this->pdo);
        $this->repositories[$entityClass] = $repository;
    }
}