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
     * @var DateTime
     */
    private $date;

    /**
     * @var Utilisateur
     */
    private Utilisateur $utilisateur;

    private string $photoPath;

    private string $photoDescription;

    /**
     * @var Item[]
     */
    private array $items;


    /**
     * @param Item[] $items
     */
    public static function create(string $photo, string $photoDescription, array $items, Utilisateur $utilisateur) {
        $publication = new Publication();
        $publication->date = new DateTime();
        $publication->utilisateur = $utilisateur;
        $publication->photoPath = $photo;
        $publication->photoDescription = $photoDescription;
        $publication->items = $items;
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

    /**
     * @return string
     */
    public function getPhotoPath(): string
    {
        return $this->photoPath;
    }

    /**
     * @param string $photoPath
     */
    public function setPhotoPath(string $photoPath): void
    {
        $this->photoPath = $photoPath;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param Item[] $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @return string
     */
    public function getPhotoDescription(): string
    {
        return $this->photoDescription;
    }

    /**
     * @param string $photoDescription
     */
    public function setPhotoDescription(string $photoDescription): void
    {
        $this->photoDescription = $photoDescription;
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