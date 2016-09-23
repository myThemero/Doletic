<?php

require_once "objects/phpword/PHPWord.php";

class DocumentDictionary
{

    // -- consts
    const DOC_PROPALE = "propale";
    const DOC_CC = "cc";
    const DOC_RM = "rm";
    const DOC_PVL = "pvl";
    const DOC_PVFR = "pvfr";
    const DOC_PVF = "pvf";
    const DOC_AVENANTETUDIANT = "avenantetudiant";
    const DOC_AVENANTENTREPRISE = "avenantentreprise";
    const DOC_LETTREENVOIAVENANT = "lettreenvoiavenant";
    const DOC_LETTREENVOIPROPALE = "lettreenvoipropale";
    const DOC_LETTREENVOIDEVIS = "lettreenvoidevis";
    const DOC_DEVIS = "devis";
    const DOC_CONFIDENTIALITEETUDIANT = "confidentialiteetudiant";
    const DOC_CONFIDENTIALITEENTREPRISE = "confidentialiteentreprise";
    const DOC_DEMANDEFACTURE = "demandefacture";
    const DOC_DEMANDEBV = "demandebv";


    // -- attributes
    private $dict = array();

    // -- functions

    public function __construct($docType, $params)
    {
        switch ($docType) {
            case DocumentDictionary::DOC_PROPALE:
                $this->__make_propositioncommerciale_dict($params);
                break;
            case DocumentDictionary::DOC_CC:
                $this->__make_conventionclient_dict($params);
                break;
            case DocumentDictionary::DOC_RM:
                $this->__make_recapitulatifmission_dict($params);
                break;
            case DocumentDictionary::DOC_PVL:
                $this->__make_procesverballivraison_dict($params);
                break;
            case DocumentDictionary::DOC_PVFR:
                $this->__make_procesverbalfinrecette_dict($params);
                break;
            case DocumentDictionary::DOC_PVF:
                $this->__make_procesverbalfin_dict($params);
                break;
            case DocumentDictionary::DOC_AVENANTETUDIANT:
                $this->__make_avenantetudiant_dict($params);
                break;
            case DocumentDictionary::DOC_AVENANTENTREPRISE:
                $this->__make_avenantentreprise_dict($params);
                break;
            case DocumentDictionary::DOC_LETTREENVOIAVENANT:
                $this->__make_lettreenvoiavenant_dict($params);
                break;
            case DocumentDictionary::DOC_LETTREENVOIPROPALE:
                $this->__make_lettreenvoipropale_dict($params);
                break;
            case DocumentDictionary::DOC_LETTREENVOIDEVIS:
                $this->__make_lettreenvoidevis_dict($params);
                break;
            case DocumentDictionary::DOC_DEVIS:
                $this->__make_devis_dict($params);
                break;
            case DocumentDictionary::DOC_CONFIDENTIALITEETUDIANT:
                $this->__make_confidentialiteetudiant_dict($params);
                break;
            case DocumentDictionary::DOC_CONFIDENTIALITEENTREPRISE:
                $this->__make_confidentialiteentreprise_dict($params);
                break;
            case DocumentDictionary::DOC_DEMANDEFACTURE:
                $this->__make_demandefacture_dict($params);
                break;
            case DocumentDictionary::DOC_DEMANDEBV:
                $this->__make_demandebv_dict($params);
                break;
        }
    }

    public function getDict()
    {
        return $this->dict;
    }

    private function __make_propositioncommerciale_dict($params)
    {
        $chadaff = $params[Services::PARAM_CHADAFF];
        $project = $params[Services::PARAM_PROJECT];
        $int = $params[Services::PARAM_INT];
        $contact = $params[Services::PARAM_CONTACT];

        $this->dict = [
            'NOMENTREPRISE' => $project->GetFirmId()->GetName(),
            'TITREETUDE' => $project->GetNumber(),
            'DESCRIPTIONETUDE' => $project->GetName(),
            'CIVILITEUSER' => $chadaff->GetGender(), //Monsieur ou madame
            'NOMUSER' => $chadaff->GetFirstName() . ' ' . $chadaff->GetLastName(), //Nom du chadaff
            'CIVILITEINTERVENANT' => $int->GetGender(), //Monsieur ou madame
            'NOMINTERVENANT' => $int->GetFirstName() . ' ' . $int->GetLastName(), //Nom de l'intervenant, à faire plusieurs fois si plusieurs intervenants
            'CIVILITEINTERVENANT1' => $int->GetGender(), //Monsieur ou madame
            'NOMINTERVENANT1' => $int->GetFirstName() . ' ' . $int->GetLastName(), //Nom de l'intervenant, à faire plusieurs fois si plusieurs intervenants
            'CIVILITECORRESPONDANTQUALITE' => $project->GetAuditorId()->GetGender(), //Monsieur ou madame
            'NOMCORRESPONDANTQUALITE' => $project->GetAuditorId()->GetFirstname() . ' ' . $project->GetAuditorId()->GetLastName(), // Nom du corres qualité
            'CIVILITECONTACT' => $contact->GetGender(), //Monsieur ou madame
            'NOMCONTACT' => $contact->GetLastName(), //Nom du client
            'PRENOMCONTACT' => $contact->GetFirstName(), //Prenom du client
            'FCTCONTACT' => 'test', //Fonction du contact
            'CIVPRESIDENT' => 'test', //Monsieur ou madame
            'NOMPRESIDENT' => 'test', //Nom du président
            'DUREEETUDE' => 'test', //Durée de l'étude
            'NBJOURSJEH' => 'test', //Nombre total de JEHs
            'TAUXTVA' => 'test', //taux de tva
            'DJOUR' => date('d/m/Y'), //date actuelle au format 05/05/1994
        ];
    }

    private function __make_conventionclient_dict($params)
    {
        $this->dict = [
            'NOMENTREPRISE' => $params[Services::PARAM_PROJECT]->GetFirmId()->GetName(),
            'ADRESSEENTREPRISE' => 'test', //adresse de l'etreprise
            'CPENTREPRISE' => 'test', //code postale de l'entreprise
            'VILLEENTREPRISE' => 'test', //ville de l'entreprise
            'SIRETENTREPRISE' => 'test', //siret de l'entreprise
            'TITREETUDE' => $params[Services::PARAM_PROJECT]->GetNumber(),
            'DESCRIPTIONETUDE' => $params[Services::PARAM_PROJECT]->GetName(),
            'CIVILITECORRESPONDANTQUALITE' => 'test', //Monsieur ou madame 
            'NOMCORRESPONDANTQUALITE' => 'test', // Nom du corres qualité
            'CIVILITECONTACT' => 'test', //Monsieur ou madame
            'NOMCONTACT' => 'test', //Nom du client
            'PRENOMCONTACT' => 'test', //Prenom du client
            'FCTCONTACT' => 'test', //Fonction du contact
            'CIVPRESIDENT' => 'test', //Monsieur ou madame
            'NOMPRESIDENT' => 'test', //Nom du président
            'DJOUR' => 'test', //date actuelle au format 05/05/1994
        ];
    }

    private function __make_recapitulatifmission_dict($params)
    {
        $this->dict = [
            'NOMENTREPRISE' => $params[Services::PARAM_PROJECT]->GetFirmId()->GetName(),
            'TITREETUDE' => $params[Services::PARAM_PROJECT]->GetNumber(),
            'NUMINTER' => 'test', //Numero de l'intervenant
            'CIVILITEINTERVENANT' => 'test', //Monsieur ou madame 
            'NOMINTERVENANT' => 'test', //Nom de l'intervenant, à faire plusieurs fois si plusieurs intervenants
            'PRENOMINTERVENANT' => 'test', //Prénom de l'intervenant
            'ADRESSEINTERVENANT' => 'test', //Monsieur ou madame 
            'CPINTERVENANT' => 'test', //Code postal de l'intervenant
            'VILLEINTERVENANT' => 'test', //Ville intervenant
            'SECUINTERVENANT' => 'test', //n° sécu intervenant
            'NBJOURSINTER' => 'test', //nb de jeh de l'intervenant
            'TOTALINTERVENANT' => 'test', //total d'indemnisation brut
            'TOTALINTERVENANT_L' => 'test', //total d'indemnisation brut en lettre
            'TARIFINTERV' => 'test', //nb de euros par jeh
            'TOTALINTERVENANTTTC' => 'test', //total TTC
            'TOTALINTERVENANTTTC_L' => 'test', //total TTC en lettre
            'NOMPRESIDENT' => 'test', //Nom du président
            'DJOUR' => 'test', //date actuelle au format 05/05/1994
        ];
    }

    private function __make_procesverballivraison_dict($params)
    {
        $this->dict = [
            'NOMENTREPRISE' => $params[Services::PARAM_PROJECT]->GetFirmId()->GetName(),
            'TITREETUDE' => $params[Services::PARAM_PROJECT]->GetNumber(),
            'DESCRIPTIONETUDE' => $params[Services::PARAM_PROJECT]->GetName(),
            'CIVILITECONTACT' => 'test', //Monsieur ou madame
            'NOMCONTACT' => 'test', //Nom du client
            'PRENOMCONTACT' => 'test', //Prenom du client
            'FCTCONTACT' => 'test', //Fonction du contact
            'NOMPRESIDENT' => 'test', //Nom du président
            'DJOUR' => 'test', //date actuelle au format 05/05/1994
            'DATESIGCV' => 'test', //date signature de la cc

        ];
    }

    private function __make_procesverbalfinrecette_dict($params)
    {
        $this->dict = [
            'NOMENTREPRISE' => $params[Services::PARAM_PROJECT]->GetFirmId()->GetName(),
            'TITREETUDE' => $params[Services::PARAM_PROJECT]->GetNumber(),
            'DESCRIPTIONETUDE' => $params[Services::PARAM_PROJECT]->GetName(),
            'CIVILITECONTACT' => 'test', //Monsieur ou madame
            'NOMCONTACT' => 'test', //Nom du client
            'PRENOMCONTACT' => 'test', //Prenom du client
            'FCTCONTACT' => 'test', //Fonction du contact
            'NOMPRESIDENT' => 'test', //Nom du président
            'DJOUR' => 'test', //date actuelle au format 05/05/1994
            'DATESIGCV' => 'test', //date signature de la cc

        ];
    }

    private function __make_procesverbalfin_dict($params)
    {
        $this->dict = [
            'NOMENTREPRISE' => $params[Services::PARAM_PROJECT]->GetFirmId()->GetName(),
            'TITREETUDE' => $params[Services::PARAM_PROJECT]->GetNumber(),
            'DESCRIPTIONETUDE' => $params[Services::PARAM_PROJECT]->GetName(),
            'CIVILITECONTACT' => 'test', //Monsieur ou madame
            'NOMCONTACT' => 'test', //Nom du client
            'PRENOMCONTACT' => 'test', //Prenom du client
            'FCTCONTACT' => 'test', //Fonction du contact
            'NOMPRESIDENT' => 'test', //Nom du président
            'DJOUR' => 'test', //date actuelle au format 05/05/1994
            'DATESIGCV' => 'test', //date signature de la cc

        ];
    }


    private function __make_avenantetudiant_dict($params)
    {
        $this->dict = [
            'TITREETUDE' => $params[Services::PARAM_PROJECT]->GetNumber(),
            'NUMINTER' => 'test', //Numero de l'intervenant
            'CIVILITEINTERVENANT' => 'test', //Monsieur ou madame 
            'NOMINTERVENANT' => 'test', //Nom de l'intervenant, à faire plusieurs fois si plusieurs intervenants
            'PRENOMINTERVENANT' => 'test', //Prénom de l'intervenant
            'ADRESSEINTERVENANT' => 'test', //Monsieur ou madame 
            'CPINTERVENANT' => 'test', //Code postal de l'intervenant
            'VILLEINTERVENANT' => 'test', //Ville intervenant
            'SECUINTERVENANT' => 'test', //n° sécu intervenant
            'NOMPRESIDENT' => 'test', //Nom du président
            'DJOUR' => 'test', //date actuelle au format 05/05/1994
        ];
    }

    private function __make_avenantentreprise_dict($params)
    {
        $this->dict = [
            'NOMENTREPRISE' => $params[Services::PARAM_PROJECT]->GetFirmId()->GetName(),
            'ADRESSEENTREPRISE' => 'test', //adresse de l'etreprise
            'CPENTREPRISE' => 'test', //code postale de l'entreprise
            'VILLEENTREPRISE' => 'test', //ville de l'entreprise
            'SIRETENTREPRISE' => 'test', //siret de l'entreprise
            'TITREETUDE' => $params[Services::PARAM_PROJECT]->GetNumber(),
            'CIVILITECONTACT' => 'test', //Monsieur ou madame
            'NOMCONTACT' => 'test', //Nom du client
            'PRENOMCONTACT' => 'test', //Prenom du client
            'FCTCONTACT' => 'test', //Fonction du contact
            'NOMPRESIDENT' => 'test', //Nom du président
            'DJOUR' => 'test', //date actuelle au format 05/05/1994
        ];
    }

    private function __make_lettreenvoiavenant_dict($params)
    {
        $this->dict = [
            'NOMENTREPRISE' => $params[Services::PARAM_PROJECT]->GetFirmId()->GetName(),
            'ADRESSEENTREPRISE' => 'test', //adresse de l'etreprise
            'CPENTREPRISE' => 'test', //code postale de l'entreprise
            'VILLEENTREPRISE' => 'test', //ville de l'entreprise
            'TITREETUDE' => $params[Services::PARAM_PROJECT]->GetNumber(),
            'CIVILITECONTACT' => 'test', //Monsieur ou madame
            'NOMCONTACT' => 'test', //Nom du client
            'DJOUR' => 'test', //date actuelle au format 05/05/1994
            'NOMUSER' => 'test', //Nom du chadaff 
            'PRENOMUSER' => 'test', //Prenom du chadaff 
        ];
    }

    private function __make_lettreenvoipropale_dict($params)
    {
        $this->dict = [
            'NOMENTREPRISE' => $params[Services::PARAM_PROJECT]->GetFirmId()->GetName(),
            'ADRESSEENTREPRISE' => 'test', //adresse de l'etreprise
            'CPENTREPRISE' => 'test', //code postale de l'entreprise
            'VILLEENTREPRISE' => 'test', //ville de l'entreprise
            'TITREETUDE' => $params[Services::PARAM_PROJECT]->GetNumber(),
            'CIVILITECONTACT' => 'test', //Monsieur ou madame
            'NOMCONTACT' => 'test', //Nom du client
            'DJOUR' => 'test', //date actuelle au format 05/05/1994
            'NOMUSER' => 'test', //Nom du chadaff 
            'PRENOMUSER' => 'test', //Prenom du chadaff 
        ];
    }

    private function __make_lettreenvoidevis_dict($params)
    {
        $this->dict = [
            'NOMENTREPRISE' => $params[Services::PARAM_PROJECT]->GetFirmId()->GetName(),
            'ADRESSEENTREPRISE' => 'test', //adresse de l'etreprise
            'CPENTREPRISE' => 'test', //code postale de l'entreprise
            'VILLEENTREPRISE' => 'test', //ville de l'entreprise
            'TITREETUDE' => $params[Services::PARAM_PROJECT]->GetNumber(),
            'CIVILITECONTACT' => 'test', //Monsieur ou madame
            'NOMCONTACT' => 'test', //Nom du client
            'DJOUR' => 'test', //date actuelle au format 05/05/1994
            'NOMUSER' => 'test', //Nom du chadaff 
            'PRENOMUSER' => 'test', //Prenom du chadaff 
        ];
    }

    private function __make_devis_dict($params)
    {
        $this->dict = [
            //'DJOUR' => 'test', //date actuelle au format 05/05/1994
            'FRAISETIC' => 'test', // frais de gestion
            'FRAISENTREPRISE' => 'test', // frais de dossier
            'TOTALENTREPRISE' => 'test', // total HT
            'MONTANTTVAENT' => 'test', // montant de la tva
            'TOTALENTREPRISETTC' => 'test', // total ttc
            'TAUXTVA' => 'test', // Taux de tva
        ];
    }

    private function __make_confidentialiteetudiant_dict($params)
    {
        $this->dict = [
            'TITREETUDE' => $params[Services::PARAM_PROJECT]->GetNumber(),
            'NOMENTREPRISE' => $params[Services::PARAM_PROJECT]->GetFirmId()->GetName(),
            'CIVILITEINTERVENANT' => 'test', //Monsieur ou madame 
            'NOMINTERVENANT' => 'test', //Nom de l'intervenant, à faire plusieurs fois si plusieurs intervenants
            'PRENOMINTERVENANT' => 'test', //Prénom de l'intervenant
            'ADRESSEINTERVENANT' => 'test', //Monsieur ou madame 
            'CPINTERVENANT' => 'test', //Code postal de l'intervenant
            'VILLEINTERVENANT' => 'test', //Ville intervenant
            'SECUINTERVENANT' => 'test', //n° sécu intervenant
            'NOMPRESIDENT' => 'test', //Nom du président
            'DJOUR' => 'test', //date actuelle au format 05/05/1994
        ];
    }

    private function __make_confidentialiteentreprise_dict($params)
    {
        $this->dict = [
            'NOMENTREPRISE' => $params[Services::PARAM_PROJECT]->GetFirmId()->GetName(),
            'ADRESSEENTREPRISE' => 'test', //adresse de l'etreprise
            'CPENTREPRISE' => 'test', //code postale de l'entreprise
            'VILLEENTREPRISE' => 'test', //ville de l'entreprise
            'SIRETENTREPRISE' => 'test', //siret de l'entreprise
            'TITREETUDE' => $params[Services::PARAM_PROJECT]->GetNumber(),
            'CIVILITECONTACT' => 'test', //Monsieur ou madame
            'NOMCONTACT' => 'test', //Nom du client
            'PRENOMCONTACT' => 'test', //Prenom du client
            'FCTCONTACT' => 'test', //Fonction du contact
            'NOMPRESIDENT' => 'test', //Nom du président
            'CIVILITETRESORIER' => 'test', //Monsieur ou madame
            'NOMTRESORIER' => 'test', //Nom du treso
            'PRENOMTRESORIER' => 'test', //Prenom du treso
            'DJOUR' => 'test', //date actuelle au format 05/05/1994
        ];
    }

    private function __make_demandefacture_dict($params)
    {
        $this->dict = [
            'NOMENTREPRISE' => $params[Services::PARAM_PROJECT]->GetFirmId()->GetName(),
            'ADRESSEENTREPRISE' => 'test', //adresse de l'etreprise
            'CPENTREPRISE' => 'test', //code postale de l'entreprise
            'VILLEENTREPRISE' => 'test', //ville de l'entreprise
            'TITREETUDE' => $params[Services::PARAM_PROJECT]->GetNumber(),
            'NOMCONTACT' => 'test', //Nom du client
            'PRENOMCONTACT' => 'test', //Prenom du client
            'DATESIGCV' => 'test', //date signature de la cc
            'DESCRIPTIONETUDE' => $params[Services::PARAM_PROJECT]->GetName(),
            'NOMUSER' => 'test', //Nom du chadaff 
        ];
    }

    private function __make_demandebv_dict($params)
    {
        $this->dict = [
            'TITREETUDE' => $params[Services::PARAM_PROJECT]->GetNumber(),
            'NOMENTREPRISE' => $params[Services::PARAM_PROJECT]->GetFirmId()->GetName(),
            'NOMINTERVENANT' => 'test', //Nom de l'intervenant, à faire plusieurs fois si plusieurs intervenants
            'PRENOMINTERVENANT' => 'test', //Prénom de l'intervenant
            'ADRESSEINTERVENANT' => 'test', //Monsieur ou madame 
            'CPINTERVENANT' => 'test', //Code postal de l'intervenant
            'VILLEINTERVENANT' => 'test', //Ville intervenant
            'SECUINTERVENANT' => 'test', //n° sécu intervenant
            'NOMUSER' => 'test', //Nom du chadaff 
            'PRENOMUSER' => 'test', //Prenom du chadaff 
        ];
    }

}