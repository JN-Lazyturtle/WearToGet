<?php

namespace TheFeed\Business\Services;


use Fpdf\Fpdf;

class PDFService extends FPDF
{


    public function __construct()
    {
        parent::__construct();
        $this->AddPage();
    }

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
        $this->SetFont('Times', '', 16);
        $pdfName = 'WearToGet.pdf';
        $this->Output($pdfName, 'I'); // 'I' pour afficher le PDF dans un navigateur
    }

    public function generatePDFToSendMail(){
        $this->SetFont('Times', '', 16);
        $pdfName = 'WearToGet.pdf';
        return $this->Output($pdfName, 'S'); // 'S' pour récupérer le contenu du PDF sous forme de chaîne de caractères
    }
}