<?php

namespace TheFeed\Application;

use Framework\Application\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use TheFeed\Business\Exception\ServiceException;

class UtilisateurController extends Controller
{

    public function getInscription() {
        return $this->render("Utilisateurs/inscription.html.twig");
    }

    public function getConnexion() {
        return $this->render("Utilisateurs/connexion.html.twig");
    }

    public function pagePerso($idUser) {
        $publicationsService = $this->container->get('publication_service');
        $userService = $this->container->get('utilisateur_service');
        try {
            $publications = $publicationsService->getPublicationsFrom($idUser);
            $utilisateur = $userService->getUtilisateur($idUser, false);
            return $this->render(
                "Utilisateurs/page_perso.html.twig",
                ["utilisateur" => $utilisateur, "publications" => $publications['owner']]);
        }
        catch (ServiceException $exception) {
            throw new ResourceNotFoundException();
        }
    }

    public function pageLiked($idUser) {
        $publicationsService = $this->container->get('publication_service');
        $userService = $this->container->get('utilisateur_service');
        try {
            $publications = $publicationsService->getPublicationsFrom($idUser);
            $utilisateur = $userService->getUtilisateur($idUser, false);
            return $this->render(
                "Utilisateurs/page_liked.html.twig",
                ["utilisateur" => $utilisateur, "liked" => $publications['liked']]);
        }
        catch (ServiceException $exception) {
            throw new ResourceNotFoundException();
        }
    }

    public function submitInscription(Request $request) {
        $login = $request->get("login");
        $passwordClair = $request->get("password");
        $adresseMail = $request->get("adresseMail");
        $profilePictureFile = $request->files->get("profilePicture");
        $userService = $this->container->get('utilisateur_service');
        try {
            $userService->createUtilisateur($login, $passwordClair, $adresseMail, $profilePictureFile);
            $this->addFlash("success","Inscription réussie!");
            return $this->redirectToRoute('feed');
        }
        catch (ServiceException $e) {
            $this->addFlash("error",$e->getMessage());
            return $this->render("Utilisateurs/inscription.html.twig", ["login" => $login, "adresseMail" => $adresseMail]);
        }
    }

    public function submitConnexion(Request $request) {
        $login = $request->get("login");
        $passwordClair = $request->get("password");
        $userService = $this->container->get('utilisateur_service');
        try {
            $userService->connexion($login, $passwordClair);
            return $this->redirectToRoute('feed');
        } catch (ServiceException $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('connexion');
        }
    }

    public function deconnexion() {
        $userService = $this->container->get('utilisateur_service');
        $userService->deconnexion();
        return $this->redirectToRoute('feed');
    }

    public function addLiked(Request $request) {
        $idLiked = $request->get("idLiked");
        $idUser = $request->get("idUser");
        $publicationsService = $this->container->get('publication_service');
        $publicationsService->createNewLike($idLiked, $idUser);
    }

}