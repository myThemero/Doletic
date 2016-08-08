<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBTable.php";
require_once "objects/DBProcedure.php";
require_once "objects/RightsMap.php";

/**
 * @brief The Document class
 */
class Document implements \JsonSerializable
{
    // -- consts
    const CATEGORY = [
        'Etude' => ['Propale', 'Convention Client', 'PVRF'],
        'Phase' => ['PVR', 'PVL'],
        'Consultant' => ['RM', 'Demande de BV', 'Rapport pédagogique']
    ];

    // -- attributes
    private $id = null;
    private $label = null;
    private $upload_date = null;
    private $type = null;
    private $project_number = null;
    private $task_id = null;
    private $int_id = null;

    /**
     * Document constructor.
     */
    public function __construct($id, $label, $uploadDate, $type, $projectNumber = null, $taskId = null, $intId = null)
    {
        $this->id = $id;
        $this->label = $label;
        $this->upload_date = $uploadDate;
        $this->type = $type;
        $this->project_number = $projectNumber;
        $this->task_id = $taskId;
        $this->int_id = $intId;
    }


    public function jsonSerialize()
    {
        return [
            DocumentDBObject::COL_ID => $this->id,
            DocumentDBObject::COL_LABEL => $this->label,
            DocumentDBObject::COL_UPLOAD_DATE => $this->upload_date,
            DocumentDBObject::COL_TYPE => $this->type,
            DocumentDBObject::COL_PROJECT_NUMBER => $this->project_number,
            DocumentDBObject::COL_TASK_ID => $this->task_id,
            DocumentDBObject::COL_INT_ID => $this->int_id
        ];
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return null
     */
    public function getUploadDate()
    {
        return $this->upload_date;
    }

    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return null
     */
    public function getProjectNumber()
    {
        return $this->project_number;
    }

    /**
     * @return null
     */
    public function getTaskId()
    {
        return $this->task_id;
    }

    /**
     * @return null
     */
    public function getIntId()
    {
        return $this->int_id;
    }
}


class DocumentServices extends AbstractObjectServices
{

    // -- consts
    // --- params keys
    const PARAM_ID = "id";
    const PARAM_LABEL = "label";
    const PARAM_UPLOAD_DATE = "uploadDate";
    const PARAM_TYPE = "type";
    const PARAM_TEMPLATE_ID = "template_id";
    const PARAM_PROJECT_NUMBER = "projectNumber";
    const PARAM_TASK_ID = "taskId";
    const PARAM_INT_ID = "intId";

    // --- internal services (actions)

    // -- services that should not be used except for data migration
    const FORCE_INSERT = "forins";
    const FORCE_POSITION = "forpos";
    // -- functions

    // -- construct
    public function __construct($currentUser, $dbObject, $dbConnection)
    {
        parent::__construct($currentUser, $dbObject, $dbConnection);
    }

    public function GetResponseData($action, $params)
    {
        $data = null;
        return $data;
    }

# PROTECTED & PRIVATE ####################################################

    // -- consult

    // -- modify


# PUBLIC RESET STATIC DATA FUNCTION --------------------------------------------------------------------

    /**
     *    ---------!---------!---------!---------!---------!---------!---------!---------!---------
     *  !                            DATABASE CONSISTENCY WARNING                                !
     *  !                                                                                        !
     *  !  Please respect the following points :                                                !
     *    !  - When adding static data to existing data => always add at the end of the list      !
     *  !  - Never remove data (or ensure that no database element use one as a foreign key)    !
     *    ---------!---------!---------!---------!---------!---------!---------!---------!---------
     */
    public function ResetStaticData()
    {
        // --- retrieve SQL query
        $sql_category = parent::getDBObject()->GetTable(DocumentDBObject::TABL_DOC_CATEGORY)->GetINSERTQuery();
        $sql_type = parent::getDBObject()->GetTable(DocumentDBObject::TABL_DOC_TYPE)->GetINSERTQuery();
        foreach (Document::CATEGORY as $key => $category) {
            // --- create param array
            $sql_params = array(
                ":" . DocumentDBObject::COL_LABEL => $key
            );
            // --- execute SQL query
            parent::getDBConnection()->PrepareExecuteQuery($sql_category, $sql_params);
            foreach($category as $type) {
                $sql_params = array(
                    ":" . DocumentDBObject::COL_LABEL => $type,
                    ":" . DocumentDBObject::COL_CATEGORY => $key,
                    ":" . DocumentDBObject::COL_TEMPLATE_ID => null
                );
                parent::getDBConnection()->PrepareExecuteQuery($sql_type, $sql_params);
            }
        }
    }

}

/**
 * @brief Document object interface
 */
class DocumentDBObject extends AbstractDBObject
{

    // -- consts
    // --- object name
    const OBJ_NAME = "document";
    // --- tables
    const TABL_DOCUMENT = "dol_document";
    const TABL_DOC_TYPE = "dol_document_type";
    const TABL_DOC_CATEGORY = "dol_document_category";
    // --- columns
    const COL_ID = "id";
    const COL_LABEL = "label";
    const COL_UPLOAD_DATE = "upload_date";
    const COL_TYPE = "type";
    const COL_TEMPLATE_ID = "template_id";
    const COL_CATEGORY = "category";
    const COL_PROJECT_NUMBER = "project_number";
    const COL_TASK_ID = "task_id";
    const COL_INT_ID = "int_id";
    // -- attributes

    // -- functions

    public function __construct($module)
    {
        // -- construct parent
        parent::__construct($module, DocumentDBObject::OBJ_NAME);

        // -- create tables

        // --- dol_document_type table
        $dol_doc_category = new DBTable(DocumentDBObject::TABL_DOC_CATEGORY);
        $dol_doc_category
            ->AddColumn(DocumentDBObject::COL_LABEL, DBTable::DT_VARCHAR, 255, false, "", false, true, true);

        // --- dol_document_type table
        $dol_doc_type = new DBTable(DocumentDBObject::TABL_DOC_TYPE);
        $dol_doc_type
            ->AddColumn(DocumentDBObject::COL_LABEL, DBTable::DT_VARCHAR, 255, false, "", false, true, true)
            ->AddColumn(DocumentDBObject::COL_CATEGORY, DBTable::DT_VARCHAR, 255, false, "")
            ->AddColumn(DocumentDBObject::COL_TEMPLATE_ID, DBTable::DT_INT, 11, true, "")
            ->AddForeignKey(DocumentDBObject::TABL_DOC_TYPE . '_fk1', DocumentDBObject::COL_CATEGORY, DocumentDBObject::TABL_DOC_CATEGORY, DocumentDBObject::COL_LABEL, DBTable::DT_RESTRICT, DBTable::DT_CASCADE);

        // --- dol_document table
        $dol_document = new DBTable(DocumentDBObject::TABL_DOCUMENT);
        $dol_document
            ->AddColumn(DocumentDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true)
            ->AddColumn(DocumentDBObject::COL_LABEL, DBTable::DT_VARCHAR, 255, false, "")
            ->AddColumn(DocumentDBObject::COL_UPLOAD_DATE, DBTable::DT_DATETIME, -1, false, "")
            ->AddColumn(DocumentDBObject::COL_TYPE, DBTable::DT_VARCHAR, 50, false, "")
            ->AddForeignKey(DocumentDBObject::TABL_DOCUMENT . '_fk1', DocumentDBObject::COL_TYPE, DocumentDBObject::TABL_DOC_TYPE, DocumentDBObject::COL_LABEL, DBTable::DT_RESTRICT, DBTable::DT_CASCADE);

        // -- add tables
        parent::addTable($dol_doc_category);
        parent::addTable($dol_doc_type);
        parent::addTable($dol_document);

    }

    /**
     * @brief Returns all services associated with this object
     */
    public function GetServices($currentUser)
    {
        return new DocumentServices($currentUser, $this, $this->getDBConnection());
    }

    /**
     *    Initialize static data
     */
    public function ResetStaticData()
    {
        $services = new DocumentServices(null, $this, $this->getDBConnection());
        $services->ResetStaticData();
    }

}
