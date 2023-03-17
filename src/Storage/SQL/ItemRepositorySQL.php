<?php

namespace TheFeed\Storage\SQL;

use Framework\Storage\Repository;
use PDO;
use TheFeed\Business\Entity\Item;

class ItemRepositorySQL implements Repository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $statement = $this->pdo->prepare("SELECT * FROM piece");

        $statement->execute();

        $pieces = [];

        foreach ($statement as $data) {
            $piece = new Item();
            $piece->setCategory($data['type']);
            $piece->setLink($data['lien']);
            $piece->setBrand($data['marque']);
            $piece->setPublication($data['publication']);
            $pieces[] = $piece;
        }

        return $pieces;
    }

    public function getAllCategory(): array
    {
        $statement = $this->pdo->prepare("SELECT DISTINCT type FROM piece");

        $statement->execute();

        $categories = [];

        foreach ($statement as $data) {
            $piece = new Item();
            $piece->setCategory($data['type']);
            $categories[] = $piece;
        }

        return $categories;
    }

    public function getAllByPublication($idPublication): array
    {
        $statement = $this->pdo->prepare("SELECT * 
                                            FROM piece 
                                            JOIN publications pub ON pub.idPublication = piece.publication
                                            WHERE idPublication = :idPublication");

        $values = [
            "idPublication" => $idPublication,
        ];

        $statement->execute($values);

        $pieces = [];

        foreach ($statement as $data) {
            $piece = new Item();
            $piece->setCategory($data['type']);
            $piece->setLink($data['lien']);
            $piece->setBrand($data['marque']);
            $piece->setPublication($data['publication']);
            $pieces[] = $piece;
        }

        return $pieces;
    }

    public function get($id)
    {
        $statement = $this->pdo->prepare("SELECT * 
                                            FROM piece 
                                            WHERE idPiece = :id");

        $values = [
            "id" => $id,
        ];

        $statement->execute($values);
        $data = $statement->fetch();

        if($data){
            $piece = new Item();
            $piece->setCategory($data['type']);
            $piece->setLink($data['lien']);
            $piece->setBrand($data['marque']);
            $piece->setPublication($data['publication']);
            return $piece;
        }
    }

    public function create($piece)
    {
        $statement = $this->pdo->prepare("INSERT INTO piece (lien, type, marque, idPublication) VALUES(:lien, :type, :marque, :idPublication);");

        $values = [
            "lien" => $piece->getLink(),
            "type" => $piece->getCategory(),
            "marque" => $piece->getBrand(),
            "idPublication" => $piece->getPublication()->getIdPublication()
        ];

        $statement->execute($values);

        return $this->pdo->lastInsertId();
    }

    public function update($piece)
    {
        $statement = $this->pdo->prepare("UPDATE piece SET lien = :lien WHERE idPiece = :idPiece;");

        $values = [
            "idPiece" => $piece->getIdPiece(),
            "lien" => $piece->getLien(),
        ];

        $statement->execute($values);
    }

    public function remove($piece)
    {
        $statement = $this->pdo->prepare("DELETE FROM piece WHERE idPiece = :idPiece;");

        $values = [
            "idPiece" => $piece->getIdPiece()
        ];

        $statement->execute($values);
    }
}