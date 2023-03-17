<?php

namespace TheFeed\Business\Services;

use Framework\Services\ServerSessionManager;
use Framework\Services\UserSessionManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use TheFeed\Business\Entity\Utilisateur;
use TheFeed\Business\Exception\ServiceException;

class UtilisateurService
{
    private $repository;

    private UserSessionManager $sessionManager;

    private $secretSeed;

    private $profilePicturesRoot;

    public function __construct($repositoryManager, $sessionManager, $secretSeed, $profilePicturesRoot)
    {
        $this->repository = $repositoryManager->getRepository(Utilisateur::class);
        $this->sessionManager = $sessionManager;
        $this->secretSeed = $secretSeed;
        $this->profilePicturesRoot = $profilePicturesRoot;
    }

    /**
     * @return UserSessionManager
     */
    public function getSessionManager(): UserSessionManager
    {
        return $this->sessionManager;
    }

    public function getUtilisateur($idUtilisateur, $allowNull = true) {
        $utilisateur =  $this->repository->get($idUtilisateur);
        if(!$allowNull && $utilisateur == null) {
            throw new ServiceException("Utilisateur inexistant!");
        }
        return $utilisateur;
    }

    public function getUtilisateurByLogin($login, $allowNull = true) {
        $utilisateur =  $this->repository->getByLogin($login);
        if(!$allowNull && $utilisateur == null) {
            throw new ServiceException("Utilisateur inexistant!");
        }
        return $utilisateur;
    }

    public function createUtilisateur($login, $passwordClair, $adresseMail, $picUploadedFile) {
        if($login == null || $passwordClair == null || $adresseMail == null || $picUploadedFile == null) {
            throw new ServiceException("Données manquantes!");
        }
        $loginSize = strlen($login);
        if($loginSize < 4 || $loginSize > 20) {
            throw new ServiceException("Le login doit être compris entre 5 et 20 caractères!");
        }
        if(!preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,20}$#", $passwordClair)) {
            throw new ServiceException("Mot de passe invalide!");
        }
        if(!filter_var($adresseMail, FILTER_VALIDATE_EMAIL)) {
            throw new ServiceException("L'adresse mail est incorrecte!");
        }

        $utilisateur = $this->repository->getByLogin($login);
        if($utilisateur != null) {
            throw new ServiceException("Ce login est déjà pris!");
        }

        $utilisateur = $this->repository->getByAdresseMail($adresseMail);
        if($utilisateur != null) {
            throw new ServiceException("Un compte est déjà enregistré avec cette adresse mail!");
        }

        $fileExtension = $picUploadedFile->guessExtension();
        if(!in_array($fileExtension, ['png', 'jpg', 'jpeg'])) {
            throw new ServiceException("La photo de profil n'est pas au bon format!");
        }
        $pictureName = uniqid().'.'.$fileExtension;
        $path = $this->profilePicturesRoot;
        $path = str_replace('/config', '', $path);
        try{
            $picUploadedFile->move($path, $pictureName);
        }catch (\Exception $e){
            throw new ServiceException($e->getMessage());
        }

        $passwordChiffre = password_hash($this->secretSeed.$passwordClair, PASSWORD_BCRYPT);
        $utilisateur = Utilisateur::create($login, $passwordChiffre, $adresseMail, $pictureName);
        $this->repository->create($utilisateur);
    }

    public function removeUtilisateur($idUtilisateur) {
        $utilisateur = $this->getUtilisateur($idUtilisateur, false);
        if($utilisateur->getIdUtilisateur() != $idUtilisateur) {
            throw new ServiceException("L'utilisateur n'est pas propriétaire de ce compte!");
        }

        $profilePicturePath = $this->profilePicturesRoot."/".$utilisateur->getProfilePictureName();
        $this->repository->remove($utilisateur);
        unlink($profilePicturePath);
    }

    public function connexion($login, $passwordClair) {
        $utilisateur = $this->repository->getByLogin($login);
        if($utilisateur != null) {
            $password = $this->secretSeed.$passwordClair;
            if(password_verify($password, $utilisateur->getPassword())) {
                $this->sessionManager->set("id", $utilisateur->getIdUtilisateur());
                return;
            }
        }
        throw new ServiceException("Le login et/ou le mot de passe sont erronés");
    }

    public function getUserId() {
        return $this->sessionManager->get('id');
    }

    public function deconnexion() {
        $this->sessionManager->remove('id');
    }

    public function estConnecte() {
        return $this->sessionManager->has('id');
    }

    public function createNewLike(int $idLiked, int $idUser) {
        $this->repository->createLike($idLiked, $idUser);
    }

    public function removeLike(int $idLiked, int $idUser) {
        $this->repository->deleteLike($idLiked, $idUser);
    }
}