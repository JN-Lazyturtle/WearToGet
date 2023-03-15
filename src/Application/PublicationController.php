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

    public function submitPublication(Request $request) {
        $userService = $this->container->get('utilisateur_service');
        $service = $this->container->get('publication_service');
        $userId = $userService->getUserId();
        $description = $request->get('outfit-description');
        $photoPath = $request->get('outfit-picture');
        $photoDescription = $request->get('outfit-description');
        $items = $request->get('items');
        $items_array = explode(',', $items);
            try {
                $service->createNewPublication($userId, $description, $photoPath, $photoDescription, $items_array);
            } catch (ServiceException $e) {
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute('feed');
    }
}