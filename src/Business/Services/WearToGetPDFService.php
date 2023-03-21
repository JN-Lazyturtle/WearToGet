<?php

namespace TheFeed\Business\Services;

use TheFeed\Business\Entity\Publication;

class WearToGetPDFService
{
    private $picturesRoot;

    /**
     * @param $picturesRoot
     */
    public function __construct($picturesRoot)
    {
        $this->picturesRoot = $picturesRoot;
    }

    public function affichePubicationPDF(Publication $publication){
        $pdf = $this->getPdf($publication);

        $pdf->generateAndDisplayPDF();

     }

     public function sendPublicationPDFToMail(Publication $publication){
         $pdf = $this->getPdf($publication);

         return $pdf->generatePDFToSendMail();
     }

    /**
     * @param Publication $publication
     * @return PDFService
     */
    public function getPdf(Publication $publication): PDFService
    {
        $pdf = new PDFService();
        $path = str_replace('/config', '', $this->picturesRoot);
        $photo = $path . '/' . $publication->getPhotoPath();
        list($width, $height) = getimagesize($photo);
        if ($width > $pdf->GetX() / 2) {
            $width = $pdf->GetPageWidth() / 2;
        }
        $margin = 5;
        $pdf->Image($photo, $margin, $pdf->GetY(), $width);

        $pdf->SetFont('Arial', '', 12);
        foreach ($publication->getItems() as $item) {
            $pdf->SetX($width + $margin * 2);
            $txt = $item->getCategory() . " " . $item->getBrand();
            $pdf->MultiCell($pdf->GetPageWidth() / 2, 7, $txt);
            $pdf->Ln(3);
            $pdf->SetX($width + $margin * 2 + 10);
            $pdf->Cell($pdf->GetPageWidth() / 2, null, "disponible ici", null, null, null, null, $item->getLink());
            $pdf->Ln(8);
        }
        return $pdf;
    }


}