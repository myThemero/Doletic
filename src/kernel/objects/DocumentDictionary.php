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
        ];
    }

}