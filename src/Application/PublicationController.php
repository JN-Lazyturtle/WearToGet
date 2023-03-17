<?php

namespace TheFeed\Application;

use Framework\Application\Controller;
use Symfony\Component\HttpFoundation\Request;
use TheFeed\Business\Exception\ServiceException;

class PublicationController extends Controller
{

    public function feed() {
        $service = $this->container->get('publication_service');
        $publications = $service->getAllPublications();

        $serviceUser = $this->container->get('utilisateur_service');
        $idUtilisateurLogged = $serviceUser->getSessionManager()->get('id');
        $publicationsLiked = $serviceUser->getUtilisateur($idUtilisateurLogged)->getLikedPublications();

        if ($idUtilisateurLogged) {
            foreach ($publications as $publication) {
                if (in_array($publication, $publicationsLiked)) {
                    $publication->setLiked(true);
                }
            }
        }


        return $this->render('Publications/home.html.twig', ["publications" => $publications]);
    }

    public function detail($idPublication) {
        $service = $this->container->get('publication_service');
        $publication = $service->getPublication($idPublication);
        if ($publication != null) {
            $uniqueBrands = array();
            $uniqueBrandsNormalized = array();
            foreach ($publication->getItems() as $item){
                if ( ! in_array(strtolower($item->getBrand()), $uniqueBrandsNormalized)) {
                    $uniqueBrands[] = $item->getBrand();
                    $uniqueBrandsNormalized[] = strtolower($item->getBrand());
                }
            }
            return $this->render('Publications/detail.html.twig', ["post" => $publication, "uniqueBrands" => $uniqueBrands]);
        }
        return $this->feed();
    }


    public function submitFeedy(Request $request) {
        $userService = $this->container->get('utilisateur_service');
            $userId = $userService->getUserId();
            $message = $request->get('message');
            $service = $this->container->get('publication_service');
            try {
                $service->createNewPublication($userId, $message);
            } catch (ServiceException $e) {
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute('feed');
    }
}