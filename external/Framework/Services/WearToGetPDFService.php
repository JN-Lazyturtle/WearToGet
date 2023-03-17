<?php

namespace Framework\Services;

use Fpdf\Fpdf;
use TheFeed\Business\Entity\Item;
use TheFeed\Business\Entity\Publication;

class WearToGetPDFService
{

     public function affichePubicationPDF(Publication $publication){
         $pdf = new PDFService();

         $photo = 'http://localhost/WEB-1/WearToGet/web/assets/img/'.$publication->getPhotoPath();
         list($width, $height) = getimagesize($photo);
         if ($width > $pdf->GetX() / 2) {
             $width = $pdf->GetPageWidth() / 2;
         }
         $margin = 5;
         $pdf->Image($photo, $margin, $pdf->GetY(), $width);


         $pdf->SetFont('Arial', '', 12);
         foreach ($publication->getItems() as $item){
             $pdf->SetX($width+$margin*2);
             $txt = $item->getCategory()." ".$item->getBrand();
             $pdf->MultiCell($pdf->GetPageWidth()/2, 7, $txt);
             $pdf->Cell($pdf->GetPageWidth()/2, 7, "disponible ici", null, null, null, null, $item->getLink());
             $pdf->Ln(3);
         }

         $pdf->generateAndDisplayPDF();
     }


}