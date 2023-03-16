<?php

namespace TheFeed\Business\Entity;

class Item implements \JsonSerializable
{
    /**
     * @var int
     */
    private $idItem;

    /**
     * @var string
     */
    private $link;

    /**
     * @var string
     */
    private $category;

    /**
     * @var Publication
     */
    private $publication;

    /**
     * @var string
     */
    private $brand;

    public static function create($lien, $type, $idPublication, $marque)
    {
        $piece = new Item();
        $piece->publication = $idPublication;
        $piece->link = $lien;
        $piece->category = $type;
        $piece->brand = $marque;
        return $piece;
    }

    public function __construct() {

    }

    /**
     * @return int
     */
    public function getIdItem(): int
    {
        return $this->idItem;
    }

    /**
     * @param int $idItem
     */
    public function setIdItem(int $idItem): void
    {
        $this->idItem = $idItem;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
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
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     */
    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }


    public function jsonSerialize(): array
    {
        return [
            'idPiece' => $this->idItem,
            'lien' => $this->link,
            'type' => $this->category,
            'marque' => $this->brand,
            'publication' => [
                "idPublication" => $this->publication->getIdPublication(),
                "message" => $this->publication->getMessage(),
            ]
        ];
    }

    public function __toString(): string
    {
        return "[
            'idPiece' => $this->idItem,
            'lien' => $this->link,
            'type' => $this->category,
            'marque' => $this->brand,
            'publication' 
            ]";
    }


}