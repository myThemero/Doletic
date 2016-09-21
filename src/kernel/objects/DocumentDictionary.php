<?php

require_once "objects/phpword/PHPWord.php";

class DocumentDictionary
{

    // -- consts
    const DOC_PROPALE = "propale";
    const DOC_CC = "cc";

    // -- attributes
    private $dict = array();

    // -- functions

    public function __construct($docType, $params)
    {
        switch ($docType) {
            case DocumentDictionary::DOC_PROPALE:
                $this->__make_propale_dict($params);
                break;
            case DocumentDictionary::DOC_CC:
                break;
        }
    }

    public function getDict()
    {
        return $this->dict;
    }

    private function __make_propale_dict($params)
    {
        $this->dict = [
            'NOMENTREPRISE' => $params[Services::PARAM_PROJECT]->GetFirmId()->GetName(),
            'TITREETUDE' => $params[Services::PARAM_PROJECT]->GetNumber(),
            'DESCRIPTIONETUDE' => $params[Services::PARAM_PROJECT]->GetName(),
            'CIVILITEUSER' => 'test', //Monsieur ou madame
            'NOMUSER' => 'test', //Nom du chadaff
            'CIVILITEINTERVENANT' => 'test', //Monsieur ou madame
            'NOMINTERVENANT' => 'test', //Nom de l'intervenant, à faire plusieurs fois si plusieurs intervenants
            'CIVILITEINTERVENANT1' => 'test', //Monsieur ou madame
            'NOMINTERVENANT1' => 'test', //Intervenant suivant
            'CIVILITECORRESPONDANTQUALITE' => 'test', //Monsieur ou madame
            'NOMCORRESPONDANTQUALITE' => 'test', // Nom du corres qualité
            'CIVILITECONTACT' => 'test', //Monsieur ou madame
            'NOMCONTACT' => 'test', //Nom du client
            'PRENOMCONTACT' => 'test', //Prenom du client
            'CIVPREZ' => 'test', //Monsieur ou madame
            'NOMPREZ' => 'test', //Nom du président
            'DUREEETUDE' => 'test', //Durée de l'étude
            'NBJOURSJEH' => 'test', //Nombre total de JEHs
            'TAUXTVA' => 'test', //taux de tva
            'DJOUR' => 'test', //date actuelle au format 05/05/1994
        ];
    }

}