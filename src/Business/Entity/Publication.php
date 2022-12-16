<?php

namespace TheFeed\Business\Entity;

use DateTime;
use JsonSerializable;

class Publication implements JsonSerializable
{
    /**
     * @var int
     */
    private $idPublication;

    /**
     * @var string
     */
    private $message;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var Utilisateur
     */
    private $utilisateur;

    /**
     * @param string $message
     * @param int $idAuteur
     * @return Publication
     */
    public static function create($message, $utilisateur) {
        $publication = new Publication();
        $publication->message = $message;
        $publication->date = new DateTime();
        $publication->utilisateur = $utilisateur;
        return $publication;
    }

    public function __construct() {

    }

    /**
     * @return int
     */
    public function getIdPublication(): int
    {
        return $this->idPublication;
    }

    /**
     * @param int $idPublication
     */
    public function setIdPublication(int $idPublication): void
    {
        $this->idPublication = $idPublication;
    }


    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return Utilisateur
     */
    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }

    /**
     * @param Utilisateur $utilisateur
     */
    public function setUtilisateur(Utilisateur $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }

    public function jsonSerialize() : array
    {
        return [
            "idPublication" => $this->idPublication,
            "message" => $this->message,
            "date" => $this->getDate()->format('d F Y'),
            "utilisateur" => [
                "idUtilisateur" => $this->utilisateur->getIdUtilisateur(),
                "login" => $this->utilisateur->getLogin(),
                "profilePictureName" => $this->utilisateur->getProfilePictureName()
            ]
        ];
    }
}