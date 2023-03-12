<?php

namespace TheFeed\Storage\SQL;

use Framework\Storage\Repository;
use PDO;
use TheFeed\Business\Entity\Item;

class PieceRepositorySQL implements Repository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $statement = $this->pdo->prepare("SELECT * FROM pieces");

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

    public function getAllByPublication($idPublication): array
    {
        $statement = $this->pdo->prepare("SELECT * 
                                            FROM pieces 
                                            JOIN publications pub ON pub.idPublication = pieces.publication
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
                                            FROM pieces 
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
        $statement = $this->pdo->prepare("INSERT INTO pieces (lien, type, marque, idPublication) VALUES(:lien, :type, :marque, :idPublication);");

        $values = [
            "lien" => $piece->getLien(),
            "type" => $piece->getType(),
            "marque" => $piece->getMarque(),
            "idPublication" => $piece->getPublication()->getIdPublication()
        ];

        $statement->execute($values);

        return $this->pdo->lastInsertId();
    }

    public function update($piece)
    {
        $statement = $this->pdo->prepare("UPDATE pieces SET lien = :lien WHERE idPiece = :idPiece;");

        $values = [
            "idPiece" => $piece->getIdPiece(),
            "lien" => $piece->getLien(),
        ];

        $statement->execute($values);
    }

    public function remove($piece)
    {
        $statement = $this->pdo->prepare("DELETE FROM pieces WHERE idPiece = :idPiece;");

        $values = [
            "idPiece" => $piece->getIdPiece()
        ];

        $statement->execute($values);
    }
}