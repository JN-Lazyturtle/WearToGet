<?php

namespace Config\Environment\Production;

use Config\ConfigurationGlobal;
use Framework\Storage\SQL\RepositoryManagerMySQL;

class ConfigurationProduction extends ConfigurationGlobal
{
    const debug = false;

    const environment = 'production';


    const repositoryManager = [
        "manager" => RepositoryManagerMySQL::class,
        "dataSourceParameters" => [
            'hostname' => 'webinfo.iutmontp.univ-montp2.fr',
            'database' => 'rakotondramonjan',
            'login' => 'rakotondramonjan',
            'password' => 'BDDnyony23'
        ]
    ];
}