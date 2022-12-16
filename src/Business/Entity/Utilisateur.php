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

    public function __construct()
    {

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
}