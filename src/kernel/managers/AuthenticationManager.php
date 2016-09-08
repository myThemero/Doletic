<?php

require_once "interfaces/AbstractManager.php";
require_once "loaders/DBObjectLoader.php";

/**
 *    The role of this manager is to manage user and its rights
 */
class AuthenticationManager extends AbstractManager
{

    // -- attributes
    private $user = null;
    private $rgcode = null;
    private $rgcodes = array();

    // -- functions
    public function __construct(&$kernel)
    {
        parent::__construct($kernel);
        $this->user = null;
        $this->rgcode = null;
        $this->rgcodes = array();
    }

    /**
     *    Initializes this manager
     */
    public function Init()
    {
        // nothing to init
    }

    /**
     *    Returns true if a valid user is registered
     */
    public function HasValidUser()
    {
        return isset($this->user);
    }

    /**
     *    Returns true if a valid user has been retrieved
     */
    public function AuthenticateUser($username, $hash)
    {
        // load user into class private attribute
        $this->user = parent::kernel()->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($this->GetCurrentUser())
            ->GetResponseData(
                UserServices::GET_USER_BY_UNAME, array(
                UserServices::PARAM_UNAME => $username,
                UserServices::PARAM_HASH => $hash));
        if ($this->HasValidUser()) {    // retrieve RG code if user is valid
            $this->rgcode = parent::kernel()->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($this->GetCurrentUser())
                ->GetResponseData(
                    UserDataServices::GET_USER_RG_CODE, array());
            $this->rgcodes = parent::kernel()->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($this->GetCurrentUser())
                ->GetResponseData(
                    UserDataServices::GET_USER_RIGHTS, array(UserDataServices::PARAM_USER_ID => $this->GetCurrentUser()->GetId()));
        }
        // return valid user check
        return $this->HasValidUser();
    }

    public function ResetPasswordExec($token)
    {
        $ok = false;
        $new_pass = parent::kernel()->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($this->GetCurrentUser())
            ->GetResponseData(UserServices::UPDATE_PASS, array(
                UserServices::PARAM_TOKEN => $token
            ));
        // if token has been created
        if (isset($new_pass)) {
            /// \todo send new_pass using user mail
            var_dump($new_pass); // DEBUG
            $ok = true;
        }
        return $ok;
    }

    public function ResetPasswordInit($mail)
    {
        $ok = false;
        // retrieve mail using service
        $udata = parent::kernel()->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($this->GetCurrentUser())
            ->GetResponseData(UserDataServices::CHECK_MAIL, array(
                UserDataServices::PARAM_EMAIL => $mail
            ));
        // if mail exists
        if (isset($udata)) {
            // create token using service
            $token = parent::kernel()->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($this->GetCurrentUser())
                ->GetResponseData(UserServices::UPDATE_TOKEN, array(
                    UserServices::PARAM_ID => $udata->GetUserId()
                ));
            // if token has been created
            if (isset($token)) {
                /// \todo send token using user mail
                var_dump($token); // DEBUG
                $ok = true;
            }
        }
        return $ok;
    }

    public function GetCurrentUser()
    {
        if (isset($this->user)) {
            return $this->user;                        // return current user
        } else {
            return new User(-1, "invalid", "", ""); // return invalid user
        }
    }

    public function GetCurrentUserRGCode()
    {
        if (isset($this->rgcode)) {
            return $this->rgcode;                        // return current user
        } else {
            return (RightsMap::G_R | RightsMap::D_G);    // return guest and default group
        }

    }

    public function GetCurrentUserRGCodeForModule($module)
    {
        if (isset($this->rgcodes[$module])) {
            return $this->rgcodes[$module];                        // return current user
        }
        return (RightsMap::G_R | RightsMap::D_G);    // return guest and default group
    }
}