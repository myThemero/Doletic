<?php

require_once "DoleticKernel.php";
require_once "services/Services.php";

/**
 * @warning all displayXXX and service function exit script after their execution
 *                in order to ensure nothing is printed after.
 */
class Main
{

    // -- consts
    // --- GET & POST keys
    const RPARAM_QUERY = "q";
    const RPARAM_TOKEN = "t";
    // --- GET specific params
    // --- POST specific params
    const PPARAM_TOKEN = "token";
    const PPARAM_PAGE = "page";
    const PPARAM_USER = "user";
    const PPARAM_HASH = "hash";
    const PPARAM_MAIL = "mail";
    // --- SESSION keys
    const SPARAM_DOL_KERN = "doletic_kernel";
    // --- known queries
    const QUERY_SERVICE = "service";
    const QUERY_LOGOUT = "logout";
    const QUERY_LOGIN = "login";
    const QUERY_AUTHEN = "auth";
    const QUERY_LOST = "lost";
    const QUERY_RESET_PASS = "resetpass";
    const QUERY_INTERF = "ui";

    // -- functions

    public function Run()
    {
        // retreive session
        session_start();
        // DEBUG --------------------------------------------
        if (array_key_exists(Main::RPARAM_QUERY, $_GET)) {
            // GET query is about logout
            if (!strcmp($_GET[Main::RPARAM_QUERY], "reset")) {
                print_r($_SESSION[Main::SPARAM_DOL_KERN]);
                $this->__display_logout();
            } else if (!strcmp($_GET[Main::RPARAM_QUERY], Main::QUERY_RESET_PASS)) {
                // Create a doletic kernel, initialize it & put it in session vars
                $_SESSION[Main::SPARAM_DOL_KERN] = new DoleticKernel();
                $_SESSION[Main::SPARAM_DOL_KERN]->Init();
                // reload settings
                $_SESSION[Main::SPARAM_DOL_KERN]->ReloadSettings();
                // connect to database
                $_SESSION[Main::SPARAM_DOL_KERN]->ConnectDB();
                $_POST[Main::RPARAM_QUERY] = $_GET[Main::RPARAM_QUERY];
                $_POST[Main::RPARAM_TOKEN] = $_GET[Main::RPARAM_TOKEN];
            }
        }
        // --------------------------------------------------
        // check if doletic kernel exists in session
        if (array_key_exists(Main::SPARAM_DOL_KERN, $_SESSION)) {
            // reload settings
            $_SESSION[Main::SPARAM_DOL_KERN]->ReloadSettings();
            // connect database
            $_SESSION[Main::SPARAM_DOL_KERN]->ConnectDB();
            // check if kernel has a valid user registered
            if ($_SESSION[Main::SPARAM_DOL_KERN]->HasValidUser()) {
                //check if query received in POST
                if (array_key_exists(Main::RPARAM_QUERY, $_POST)) {
                    // GET query is about interface
                    // if login query received 
                    if (!strcmp($_POST[Main::RPARAM_QUERY], Main::QUERY_LOGIN)) {
                        $this->__display_home(); // display home cause user is already logged in
                    } // if service query received
                    else if (!strcmp($_POST[Main::RPARAM_QUERY], Main::QUERY_SERVICE)) {
                        $this->__service();
                    } // POST logout query
                    else if (!strcmp($_POST[Main::RPARAM_QUERY], Main::QUERY_LOGOUT)) {
                        $this->__display_logout();
                    } // POST ui query
                    else if (!strcmp($_POST[Main::RPARAM_QUERY], Main::QUERY_INTERF)) {
                        $this->__display_interface();
                    }
                } else {
                    $this->__display_base();
                }
            } //check if query received in POST
            else if (array_key_exists(Main::RPARAM_QUERY, $_POST)) {
                // if login query received 
                if (!strcmp($_POST[Main::RPARAM_QUERY], Main::QUERY_LOGIN)) {
                    $this->__display_login();
                } // if authentication query received
                else if (!strcmp($_POST[Main::RPARAM_QUERY], Main::QUERY_AUTHEN)) {
                    $this->__authenticate();
                } // if lost password ui required
                else if (!strcmp($_POST[Main::RPARAM_QUERY], Main::QUERY_LOST)) {
                    $this->__display_lost_ui();
                } // if reset password query received
                else if (!strcmp($_POST[Main::RPARAM_QUERY], Main::QUERY_RESET_PASS) && isset($_POST[Main::RPARAM_TOKEN])) {
                    $this->__confirm_reset_pass($_POST[Main::RPARAM_TOKEN]);
                }
                else if (!strcmp($_POST[Main::RPARAM_QUERY], Main::QUERY_RESET_PASS)) {
                    $this->__reset_pass();
                }
            } else { // if no valid user ask for a login
                $this->__display_base();
            }
            // disconnect database
            $_SESSION[Main::SPARAM_DOL_KERN]->DisconnectDB();
        } else { // if no doletic kernel in session, create one
            $this->__init();
        }
        $this->__display_login();
    }

# PROTECTED & PRIVATE ##########################################################

    private function __init()
    {
        // Create a doletic kernel, initialize it & put it in session vars
        $_SESSION[Main::SPARAM_DOL_KERN] = new DoleticKernel();
        $_SESSION[Main::SPARAM_DOL_KERN]->Init();
        // connect to database
        $_SESSION[Main::SPARAM_DOL_KERN]->ConnectDB();
        // call login to show login interface
        $this->__display_base();
    }

    private function __authenticate()
    {
        // check if all post params are present
        if (array_key_exists(Main::PPARAM_USER, $_POST) &&
            array_key_exists(Main::PPARAM_HASH, $_POST)
        ) {
            // Ask kernel to authenticate user
            $ok = $_SESSION[Main::SPARAM_DOL_KERN]->AuthenticateUser($_POST[Main::PPARAM_USER], $_POST[Main::PPARAM_HASH]);
            // Terminate returning approriated json structure
            $this->__terminate(json_encode(array('authenticated' => $ok)));
        } else { // if params are missing show login page
            $this->__display_login();
        }
    }

    private function __reset_pass()
    {
        // Ask kernel for password reset
        $ok = $_SESSION[Main::SPARAM_DOL_KERN]->ResetPasswordInit($_POST[Main::PPARAM_MAIL]);
        // Terminate returning approriated json structure
        $this->__terminate(json_encode(array('sent' => $ok)));
    }

    private function __confirm_reset_pass($token)
    {
        // Ask kernel for password reset
        $ok = true;
        //$ok = $_SESSION[Main::SPARAM_DOL_KERN]->ResetPasswordExec($token);
        $this->__display_restored();
//        if($ok) {
//            $this->__display_restored();
//        } else {
//            $this->__terminate(json_encode(array('sent' => $ok)));
//        }
    }


    private function __service()
    {
        // create service instance
        $service = new Services($_SESSION[Main::SPARAM_DOL_KERN]);
        // check if minimum post params are present
        if (array_key_exists(Services::PPARAM_OBJ, $_POST) &&
            array_key_exists(Services::PPARAM_ACT, $_POST)
        ) {
            // return service response JSON encoded
            $this->__terminate($service->Response($_POST));
        } else { // if params are missing return service default response
            $this->__terminate($service->DefaultResponse());
        }
    }

    private function __display_interface()
    {
        // check if params
        if (array_key_exists(Main::PPARAM_PAGE, $_POST)) {
            // display given interface
            $this->__terminate($_SESSION[Main::SPARAM_DOL_KERN]->GetInterfaceScripts($_POST[Main::PPARAM_PAGE]));
        } else {
            // display page not found interface
            $this->__terminate($_SESSION[Main::SPARAM_DOL_KERN]->GetInterfaceScripts(UIManager::INTERFACE_404));
        }
    }

    private function __display_base()
    {
        $this->__terminate($_SESSION[Main::SPARAM_DOL_KERN]->GetHTMLBase());
    }

    private function __display_lost_ui()
    {
        $this->__terminate($_SESSION[Main::SPARAM_DOL_KERN]->GetInterfaceScripts(UIManager::INTERFACE_LOST));
    }

    private function __display_restored()
    {
        $this->__terminate($_SESSION[Main::SPARAM_DOL_KERN]->GetInterfaceScripts(UIManager::INTERFACE_RESTORED));
    }

    private function __display_login()
    {
        // display login interface
        $this->__terminate($_SESSION[Main::SPARAM_DOL_KERN]->GetInterfaceScripts(UIManager::INTERFACE_LOGIN));
    }

    private function __display_logout()
    {
        // display logout interface
        echo $_SESSION[Main::SPARAM_DOL_KERN]->GetInterfaceScripts(UIManager::INTERFACE_LOGOUT);
        // terminate kernel
        $_SESSION[Main::SPARAM_DOL_KERN]->Terminate();
        // unset session vars
        $_SESSION = array();
        // destroy session
        session_destroy();
        // exit explicitly
        exit;
    }

    private function __display_home()
    {
        // load home interface
        $this->__terminate($_SESSION[Main::SPARAM_DOL_KERN]->GetInterfaceScripts(UIManager::INTERFACE_HOME));
    }

    private function __terminate($response)
    {
        // print
        echo $response;
        // disconnect database
        $_SESSION[Main::SPARAM_DOL_KERN]->DisconnectDB();
        // explicitly exit
        exit;
    }

}

// Create an instance of main and run it

$main = new Main();
$main->Run();