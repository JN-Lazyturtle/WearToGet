<?php

namespace TheFeed\Business\Entity;

class Piece implements \JsonSerializable
{
    /**
     * @var int
     */
    private $idPiece;

    /**
     * @var string
     */
    private $lien;

    /**
     * @var string
     */
    private $type;

    /**
     * @var Publication
     */
    private $publication;

    /**
     * @var string
     */
    private $marque;

    public static function create($lien, $type, $publication, $marque)
    {
        $piece = new Piece();
        $piece->publication = $publication->getId();
        $piece->lien = $lien;
        $piece->type = $type;
        $piece->marque = $marque;
        return $piece;
    }

    public function __construct() {

    }

    /**
     * @return int
     */
    public function getIdPiece(): int
    {
        return $this->idPiece;
    }

    /**
     * @param int $idPiece
     */
    public function setIdPiece(int $idPiece): void
    {
        $this->idPiece = $idPiece;
    }

    /**
     * @return string
     */
    public function getLien(): string
    {
        return $this->lien;
    }

    /**
     * @param string $lien
     */
    public function setLien(string $lien): void
    {
        $this->lien = $lien;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return Publication
     */
    public function getPublication(): Publication
    {
        return $this->publication;
    }

    /**
     * @param Publication $publication
     */
    public function setPublication(Publication $publication): void
    {
        $this->publication = $publication;
    }

    /**
     * @return string
     */
    public function getMarque(): string
    {
        return $this->marque;
    }

    /**
     * @param string $marque
     */
    public function setMarque(string $marque): void
    {
        $this->marque = $marque;
    }


    public function jsonSerialize(): array
    {
        return [
            'idPiece' => $this->idPiece,
            'lien' => $this->lien,
            'type' => $this->type,
            'marque' => $this->marque,
            'publication' => [
                "idPublication" => $this->publication->getIdPublication(),
                "message" => $this->publication->getMessage(),
            ]
        ];
    }
}