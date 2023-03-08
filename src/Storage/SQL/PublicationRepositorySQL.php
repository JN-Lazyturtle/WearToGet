<?php

namespace TheFeed\Storage\SQL;

use DateTime;
use Framework\Storage\Repository;
use PDO;
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
        $statement = $this->pdo->prepare("SELECT idPublication, message, date, idUtilisateur, login, profilePictureName 
                                                FROM wtg_publications p 
                                                JOIN wtg_utilisateurs u on p.idAuteur = u.idUtilisateur
                                                ORDER BY date DESC");
        $statement->execute();

        $publis = [];

        foreach ($statement as $data) {
            $publi = new Publication();
            $publi->setIdPublication($data["idPublication"]);
            $publi->setMessage($data["message"]);
            $publi->setDate(new DateTime($data["date"]));
            $utilisateur = new Utilisateur();
            $utilisateur->setIdUtilisateur($data["idUtilisateur"]);
            $utilisateur->setLogin($data["login"]);
            $utilisateur->setProfilePictureName($data["profilePictureName"]);
            $publi->setUtilisateur($utilisateur);
            $publis[] = $publi;
        }

        return $publis;
    }

    public function getAllFrom($idUtilisateut) : array {
        $values = [
            "idAuteur" => $idUtilisateut,
        ];
        $statement = $this->pdo->prepare("SELECT idPublication, message, date, idUtilisateur, login, profilePictureName 
                                                FROM wtg_publications p 
                                                JOIN wtg_utilisateurs u on p.idAuteur = u.idUtilisateur
                                                WHERE idAuteur = :idAuteur                    
                                                ORDER BY date DESC");
        $statement->execute($values);

        $publis = [];

        foreach ($statement as $data) {
            $publi = new Publication();
            $publi->setIdPublication($data["idPublication"]);
            $publi->setMessage($data["message"]);
            $publi->setDate(new DateTime($data["date"]));
            $utilisateur = new Utilisateur();
            $utilisateur->setIdUtilisateur($data["idUtilisateur"]);
            $utilisateur->setLogin($data["login"]);
            $utilisateur->setProfilePictureName($data["profilePictureName"]);
            $publi->setUtilisateur($utilisateur);
            $publis[] = $publi;
        }

        return $publis;
    }

    public function create($publication) {
        $values = [
            "message" => $publication->getMessage(),
            "date" => $publication->getDate()->format('Y-m-d H:i:s'),
            "idAuteur" => $publication->getUtilisateur()->getIdUtilisateur()
        ];
        $statement = $this->pdo->prepare("INSERT INTO wtg_publications (message, date, idAuteur) VALUES(:message, :date, :idAuteur);");
        $statement->execute($values);
        return $this->pdo->lastInsertId();
    }

    public function get($id)
    {
        $values = [
            "idPublication" => $id,
        ];
        $statement = $this->pdo->prepare("SELECT idPublication, message, date, idUtilisateur, login, profilePictureName  
                                                FROM wtg_publications p
                                                JOIN wtg_utilisateurs u on p.idAuteur = u.idUtilisateur
                                                WHERE idPublication = :idPublication");
        $statement->execute($values);
        $data = $statement->fetch();
        if($data) {
            $publication = new Publication();
            $publication->setIdPublication($data["idPublication"]);
            $publication->setMessage($data["message"]);
            $publication->setDate(new DateTime($data["date"]));
            $utilisateur = new Utilisateur();
            $utilisateur->setIdUtilisateur($data["idUtilisateur"]);
            $utilisateur->setLogin($data["login"]);
            $utilisateur->setProfilePictureName($data["profilePictureName"]);
            $publication->setUtilisateur($utilisateur);
            return $publication;
        }
    }

    public function update($publication)
    {
        $values = [
            "idPublication" => $publication->getIdPublication(),
            "message" => $publication->getMessage(),
        ];
        $statement = $this->pdo->prepare("UPDATE wtg_publications SET message = :message WHERE idPublication = :idPublication;");
        $statement->execute($values);
    }

    public function remove($publication)
    {
        $values = [
            "idPublication" => $publication->getIdPublication(),
        ];
        $statement = $this->pdo->prepare("DELETE FROM wtg_publications WHERE idPublication = :idPublication");
        $statement->execute($values);
    }
}