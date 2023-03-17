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

    private string $description;

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
    public static function create(string $description, string $photo, string $photoDescription, Utilisateur $utilisateur) {
        $publication = new Publication();
        $publication->date = new DateTime();
        $publication->utilisateur = $utilisateur;
        $publication->description = $description;
        $publication->photoPath = $photo;
        $publication->photoDescription = $photoDescription;
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

    public function getDateFormated() : string
    {
        return $this->date->format("m-d-Y");
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
    public function setPhotoPath(string $photoPath): self
    {
        $this->photoPath = $photoPath;

        return $this;
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
    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
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
    public function setPhotoDescription(string $photoDescription): self
    {
        $this->photoDescription = $photoDescription;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }



    public function jsonSerialize() : array
    {
        return [
            "idPublication" => $this->idPublication,
            "description" => $this->message,
            "date" => $this->getDate()->format('d F Y'),
            "pathPhoto" => $this->photoPath,
            "photoDescription" => $this->photoDescription,
            "utilisateur" => [
                "idUtilisateur" => $this->utilisateur->getIdUtilisateur(),
                "login" => $this->utilisateur->getLogin(),
                "profilePictureName" => $this->utilisateur->getProfilePictureName()
            ]
        ];
    }
}