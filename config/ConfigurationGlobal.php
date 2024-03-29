<?php

namespace Config;

use Framework\Services\ServerSessionManager;
use Symfony\Component\DependencyInjection\Reference;
use TheFeed\Application\API\PublicationControllerAPI;
use TheFeed\Application\API\UtilisateurControllerAPI;
use TheFeed\Application\PublicationController;
use TheFeed\Application\UtilisateurController;
use TheFeed\Business\Entity\Item;
use TheFeed\Business\Entity\Publication;
use TheFeed\Business\Entity\Utilisateur;
use TheFeed\Business\Services\ItemService;
use TheFeed\Business\Services\PublicationService;
use TheFeed\Business\Services\UtilisateurService;
use TheFeed\Business\Services\WearToGetPDFService;
use TheFeed\Listener\AppListener;
use TheFeed\Storage\SQL\ItemRepositorySQL;
use TheFeed\Storage\SQL\PublicationRepositorySQL;
use TheFeed\Storage\SQL\UtilisateurRepositorySQL;

class ConfigurationGlobal
{
    const debug = false;

    const appRoot = __DIR__ . '/../src';

    const parameters = [
        "profile_pictures_storage" => __DIR__ . '/web/assets/img',
        "secret_seed" => "qh7878qfsfsr_ttezo!"
    ];

    const views = "View";

    const repositories = [
        Publication::class => PublicationRepositorySQL::class,
        Utilisateur::class => UtilisateurRepositorySQL::class,
        Item::class => ItemRepositorySQL::class
    ];

    const userSessionManager = [
        "manager" => ServerSessionManager::class,
        "parameters" => [
            'environment' => '%environment%'
        ]
    ];

    const controllers = [
        "publication_controller" => PublicationController::class,
        "utilisateur_controller" => UtilisateurController::class,
        "publication_controller_api" => PublicationControllerAPI::class,
        "utilisateur_controller_api" => UtilisateurControllerAPI::class,
    ];

    const routes = [
        "feed" => [
            "path" => "/",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "publication_controller::feed",
            ]
        ],
        "submit_feedy" => [
            "path" => "/feedy",
            "methods" => ["POST"],
            "parameters" => [
                "_controller" => "publication_controller::submitPublication",
                "_logged" => true,
            ]
        ],
        "detail_publication" => [
            "path" => "/publication/detail/{idPublication}",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "publication_controller::detail",
                "idPublication" => null,
            ]
        ],
        "pdf_publication" => [
            "path" => "/publication/pdf/{idPublication}",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "publication_controller::generatePDF",
                "idPublication" => null,
            ]
        ],
        "connexion" => [
            "path" => "/connexion",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "utilisateur_controller::getConnexion",
                "_force_not_logged" => true,
            ]
        ],
        "deconnexion" => [
            "path" => "/deconnexion",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "utilisateur_controller::deconnexion",
                "_logged" => true,
            ]
        ],
        "inscription" => [
            "path" => "/inscription",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "utilisateur_controller::getInscription",
                "_force_not_logged" => true,
            ]
        ],
        "page_perso" => [
            "path" => "/utilisateurs/page/{idUser}",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "utilisateur_controller::pagePerso",
                "idUser" => null,
            ]
        ],
        "submit_inscription" => [
            "path" => "/inscription",
            "methods" => ["POST"],
            "parameters" => [
                "_controller" => "utilisateur_controller::submitInscription",
                "_force_not_logged" => true,
            ]
        ],
        "submit_connexion" => [
            "path" => "/connexion",
            "methods" => ["POST"],
            "parameters" => [
                "_controller" => "utilisateur_controller::submitConnexion",
                "_force_not_logged" => true,
            ]
        ],
        "submit_feedy_api" => [
            "path" => "/api/feedy",
            "methods" => ["POST"],
            "parameters" => [
                "_controller" => "publication_controller_api::submitPublication",
                "_logged" => true,
            ]
        ],
        "remove_feedy_api" => [
            "path" => "api/feedy/{idPublication}",
            "methods" => ["DELETE"],
            "parameters" => [
                "_controller" => "publication_controller_api::removeFeedy",
                "idPublication" => null,
                "_logged" => true,
            ]
        ],
        "remove_utilisateur_api" => [
            "path" => "api/utilisateur/{idUser}",
            "methods" => ["DELETE"],
            "parameters" => [
                "_controller" => "utilisateur_controller_api::removeUtilisateur",
                "idUser" => null,
                "_logged" => true,
            ]
        ],
        "mes_likes" => [
            "path" => "/liked/{idLiked}/user/{idUser}/page/{page}",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "utilisateur_controller::addLiked",
                "idLiked" => null,
                "idUser" => null,
                "page" => null,
            ]
        ],
        "unlike" => [
            "path" => "/unlike/{idLiked}/user/{idUser}/page/{page}",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "utilisateur_controller::removeLiked",
                "idLiked" => null,
                "idUser" => null,
                "page" => null,
            ]
        ],
        "like_perso" => [
            "path" => "/utilisateurs/like/{idUser}",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "utilisateur_controller::pageLiked",
                "idUser" => null,
            ]
        ],
    ];

    const listeners = [
      "app_listener"
    ];

    public static function services($container): void
    {
        $container->register('publication_service', PublicationService::class)
            ->setArguments([
                new Reference('repository_manager'),
                new Reference('utilisateur_service'),
                "%profile_pictures_storage%"
            ])
        ;
        $container->register('utilisateur_service', UtilisateurService::class)
            ->setArguments([
                new Reference('repository_manager'),
                new Reference('session_manager'),
                "%secret_seed%",
                "%profile_pictures_storage%"
            ])
        ;
        $container->register('item_service', ItemService::class)
            ->setArguments([
                new Reference('repository_manager'),
                new Reference('publication_service')
            ])
        ;
        $container->register('app_listener', AppListener::class)
            ->setArguments([
                new Reference('utilisateur_service'),
                new Reference('twig'),
                new Reference('url_generator')]);

        $container->register('pdf_service', WearToGetPDFService::class);
    }
}