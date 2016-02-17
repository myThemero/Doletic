<?php 

require_once "DoleticKernel.php";
require_once "objects/RightsMap.php";
require_once "objects/DocumentProcessor.php";

class ServiceResponse implements \JsonSerializable {
	
	// -- consts
	// --- service errors
	const ERR_NO_ERROR 				= 0x00;
	const ERR_MISSING_PARAMS		= 0x01;
	const ERR_MISSING_OBJ 			= 0x02;
	const ERR_MISSING_ACT 			= 0x03;
	const ERR_MISSING_SERVICE   	= 0x04;
	const ERR_SERVICE_FAILED 		= 0x05;
	const ERR_INSUFFICIENT_RIGHTS 	= 0x06;
	// --- upload errors
	const ERR_UP_INVALID_PARAMS = 0x10;
	const ERR_UP_NO_FILE 		= 0x11;
	const ERR_UP_FILE_TOO_BIG 	= 0x12;
	const ERR_UP_UNKNOWN		= 0x13;
	const ERR_UP_FORBID_FORMAT	= 0x14;
	const ERR_UP_FILESYSTEM		= 0x15;
	// --- download errors
	const ERR_DL_MISSING_ID		= 0x20;
	const ERR_DL_MISSING_FILE   = 0x21;
	const ERR_DL_COPY		    = 0x23;
	const ERR_DL_MAX		    = 0x24;

	// -- attributes
	private $code;
	private $err_string;
	private $object;

	// -- functions
	/**
	 *
	 */
	public function __construct($responseData, $responseCode = ServiceResponse::ERR_NO_ERROR, $responseErrString = "RAS") {
		$this->code = $responseCode;
		$this->err_string = $responseErrString;
		$this->object = $responseData;
	}
	/**
	 *
	 */
	public function jsonSerialize() {
       return [
           'code' => $this->code,
           'error' => $this->err_string,
           'object' => $this->object];
   	}
}

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
	// --- upload related consts
	const UKEY_FILE			= "file";
	const UKEY_ERROR		= "error";
	const UKEY_SIZE			= "size";
	const UKEY_TMP_NAME		= "tmp_name";
	const UKEY_NAME			= "name";
	const UPL_MAX_FILE_SIZE = 4194304;	// size in bytes (4 Mo)
	const UPL_ALLOWED_EXTS  = array( 
	// format is 'ext' => 'associated_mime_type' 
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'png' => 'image/png',
		'svg' => 'image/svg',
		'pdf' => 'application/pdf',
		'zip' => 'application/zip',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'tex' => 'application/x-tex',
		'gz' => 'application/x-gzip');
	// --- filesystem related consts
	const UPLOAD_FOLDER 	= "/uploads";

	// -- attributes
	private $kernel;
	private $rights_map;

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
					$response = $this->__service_upload($post);
				} else if($post[Services::PPARAM_ACT] === Services::SERVICE_DOWNLOAD) {
					$response = $this->__service_download($post);
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
			if($obj != null) {
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
					if($data != null) {
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

	private function __service_upload($post) {
		// initialize response with null
		$response = null;	
		// simple upload without check for now...
		try {
   
		    // Undefined | Multiple Files | $_FILES Corruption Attack
		    // If this request falls under any of them, treat it invalid.
		    if (!isset($_FILES[Services::UKEY_FILE][Services::UKEY_ERROR]) ||
		        is_array($_FILES[Services::UKEY_FILE][Services::UKEY_ERROR])
		    ) {
		        throw new RuntimeException('Paramètres invalides', ServiceResponse::ERR_UP_INVALID_PARAMS);
		    }

		    // Check $_FILES['upfile']['error'] value. ====> PHP . INI related errors
		    switch ($_FILES[Services::UKEY_FILE][Services::UKEY_ERROR]) {
		        case UPLOAD_ERR_OK:
		            break;
		        case UPLOAD_ERR_NO_FILE:
		            throw new RuntimeException('Aucun fichier envoyé.', ServiceResponse::ERR_UP_NO_FILE);
		        case UPLOAD_ERR_INI_SIZE:
		        case UPLOAD_ERR_FORM_SIZE:
		            throw new RuntimeException('Taille limite de fichier dépassée.', ServiceResponse::ERR_UP_FILE_TOO_BIG);
		        default:
		            throw new RuntimeException('Erreur inconnue.', ServiceResponse::ERR_UP_UNKNOWN);
		    }

		    // You should also check filesize here.
		    if ($_FILES[Services::UKEY_FILE][Services::UKEY_SIZE] > Services::UPL_MAX_FILE_SIZE) {
		        throw new RuntimeException('Taille limite de fichier dépassée.', ServiceResponse::ERR_UP_FILE_TOO_BIG);
		    }

		    // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
		    // Check MIME Type by yourself.
		    $finfo = new finfo(FILEINFO_MIME_TYPE);
		    if (false === $ext = array_search(
		        $finfo->file($_FILES[Services::UKEY_FILE][Services::UKEY_TMP_NAME]),
		        Services::UPL_ALLOWED_EXTS,
		        true
		    )) {
		        throw new RuntimeException('Format de fichier interdit.', ServiceResponse::ERR_UP_FORBID_FORMAT);
		    }

			// retrieve filename
		    $filename = $_FILES[Services::UKEY_FILE][Services::UKEY_NAME];
		    // Obtain safe unique name from its binary data plus date part.
		    $destfname = sprintf('/%s_%s.%s', 
		    	sha1_file($_FILES[Services::UKEY_FILE][Services::UKEY_TMP_NAME]), 
		    	date('Y_m_d_H_i_s'),
		    	$ext);
		    // full dest (prepend absolute path)
		    $dest = rtrim($this->kernel->SettingValue(SettingsManager::KEY_DOLETIC_DIR)," /").Services::UPLOAD_FOLDER.$destfname;
		    // move uploaded file and throw error if it fails
		    if (!move_uploaded_file(
		        $_FILES[Services::UKEY_FILE][Services::UKEY_TMP_NAME],
		        $dest
		    )) {
		        throw new RuntimeException('Echec du déplacement du fichier reçu.', ServiceResponse::ERR_UP_FILESYSTEM);
		    }

		    $id = null;
		    $upload_params = array(
		    			UploadServices::PARAM_USER_ID => $this->kernel->GetCurrentUser()->GetId(),
		    			UploadServices::PARAM_FNAME => $filename,
		    			UploadServices::PARAM_STOR_FNAME => $destfname);
		    // write upload in database and retrieve its id
		    if(!$this->kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices($this->kernel->GetCurrentUser())
		    	->GetResponseData(UploadServices::INSERT, $upload_params)) {
		    	throw new RuntimeException("Erreur d'enregistrement dans la base de données.", ServiceResponse::ERR_UP_FILESYSTEM);
		    }
		    // retrieve user
	    	$upload = $this->kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices($this->kernel->GetCurrentUser())
	    				->GetResponseData(UploadServices::GET_UPLOAD_BY_STOR_FNAME, array(
	    					UploadServices::PARAM_STOR_FNAME => $destfname));
	    	if($upload === null) {
	    		throw new RuntimeException("Impossible de retrouver l'identifiant de l'upload.", ServiceResponse::ERR_UP_FILESYSTEM);
	    	}
	    	// retrieve upload's id
	    	$id = $upload->GetId();
		    // create service response
		    $response = new ServiceResponse($id);
		} catch (RuntimeException $e) {
		    $response = new ServiceResponse("", $e->getCode(), $e->getMessage());
		}
		// return response
		return $response;
	}

	private function __service_download($post) {
		// initialize response with null
		$response = null;	
		// simple upload without check for now...
		try {
			// retrieve upload record 
			$upload = $this->kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices($this->kernel->GetCurrentUser())
						->GetResponseData(UploadServices::GET_UPLOAD_BY_ID, array(
							UploadServices::PARAM_ID => $post[Services::PPARAM_PARAMS][Services::PKEY_ID]));
			// check if valid upload is recieved
			if($upload === null) {
				// throw service exception
				throw new RuntimeException("Le service n'a pas réussi à retrouver le fichier dans la base.", ServiceResponse::ERR_DL_MISSING_ID);
			}   
			// set url
			$url = sprintf("%s%s",Services::UPLOAD_FOLDER,$upload->GetStorageFilename());
			// set src
			$src = sprintf("%s%s", 
						rtrim($this->kernel->SettingValue(SettingsManager::KEY_DOLETIC_DIR)," /"),
						$url);
			// set basename
			$basename = $upload->GetFilename(); 
			// if all goes as planned, send file directly and stop script execution
			if (!file_exists($src)) {
				// throw service exception
				throw new RuntimeException("Le service n'a pas réussi à retrouver le fichier sur le disque.", ServiceResponse::ERR_DL_MISSING_FILE);
			}
			// Everything is ok build and send link
			$response = new ServiceResponse(array("url" => $url, "basename" => $basename));
		} catch (RuntimeException $e) {
		    $response = new ServiceResponse("", $e->getCode(), $e->getMessage());
		}
		// return response if no download
		return $response;
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
			if($udata === null) {
				throw new RuntimeException("Echec de récupération des données de l'utilisateur courant", ServiceResponse::ERR_SERVICE_FAILED);
			}
			// if user data avatar id is not 0
			if($udata->GetAvatarId() != 0) {
				$result = $this->kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices($this->kernel->GetCurrentUser())
							->GetResponseData(UploadServices::DELETE_OWNER_CHECK, array(
								UploadServices::PARAM_ID => $udata->GetAvatarId()));	
				if($result === null) {
					throw new RuntimeException("Echec de suppression de l'avatar courant", ServiceResponse::ERR_SERVICE_FAILED);
				}
			}
			// -- add new avatar
			$result = $this->kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($this->kernel->GetCurrentUser())
						->GetResponseData(UserDataServices::UPDATE_AVATAR, $post[Services::PPARAM_PARAMS]);
			// -- treat result
			if($result === null) {
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
			if($udata === null) {
				throw new RuntimeException("Echec de récupération des données de l'utilisateur courant", ServiceResponse::ERR_SERVICE_FAILED);
			}
			// if avatar id is not 0 => meaning not default avatar
			if($udata->GetAvatarId() != 0) {
				$avatar = $this->kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices($this->kernel->GetCurrentUser())
						->GetResponseData(UploadServices::GET_UPLOAD_BY_ID, array(
							UploadServices::PARAM_ID => $udata->GetAvatarId()));	
				if($avatar === null) {
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
			if($study === null) {
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
				if($upload === null) {
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
