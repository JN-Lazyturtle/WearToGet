<?php

namespace TheFeed\Storage\XML;

use DateTime;
use DOMDocument;
use Framework\Storage\Repository;
use TheFeed\Business\Entity\Publication;

class PublicationRepositoryXML implements Repository
{

    private DOMDocument $xmlDoc;

    public function __construct($xmlDoc)
    {
        $this->xmlDoc = $xmlDoc;

    }

    public function getAll()
    {
        $publications = [];
        $database = $this->xmlDoc->firstChild;
        $publicationNodes = $database->childNodes[0];
        foreach($publicationNodes->childNodes as $publicationNode) {
            $publication = new Publication();
            $id = intval($publicationNode->getAttribute("idPublication"));
            $message = $publicationNode->getAttribute("message");
            $date = new DateTime($publicationNode->getAttribute("date"));
            $loginAuteur = $publicationNode->getAttribute("loginAuteur");
            $publication->setIdPublication($id);
            $publication->setMessage($message);
            $publication->setDate($date);
            $publication->setLoginAuteur($loginAuteur);
            $publications[] = $publication;
        }
        return array_reverse($publications);
    }

    public function get($id)
    {
        // TODO: Implement get() method.
    }

    public function create($entity)
    {
        $publicationNode = $this->xmlDoc->createElement("publication");
        $publicationNode->setAttribute("idPublication", $this->getGreatestId());
        $publicationNode->setAttribute("message", $entity->getMessage());
        $publicationNode->setAttribute("date", $entity->getDate()->format('Y-m-d H:i:s'));
        $publicationNode->setAttribute("loginAuteur", $entity->getLoginAuteur());
        $database = $this->xmlDoc->firstChild;
        $publicationNodes = $database->childNodes[0];
        $publicationNodes->appendChild($publicationNode);
        $this->xmlDoc->save($this->xmlDoc->documentURI);
    }

    private function getGreatestId() {
        $database = $this->xmlDoc->firstChild;
        $publicationNodes = $database->childNodes[0];
        $idMax = 0;
        foreach($publicationNodes->childNodes as $publicationNode) {
            $id = intval($publicationNode->getAttribute("idPublication"));
            if($id > $idMax) {
                $idMax = $id;
            }
        }
        $idMax++;
        return $idMax;
    }

    public function update($entity)
    {
        // TODO: Implement update() method.
    }

    public function remove($entity)
    {
        // TODO: Implement remove() method.
    }
}