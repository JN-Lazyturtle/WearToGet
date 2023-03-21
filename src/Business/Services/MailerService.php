<?php

namespace TheFeed\Business\Services;

use Exception;

class MailerService
{

    public function sendPdfToEmail($pdfContent, $mail)
    {

        // Adresse e-mail du destinataire
        $to = $mail;

        // Sujet de l'e-mail
        $subject = 'Look préféré en PDF';

        // Message de l'e-mail
        $message = 'Voici un fichier votre look préféré en version PDF en pièce jointe.';

        // Fichier PDF en pièce jointe
        $pdfFileName = 'wearToGetLook.pdf';
        $pdfContentType = 'application/pdf';

        // Séparer les données du fichier PDF
        $boundary = md5(rand());
        $attachment = chunk_split(base64_encode($pdfContent));

        // En-têtes de l'e-mail
        $headers = "From: wear-to-get@yopmail.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

        // Corps de l'e-mail
        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= "$message\r\n";

        // Ajouter le fichier PDF en pièce jointe
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: $pdfContentType\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$pdfFileName\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= "$attachment\r\n";
        $body .= "--$boundary--";

        // Paramètres SMTP
        ini_set('SMTP', 'smtp-int.umontpellier.fr');
        ini_set('smtp_port', 587);


        // Envoyer l'e-mail
        try {
            mail($to, $subject, $body, $headers);
        }catch (Exception $e){
            echo 'Erreur lors de l\'envoi de l\'e-mail : ' . $e->getMessage();
        }

    }
}