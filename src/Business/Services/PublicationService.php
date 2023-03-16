<?php

namespace TheFeed\Business\Services;

use TheFeed\Business\Entity\Item;
use TheFeed\Business\Entity\Publication;
use TheFeed\Business\Exception\ServiceException;

class PublicationService
{

    private $repository;

    private $itemRepository;

    private UtilisateurService $serviceUtilisateur;

    public function __construct($repositoryManager, UtilisateurService $serviceUtilisateur)
    {
        $this->repository = $repositoryManager->getRepository(Publication::class);
        $this->itemRepository = $repositoryManager->getRepository(Item::class);
        $this->serviceUtilisateur = $serviceUtilisateur;
    }

    public function getAllPublications()
    {
        return $this->repository->getAll();
    }

    public function getPublication($idPublication, $allowNull = true)
    {
        $publication = $this->repository->get($idPublication);
        if (!$allowNull && $publication == null) {
            throw new ServiceException("Publication inexistante");
        }
        return $publication;
    }

    /**
     * @param Item[] $items
     */
    public function createNewPublication($idUtilisateur, string $description, string $photoPath, string $photoDescription, array $items)
    {
        $utilisateur = $this->serviceUtilisateur->getUtilisateur($idUtilisateur, false);
        $publication = Publication::create($description, $photoPath, $photoDescription, $utilisateur);
        $id = $this->repository->create($publication);
        $publication->setIdPublication($id);

        foreach ($items['link'] as $link){
            $item = Item::create($link, $items['category'], $id, $items['mark']);
            $item->setPublication($publication);
            $this->itemRepository->create($item);
        }

        return $this->repository->get($id);
    }

    public function getPublicationsFrom($refUtilisateur)
    {
        $utilisateur = $this->serviceUtilisateur->getUtilisateur($refUtilisateur);

        if ($utilisateur == null) {
            $utilisateur = $this->serviceUtilisateur->getUtilisateurByLogin($refUtilisateur, false);
        }

        return $this->repository->getAllFrom($utilisateur->getIdUtilisateur());
    }

    public function removePublication($idUtilisateur, $idPublication)
    {
        $utilisateur = $this->serviceUtilisateur->getUtilisateur($idUtilisateur, false);
        $publication = $this->getPublication($idPublication, false);
        if ($utilisateur->getIdUtilisateur() != $publication->getUtilisateur()->getIdUtilisateur()) {
            throw new ServiceException("L'utilisateur n'est pas l'auteur de cette publication!");
        }
        $this->repository->remove($publication);
    }

}