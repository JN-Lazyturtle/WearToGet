<?php

namespace TheFeed\Business\Services;

use TheFeed\Business\Entity\Item;
use TheFeed\Business\Entity\Publication;
use TheFeed\Business\Exception\ServiceException;
use TheFeed\Storage\SQL\ItemRepositorySQL;
use TheFeed\Storage\SQL\PublicationRepositorySQL;

class ItemService
{
    private ItemRepositorySQL $itemRepositorySQL;

    private PublicationRepositorySQL $publicationRepositorySQL;

    /**
     * @param ItemRepositorySQL $itemRepositorySQL
     * @param PublicationRepositorySQL $publicationRepositorySQL
     */
    public function __construct($repositoryManager)
    {
        $this->itemRepositorySQL= $repositoryManager->getRepository(Item::class);
        $this->publicationRepositorySQL = $repositoryManager->getRepository(Publication::class);
    }

    public function getAllItems()
    {
        return $this->itemRepositorySQL->getAll();
    }

    public function getAllCategory()
    {
        return $this->itemRepositorySQL->getAllCategory();
    }

    public function getItem($idItem, $allowNull)
    {
        $piece = $this->itemRepositorySQL->get($idItem);
        if(!$allowNull && $piece == null) {
            throw new ServiceException("Lien inexistant!");
        }
        return $piece;
    }

    public function createNewItem($lien, $type, $marque, $idPublication)
    {
        if($lien == null || $lien == "") {
            throw new ServiceException("Le lien ne peut pas être vide!");
        }

        if($type == null || $type == "") {
            throw new ServiceException("Le type ne peut pas être vide!");
        }

        if($marque == null || $marque == "") {
            throw new ServiceException("La marque ne peut pas être vide!");
        }

        $publication = $this->publicationRepositorySQL->get($idPublication);
        $id = Item::create($lien, $type, $publication, $marque);
        return $this->itemRepositorySQL->get($id);

    }

}