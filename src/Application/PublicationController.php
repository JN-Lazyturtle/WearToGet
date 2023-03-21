<?php

namespace TheFeed\Application;

use Framework\Application\Controller;
use Framework\Services\UserSessionManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Email;
use TheFeed\Business\Exception\ServiceException;
use TheFeed\Business\Services\WearToGetPDFService;

class PublicationController extends Controller
{
    private UserSessionManager $sessionManager;

    public function feed() {
        $service = $this->container->get('publication_service');
        $publications = $service->getAllPublications();

        $serviceUser = $this->container->get('utilisateur_service');
        $idUtilisateurLogged = $serviceUser->getSessionManager()->get('id');

        if ($idUtilisateurLogged) {
            $publicationsLiked = $serviceUser->getUtilisateur($idUtilisateurLogged)->getLikedPublications();
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

    public function generatePDF($idPublication) {
        $service = $this->container->get('publication_service');
        $publication = $service->getPublication($idPublication);
        if (! $publication) {
            return $this->feed();
        }
        $pdf = $this->container->get('pdf_service');
        $pdf->affichePubicationPDF($publication);
    }

    public function sendPDFToMail(Request $request, $idPublication){
        $mailInput = $request->get('mail');
        $service = $this->container->get('publication_service');
        $publication = $service->getPublication($idPublication);

        if (! $publication) {
            return $this->feed();
        }
        $pdf = $this->container->get('pdf_service');
        $pdf = $pdf->sendPublicationPDFToMail($publication);
        $mail = $this->container->get('mailer_service');
        $mail->sendPdfToEmail($pdf, $mailInput);
        $this->addFlash("success", "Mail Envoyé!");
        return $this->redirectToRoute('feed');
    }

    public function submitPublication(Request $request) {
        $userService = $this->container->get('utilisateur_service');
        $service = $this->container->get('publication_service');
        $userId = $userService->getUserId();
        $description = $request->get('outfit-description');
        $profilePictureFile = $request->files->get("outfitPicture");
        $photoDescription = $request->get('outfit-description');

        $item = $request->get('items');
        $items_mark = $request->get('marque');
        $items_category = $request->get('category');
        $itemString = preg_replace('/\s+/', ' ', $item);
        $items_array = explode(' ', $itemString);
        $items = [
            'mark' => $items_mark,
            'category' => $items_category,
            'link' => $items_array
        ];

            try {
                $service->createNewPublication($userId, $description, $profilePictureFile, $photoDescription, $items);
            } catch (ServiceException $e) {
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute('feed');
    }
}