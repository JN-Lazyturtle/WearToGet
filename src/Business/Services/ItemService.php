<?php

namespace TheFeed\Business\Services;

use TheFeed\Business\Entity\Item;
use TheFeed\Business\Exception\ServiceException;
use TheFeed\Storage\SQL\PieceRepositorySQL;
use TheFeed\Storage\SQL\PublicationRepositorySQL;

class ItemService
{
    private PieceRepositorySQL $pieceRepositorySQL;

    private PublicationRepositorySQL $publicationRepositorySQL;

    /**
     * @param PieceRepositorySQL $pieceRepositorySQL
     * @param PublicationRepositorySQL $publicationRepositorySQL
     */
    public function __construct(PieceRepositorySQL $pieceRepositorySQL, PublicationRepositorySQL $publicationRepositorySQL)
    {
        $this->pieceRepositorySQL = $pieceRepositorySQL;
        $this->publicationRepositorySQL = $publicationRepositorySQL;
    }

    public function getAllPieces()
    {
        return $this->pieceRepositorySQL->getAll();
    }

    public function getPiece($idItem, $allowNull)
    {
        $piece = $this->pieceRepositorySQL->get($idItem);
        if(!$allowNull && $piece == null) {
            throw new ServiceException("Lien inexistant!");
        }
        return $piece;
    }

    public function createNewPiece($lien, $type, $marque, $idPublication)
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
        return $this->pieceRepositorySQL->get($id);

    }

}