<?php

namespace TheFeed\Business\Entity;

class Utilisateur
{

    private $idUtilisateur;
    private $login;
    //Chiffré
    private $password;
    private $adresseMail;
    private $profilePictureName;
    private array $postsList;
    private array $likedPosts;

    public function __construct()
    {
        $this->postsList = [];
        $this->likedPosts = [];
    }

    public static function create($login, $passwordChiffre, $addresseMail, $profilePictureName) : Utilisateur {
        $utilisateur = new Utilisateur();
        $utilisateur->setLogin($login);
        $utilisateur->setPassword($passwordChiffre);
        $utilisateur->setAdresseMail($addresseMail);
        $utilisateur->setProfilePictureName($profilePictureName);
        return $utilisateur;
    }

    public function getIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getAdresseMail()
    {
        return $this->adresseMail;
    }

    public function getProfilePictureName()
    {
        return $this->profilePictureName;
    }

    public function setIdUtilisateur($idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    public function setLogin($login): void
    {
        $this->login = $login;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function setAdresseMail($adresseMail): void
    {
        $this->adresseMail = $adresseMail;
    }

    public function setProfilePictureName($profilePictureName): void
    {
        $this->profilePictureName = $profilePictureName;
    }

    /**
     * @return array
     */
    public function getPostsList(): array
    {
        return $this->postsList;
    }

    public function addPostsList(Publication $publication): self
    {
        if (!in_array($publication, $this->postsList)) {
            $this->postsList[] = [$publication];
            $publication->setUtilisateur($this);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getLikedPosts(): array
    {
        return $this->likedPosts;
    }

    public function addLikedPosts(Publication $publication): self
    {
        if (!in_array($publication, $this->likedPosts)) {
            $this->likedPosts[] = [$publication];
        }
        return $this;
    }
}