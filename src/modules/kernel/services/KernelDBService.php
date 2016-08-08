<?php

require_once "interfaces/AbstractDBService.php";
require_once "objects/DBProcedure.php";
require_once "objects/DBTable.php";

class KernelDBService extends AbstractDBService
{

    // -- consts
    const VALID_MEMBERSHIP = "Valide";
    const INVALID_MEMBERSHIP = "Invalide";
    const NO_MEMBERSHIP = "Non";
    // --- service name
    const SERV_NAME = "modkernel";
    // --- params keys
    const PARAM_ID = "id";
    const PARAM_USER_ID = "userId";
    // --- internal services (actions)
    const GET_ALL_UDATA_WITH_STATUS = "alluwsta";
    const GET_ALL_INTERVENANTS = "allint";
    const GET_ALL_VALID_INTERVENANTS = "allvalint";

    public function __construct($module)
    {
        // -- construct parent
        parent::__construct($module, KernelDBService::SERV_NAME);
    }

    public function GetResponseData($action, $params)
    {
        $data = null;
        if (!strcmp($action, KernelDBService::GET_ALL_UDATA_WITH_STATUS)) {
            $data = $this->__get_all_user_data_with_status();
        } else if (!strcmp($action, KernelDBService::GET_ALL_INTERVENANTS)) {
            $data = $this->__get_all_intervenants();
        } else if (!strcmp($action, KernelDBService::GET_ALL_VALID_INTERVENANTS)) {
            $data = $this->__get_all_valid_intervenants();
        }
        return $data;
    }

# PROTECTED & PRIVATE ####################################################

    private function __get_all_user_data_with_status()
    {
        $udata = parent::getDBObjectResponseData(
            UserDataDBObject::OBJ_NAME,
            UserDataServices::GET_ALL_USER_DATA,
            array()
        );
        $admm_status = parent::getDBObjectResponseData(
            AdmMembershipDBObject::OBJ_NAME,
            AdmMembershipServices::GET_ALL_CURRENT_ADM_MEMBERSHIPS,
            array()
        );
        $intm_status = parent::getDBObjectResponseData(
            IntMembershipDBObject::OBJ_NAME,
            IntMembershipServices::GET_ALL_CURRENT_INT_MEMBERSHIPS,
            array()
        );
        foreach ($udata as $user) {
            if (array_key_exists($user->GetUserId(), $admm_status)) {
                $user->SetAdmmStatus($admm_status[$user->GetUserId()]->isValid() ? KernelDBService::VALID_MEMBERSHIP : KernelDBService::INVALID_MEMBERSHIP);
            } else {
                $user->SetAdmmStatus(KernelDBService::NO_MEMBERSHIP);
            }
            if (array_key_exists($user->GetUserId(), $intm_status)) {
                $user->SetIntmStatus($intm_status[$user->GetUserId()]->isValid() ? KernelDBService::VALID_MEMBERSHIP : KernelDBService::INVALID_MEMBERSHIP);
            } else {
                $user->SetIntmStatus(KernelDBService::NO_MEMBERSHIP);
            }
        }
        return $udata;
    }

}