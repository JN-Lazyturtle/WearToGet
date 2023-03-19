<?php

namespace TheFeed\Business\Services;

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
             $pdf->Ln(3);
             $pdf->SetX($width+$margin*2+10);
             $pdf->Cell($pdf->GetPageWidth()/2, null, "disponible ici", null, null, null, null, $item->getLink());
             $pdf->Ln(8);
         }

         $pdf->generateAndDisplayPDF();
     }


}