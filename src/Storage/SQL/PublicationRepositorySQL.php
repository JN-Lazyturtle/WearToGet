<?php

namespace TheFeed\Storage\SQL;

use DateTime;
use Framework\Storage\Repository;
use PDO;
use TheFeed\Business\Entity\Item;
use TheFeed\Business\Entity\Publication;
use TheFeed\Business\Entity\Utilisateur;

class PublicationRepositorySQL implements Repository
{

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll() : array {
        $statement = $this->pdo->prepare("SELECT idPublication, date, message, pathPhoto, descriptionPhoto, idUtilisateur, login, profilePictureName
                                                FROM publications p 
                                                JOIN utilisateurs u on p.idAuteur = u.idUtilisateur
                                                ORDER BY date DESC");
        $statement->execute();

        $publis = [];

        foreach ($statement as $data) {
            $publi = $this->getPublicationFromData($data);
            $publis[] = $publi;
        }
        return $publis;
    }

    /**
     * @return Item[]
     */
    private function getItems($idPublication) : array{
        $values = [
            "idpub" => $idPublication,
        ];
        $statement = $this->pdo->prepare("SELECT idPiece, type, marque, lien
                                                FROM piece p 
                                                WHERE p.idPublication = :idpub");
        $statement->execute($values);

        $items = [];

        foreach ($statement as $data) {
            $item = new Item();
            $item->setIdItem($data["idPiece"]);
            $item->setCategory($data["type"]);
            $item->setBrand($data["marque"]);
            $item->setLink($data["lien"]);
            $items[] = $item;
        }

        return $items;
    }

    public function getAllFrom($idUtilisateur) : array {
        $values = [
            "idAuteur" => $idUtilisateur,
        ];
        $statement = $this->pdo->prepare("SELECT idPublication, date, message, pathPhoto, idUtilisateur, login, profilePictureName
                                                FROM publications p 
                                                JOIN utilisateurs u on p.idAuteur = u.idUtilisateur
                                                WHERE idAuteur = :idAuteur                    
                                                ORDER BY date DESC");
        $statement->execute($values);

        $publis = [];

        foreach ($statement as $data) {
            $publi = $this->getPublicationFromData($data);
            $publis[] = $publi;
        }

        return $publis;
    }

    /**
     * @param Publication $publication
     */
    public function create($publication) {
        $values = [
            "photo" => $publication->getPhotoPath(),
            "date" => $publication->getDate()->format('Y-m-d H:i:s'),
            "idAuteur" => $publication->getUtilisateur()->getIdUtilisateur(),
            "description" => $publication->getDescription()
        ];
        $statement = $this->pdo->prepare("INSERT INTO publications (message, pathPhoto, date, idAuteur) VALUES(:description, :photo, :date, :idAuteur);");
        $statement->execute($values);
        $publiID = $this->pdo->lastInsertId();
        foreach ($publication->getItems() as $item){
            $values = [
                "link" => $item->getLink(),
                "type" => $item->getCategory(),
                "brand" => $item->getBrand(),
                "idPublication" => $publiID
            ];
            $statement = $this->pdo->prepare("INSERT INTO piece (lien, type, marque, idPublication) VALUES(:link, :type, :brand, :idPublication);");
            $statement->execute($values);
        }
        return $publiID;
    }

    public function get($id)
    {
        $values = [
            "idPublication" => $id,
        ];
        $statement = $this->pdo->prepare("SELECT idPublication, date, message, pathPhoto, descriptionPhoto,  idUtilisateur, login, profilePictureName
                                                FROM publications p 
                                                JOIN utilisateurs u on p.idAuteur = u.idUtilisateur
                                                WHERE idPublication = :idPublication");
        $statement->execute($values);
        $data = $statement->fetch();

        if($data) {
            return $this->getPublicationFromData($data);
        }
    }

    /**
     * @param Publication $publication
     */
    public function update($publication)
    {
        $values = [
            "idPublication" => $publication->getIdPublication(),
            "photo" => $publication->getPhotoPath(),
            "description" => $publication->getDescription()
        ];
        $statement = $this->pdo->prepare("UPDATE publications SET pathPhoto = :photo, message = :description WHERE idPublication = :idPublication;");
        $statement->execute($values);
    }

    /**
     * @param Publication $publication
     */
    public function remove($publication)
    {
        foreach ($publication->getItems() as $item){
            $values = [
                "idItem" => $item->getIdItem(),
            ];
            $statement = $this->pdo->prepare("DELETE FROM piece WHERE idPiece = :idItem");
            $statement->execute($values);
        }

        $values = [
            "idPublication" => $publication->getIdPublication(),
        ];
        $statement = $this->pdo->prepare("DELETE FROM publications WHERE idPublication = :idPublication");
        $statement->execute($values);
    }


    public function getPublicationFromData($data): Publication
    {
        $publi = new Publication();
        $publi->setIdPublication($data["idPublication"]);
        $publi->setDescription($data["message"]);
        $publi->setPhotoPath($data["pathPhoto"]);
        $publi->setPhotoDescription($data["descriptionPhoto"]);
        $publi->setDate(new DateTime($data["date"]));
        $utilisateur = new Utilisateur();
        $utilisateur->setIdUtilisateur($data["idUtilisateur"]);
        $utilisateur->setLogin($data["login"]);
        $utilisateur->setProfilePictureName($data["profilePictureName"]);
        $publi->setUtilisateur($utilisateur);
        $publi->setItems($this->getItems($data["idPublication"]));
        return $publi;
    }

    public function getAllLikedFrom($idUtilisateur) : array {
        $values = [
            "idUtilisateur" => $idUtilisateur,
        ];

        $statement = $this->pdo->prepare(
            "SELECT idPublication, date, message, pathPhoto, descriptionPhoto, u.idUtilisateur, login, profilePictureName 
                    FROM publications p 
                    JOIN liked_utilisateur lu on p.idPublication = lu.idLiked 
                    JOIN utilisateurs u on p.idAuteur = u.idUtilisateur 
                    WHERE lu.idUtilisateur = :idUtilisateur"
        );
        $statement->execute($values);

        $likedPublis = [];

        foreach ($statement as $data) {
            $publi = $this->getPublicationFromData($data);
            $likedPublis[] = $publi;
        }

        return $likedPublis;
    }

}