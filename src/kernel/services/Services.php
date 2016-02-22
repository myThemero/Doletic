<?php 

require_once "DoleticKernel.php";
require_once "objects/RightsMap.php";
require_once "objects/DocumentProcessor.php";
require_once "services/components/ServiceResponse.php";
require_once "services/components/UploadComponent.php";
require_once "services/components/DownloadComponent.php";

/**
* 
*/
class Services {

	// -- consts
	// --- post attributes required
	const PPARAM_OBJ  		= "obj"; 
	const PPARAM_ACT  		= "act"; 
	const PPARAM_PARAMS 	= "params"; 
	// --- services internal consts
	const OBJ_SERVICE 		= "service";
	// --- high-level services
	const SERVICE_UPLOAD 		= "upload";
	const SERVICE_DOWNLOAD		= "download";
	const SERVICE_UI_LINKS		= "uilinks";
	const SERVICE_GET_USER		= "getuser";
	const SERVICE_UPDATE_AVATAR = "updateava";
	const SERVICE_GET_AVATAR 	= "getava";
	// --- params keys
	const PKEY_ID           = "id";
	const PKEY_FNAME		= "filename";
	const PKEY_STUDY_ID		= "studyId";
	const PKEY_TEMPLATE_IDS	= "templateIds";
	const PKEY_DOC_TYPE		= "documentType";
	

	// -- attributes
	private $kernel = null;
	private $rights_map = null;

	// -- functions
	public function __construct(&$kernel) {
		// --- init kernel and rights map
		$this->kernel = $kernel;
		$this->rights_map = new RightsMap();
		// --- add rules to right
		$this->rights_map->AddRules(array(
				Services::SERVICE_UPLOAD   => RightsMap::G_RMASK,
				Services::SERVICE_DOWNLOAD => RightsMap::G_RMASK,
				Services::SERVICE_UI_LINKS => RightsMap::G_RMASK,
				Services::SERVICE_GET_USER   => RightsMap::G_RMASK,
				Services::SERVICE_UPDATE_AVATAR => RightsMap::G_RMASK,
				Services::SERVICE_GET_AVATAR => RightsMap::G_RMASK
			));
	}

// --------------------------------- GLOBAL Services entry points ----------------------------------------------------------


	public function Response($post = array(), $pretty = false) {
		// first check check if object requested is service for high-level services
		if($post[Services::PPARAM_OBJ] === Services::OBJ_SERVICE) {
			if($this->__check_rights_service($post[Services::PPARAM_ACT])) {
				// find which service is called
				if($post[Services::PPARAM_ACT] === Services::SERVICE_UPLOAD) {
					$response = UploadComponent::execute($this->kernel);
				} else if($post[Services::PPARAM_ACT] === Services::SERVICE_DOWNLOAD) {
					$response = DownloadComponent::execute($this->kernel, $post[Services::PPARAM_PARAMS][Services::PKEY_ID]);
				} else if($post[Services::PPARAM_ACT] === Services::SERVICE_UI_LINKS) {
					$response = $this->__service_uis();
				} else if($post[Services::PPARAM_ACT] === Services::SERVICE_GET_USER) {
					$response = $this->__service_get_user();
				} else if($post[Services::PPARAM_ACT] === Services::SERVICE_UPDATE_AVATAR) {
					$response = $this->__service_update_user_avatar($post);
				} else if($post[Services::PPARAM_ACT] === Services::SERVICE_GET_AVATAR) {
					$response = $this->__service_get_avatar();
				} else {
					$response = new ServiceResponse("", ServiceResponse::ERR_MISSING_SERVICE, "Service is missing.");
				}
			} else {
				$response = new ServiceResponse("", ServiceResponse::ERR_INSUFFICIENT_RIGHTS, "Insufficient rights to access this service.");
			}
		} // else an atomic service is called -> redirect call to object specific services
		else {
			// declare response var
			$response = null;
			// retreive db object
			$obj = $this->kernel->GetDBObject($post[Services::PPARAM_OBJ]);
			if(isset($obj)) {
				// check rights
				if($this->__check_rights_module($obj->GetModule(), $post[Services::PPARAM_OBJ].':'.$post[Services::PPARAM_ACT])) {
					// retrieve services
					$services = $obj->GetServices($this->kernel->GetCurrentUser());
					// retreive response data
					if(array_key_exists(Services::PPARAM_PARAMS, $post)) {
						$data = $services->GetResponseData(
										$post[Services::PPARAM_ACT], 
										$post[Services::PPARAM_PARAMS]);
					} else {
						$data = $services->GetResponseData(
										$post[Services::PPARAM_ACT], 
										array());
					}
					if(isset($data)) {
						$response = new ServiceResponse($data);
					} else {
						$response = new ServiceResponse("[]"); // empty return from service
					}	
				} else {
					$response = new ServiceResponse("", ServiceResponse::ERR_INSUFFICIENT_RIGHTS, "Insufficient rights to access this service.");
				}
			} else {
				$response = new ServiceResponse("", ServiceResponse::ERR_MISSING_OBJ, "Object is missing.");
			}
		}
		// return response encoded to json
		$json = "";
		if ($pretty) {
			$json = json_encode($response, JSON_PRETTY_PRINT)."\n";
		} else {
			$json = json_encode($response);
		}
		return $json;
	}
	/**
	 *	Retourne la réponse par défaut du service, cela signifie que la requête est incomplète
	 */
	public function DefaultResponse() {
		$response = new ServiceResponse("", ServiceResponse::ERR_MISSING_PARAMS, "Parameters (obj and/or act) are missing.");
		return json_encode($response);
	}

// --------------------------------- HIGH-LEVEL Services ---------------------------------------------------------------------

	/**
	 *	Vérifie que l'utilisateur courant possède les droits suffisant pour effectuer l'action demandée au niveau service
	 */
	private function __check_rights_service($action) {
		return ( $this->rights_map->Check($this->kernel->GetCurrentUserRGCode(), $action) === RightsMap::OK );
	}
	/**
	 *	Vérifie que l'utilisateur courant possède les droits suffisant pour effectuer l'action demandée au niveau module
	 */
	private function __check_rights_module($module, $action) {
		return $module->CheckRights($this->kernel->GetCurrentUserRGCode(), $action);	
	}

	private function __service_uis() {
		// return response
		return new ServiceResponse($this->kernel->GetModuleUILinks());
	}

	private function __service_get_user() {
		// return response
		return new ServiceResponse($this->kernel->GetCurrentUser());	
	}

	private function __service_update_user_avatar($post) {
		// -- initialize response var
		$response = null;
		// -- surround process with try catch block to handle errors
		try {
		    // -- retrieve current user data
			$udata = $this->kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($this->kernel->GetCurrentUser())
						->GetResponseData(UserDataServices::GET_CURRENT_USER_DATA, array());
			// -- remove current avatar if needed
			if(!isset($udata)) {
				throw new RuntimeException("Echec de récupération des données de l'utilisateur courant", ServiceResponse::ERR_SERVICE_FAILED);
			}
			// if user data avatar id is not 0
			if($udata->GetAvatarId() != 0) {
				$result = $this->kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices($this->kernel->GetCurrentUser())
							->GetResponseData(UploadServices::DELETE_OWNER_CHECK, array(
								UploadServices::PARAM_ID => $udata->GetAvatarId()));	
				if(!isset($result)) {
					throw new RuntimeException("Echec de suppression de l'avatar courant", ServiceResponse::ERR_SERVICE_FAILED);
				}
			}
			// -- add new avatar
			$result = $this->kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($this->kernel->GetCurrentUser())
						->GetResponseData(UserDataServices::UPDATE_AVATAR, $post[Services::PPARAM_PARAMS]);
			// -- treat result
			if(!isset(null)) {
				throw new RuntimeException("Echec d'ajout du nouvel avatar.", ServiceResponse::ERR_SERVICE_FAILED);
			}
			// create service response
			$response = new ServiceResponse($result);
		} catch (RuntimeException $e) {
		    $response = new ServiceResponse("", $e->getCode(), $e->getMessage());
		}
		// return response
		return $response;
	}

	private function __service_get_avatar() {
		// initialize null response
		$response = null;
		// -- surround process with try catch block to handle errors
		try {
			// retrieve user data
			$udata = $this->kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($this->kernel->GetCurrentUser())
							->GetResponseData(UserDataServices::GET_CURRENT_USER_DATA, array());
			// if user data has been retrieved
			if(!isset($udata)) {
				throw new RuntimeException("Echec de récupération des données de l'utilisateur courant", ServiceResponse::ERR_SERVICE_FAILED);
			}
			// if avatar id is not 0 => meaning not default avatar
			if($udata->GetAvatarId() != 0) {
				$avatar = $this->kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices($this->kernel->GetCurrentUser())
						->GetResponseData(UploadServices::GET_UPLOAD_BY_ID, array(
							UploadServices::PARAM_ID => $udata->GetAvatarId()));	
				if(!isset($avatar)) {
					throw new RuntimeException("Echec de recupération de l'avatar courant.", ServiceResponse::ERR_SERVICE_FAILED);
				}
				// create service response
				$response = new ServiceResponse("/uploads".$avatar->GetStorageFilename());
			} else {
				// create service response
				$response = new ServiceResponse("/resources/image.png");
			}
		} catch (RuntimeException $e) {
		    $response = new ServiceResponse("", $e->getCode(), $e->getMessage());
		}
		// return response
		return $response;
	}

	private function __service_publish($post) {
		// initialize null response
		$response = null;
		// -- surround process with try catch block to handle errors
		try {
			// retrieve study object
			$study = $this->kernel->GetDBObject(StudyDBObject::OBJ_NAME)->GetServices($this->kernel->GetCurrentUser())
						->GetResponseData(StudyServices::GET_STUDY_BY_ID, array(
							StudyServices::PARAM_ID => $post[Services::PPARAM_PARAMS][Services::PKEY_STUDY_ID]));
			if(!isset($study)) {
				throw new RuntimeException("Echec de récupération de l'étude.", ServiceResponse::ERR_SERVICE_FAILED);
			}
			$templates = array();
			// retrieve all templates storage names
			foreach ($post[Services::PPARAM_PARAMS][Services::PKEY_TEMPLATE_IDS] as $id) {
				// retrieve upload record
				$upload = $this->kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices($this->kernel->GetCurrentUser())
					->GetResponseData(UploadServices::GET_UPLOAD_BY_ID, array(
						UploadServices::PARAM_ID => $id));
				// if upload record is not null
				if(!isset($upload)) {
					throw new RuntimeException("Echec de récupération d'un template.", ServiceResponse::ERR_SERVICE_FAILED);		
				}
				// push templates array
				array_push($templates, 
						rtrim($this->kernel->SettingValue(SettingsManager::KEY_DOLETIC_DIR)," /")."/uploads".$upload->GetStorageFilename());
			}
			// when all templates are retrieved, check if at least one template to process
			if(sizeof($templates) <= 0) {
				throw new RuntimeException("Aucun template à traiter.", ServiceResponse::ERR_SERVICE_FAILED);
			}
			// create document processor instance
			$doc_processor = new DocumentProcessor($this->kernel);
			// retrieve needed data to use document processor
			$basename = $study->GetUniqueIdentifier();
			$dictionary = $study->GetDictionnary();
			$type = $post[Services::PPARAM_PARAMS][Services::PKEY_DOC_TYPE];
			// process templates
			$result_dict = $doc_processor->GenerateFromTemplates($basename, $templates, $dictionary, $type);
			if($result_dict[DocumentProcessor::RESULT_STATUS]) {
				// create service response
				$response = new ServiceResponse($result_dict[DocumentProcessor::RESULT_DATA]);
			} else {
				$response = new ServiceResponse("", Services::ERR_SERVICE_FAILED, $result_dict[DocumentProcessor::RESULT_DATA]);
			}
			
		} catch (RuntimeException $e) {
		    $response = new ServiceResponse("", $e->getCode(), $e->getMessage());
		}
		return $response;
	}

}
