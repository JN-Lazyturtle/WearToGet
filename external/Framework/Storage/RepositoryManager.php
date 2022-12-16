<?php

namespace Framework\Storage;

abstract class RepositoryManager
{
    protected $repositories = [];

    public abstract function registerRepository($entityClass, $repositoryClass);

    public function registerRepositories($repositoriesData) {
        foreach ($repositoriesData as $entityClass => $repositoryClass) {
            $this->registerRepository($entityClass, $repositoryClass);
        }
    }

    public function getRepository($entityClass) {
        return $this->repositories[$entityClass];
    }
}