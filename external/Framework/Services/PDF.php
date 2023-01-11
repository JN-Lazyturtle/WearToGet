<?php

namespace Framework\Services;


use Fpdf\Fpdf;

class PDF extends FPDF
{

    function Header(){
        $this->SetFont('Times', 'B', 16);
        $this->Cell(null, null, 'WearToGet', null, null, 'C', null, );
        $this->ln(20);
    }

    function Footer(){
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Times','I',10);
        // Page number
        $this->Cell(0,10,utf8_decode('WearToGet est une marque non déposé par Nyony, Julie et Yvan'),0,0,'C');
    }

    public function generateAndDisplayPDF(){
        $this->AddPage();
        $this->SetFont('Times', null, 16);
        $this->Cell(null, null, 'Hello World !', null, null, 'C', null, );

        $pdfName = 'hello-world.pdf';
        $this->Output($pdfName, 'I'); // 'I' pour afficher le PDF dans un navigateur
    }
}