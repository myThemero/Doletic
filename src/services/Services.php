<?php 

require_once "DoleticKernel.php";

class ServiceResponse implements \JsonSerializable {
	
	// -- consts
	// --- service errors
	const ERR_NO_ERROR 			= 0x00;
	const ERR_MISSING_PARAMS	= 0x01;
	const ERR_MISSING_OBJ 		= 0x02;
	const ERR_MISSING_ACT 		= 0x03;
	const ERR_MISSING_SERVICE   = 0x04;
	const ERR_SERVICE_FAILED 	= 0x05;
	// --- upload errors
	const ERR_UP_INVALID_PARAMS = 0x10;
	const ERR_UP_NO_FILE 		= 0x11;
	const ERR_UP_FILE_TOO_BIG 	= 0x12;
	const ERR_UP_UNKNOWN		= 0x13;
	const ERR_UP_FORBID_FORMAT	= 0x14;
	const ERR_UP_FILESYSTEM		= 0x15;

	// -- attributes
	private $code;
	private $err_string;
	private $data;

	// -- functions
	/**
	 *
	 */
	public function __construct($responseData, $responseCode = ServiceResponse::ERR_NO_ERROR, $responseErrString = "") {
		$this->code = $responseCode;
		$this->err_string = $responseErrString;
		$this->data = $responseData;
	}
	/**
	 *
	 */
	public function jsonSerialize() {
       return [
           'code' => $this->code,
           'error' => $this->err_string,
           'data' => json_encode($this->data)];
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
	const SERVICE_UPLOAD 	= "upload";
	// --- params keys
	const PKEY_FNAME		= "filename";
	// --- upload file keys
	const UKEY_FILE			= "file";
	const UKEY_ERROR		= "error";
	const UKEY_SIZE			= "size";
	const UKEY_TMP_NAME		= "tmp_name";
	const UKEY_NAME			= "name";

	// -- attributes
	private $kernel;

	// -- functions

	public function __construct(&$kernel) {
		$this->kernel = $kernel;
	}

// --------------------------------- GLOBAL Services entry points ----------------------------------------------------------

	public function Response($post = array()) {
		// first check check if object requested is service for high-level services
		if($post[Services::PPARAM_OBJ] === Services::OBJ_SERVICE) {
			// find which service is called
			if($post[Services::PPARAM_ACT] === Services::SERVICE_UPLOAD) {
				$response = __service_upload($post);
			} else {
				$response = new ServiceResponse("", ServiceResponse::ERR_MISSING_SERVICE, "Service is missing.");
			}
		} // else an atomic service is called -> redirect call to object specific services
		else {
			// declare response var
			$response = null;
			// retreive db object
			$obj = parent::kernel()->GetDBObject($post[Services::PPARAM_OBJ]);
			if($obj != null) {
				// retreive response data
				$data = $obj->GetServices()->GetResponseData($post[Services::PPARAM_ACT], $post[Services::PPARAM_PARAMS]);
				if($data != null) {
					$response = new ServiceResponse($data);
				} else {
					$response = new ServiceResponse("", ServiceResponse::ERR_MISSING_ACT, "Action is missing.");	
				}
			} else {
				$response = new ServiceResponse("", ServiceResponse::ERR_MISSING_OBJ, "Object is missing.");
			}
		}
		// return response
		return json_encode($response);
	}
	/**
	 *	Return service default response -> it's always an error linked with query parameters
	 */
	public function DefaultResponse() {
		$response = new ServiceResponse("", ServiceResponse::ERR_MISSING_PARAMS, "Parameters (obj and/or act) are missing.");
		return json_encode($response);
	}

// --------------------------------- HIGH-LEVEL Services ---------------------------------------------------------------------

	/// \todo test upload service
	private function __service_upload($post) {
		// initialize response with null
		$response = null;
		// rebuild params
		$params = json_decode($post[Services::PPARAM_PARAMS]);
		$filename = null;
		if(array_key_exists(Services::PKEY_FNAME, $params)) {
			$filename = $params[Services::PKEY_FNAME];
		} else {
			$filename = "undefined";
		}
		
		// simple upload without check for now...
		try {
   
		    // Undefined | Multiple Files | $_FILES Corruption Attack
		    // If this request falls under any of them, treat it invalid.
		    if (
		        !isset($_FILES[Services::UKEY_FILE][Services::UKEY_ERROR]) ||
		        is_array($_FILES[Services::UKEY_FILE][Services::UKEY_ERROR])
		    ) {
		        throw new RuntimeException('Paramètres invalides', Services::ERR_UP_INVALID_PARAMS);
		    }

		    // Check $_FILES['upfile']['error'] value.
		    switch ($_FILES[Services::UKEY_FILE][Services::UKEY_ERROR]) {
		        case UPLOAD_ERR_OK:
		            break;
		        case UPLOAD_ERR_NO_FILE:
		            throw new RuntimeException('Aucun fichier envoyé.', Services::ERR_UP_NO_FILE);
		        case UPLOAD_ERR_INI_SIZE:
		        case UPLOAD_ERR_FORM_SIZE:
		            throw new RuntimeException('Taille limite de fichier dépassée.', Services::ERR_UP_FILE_TOO_BIG);
		        default:
		            throw new RuntimeException('Erreur inconnue.', Services::ERR_UP_UNKNOWN);
		    }

		    // You should also check filesize here.
		    if ($_FILES[Services::UKEY_FILE][Services::UKEY_SIZE] > 1000000) {
		        throw new RuntimeException('Taille limite de fichier dépassée.', Services::ERR_UP_FILE_TOO_BIG);
		    }

		    // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
		    // Check MIME Type by yourself.
		    $finfo = new finfo(FILEINFO_MIME_TYPE);
		    if (false === $ext = array_search(
		        $finfo->file($_FILES[Services::UKEY_FILE][Services::UKEY_TMP_NAME]),
		        array(
		            'jpg' => 'image/jpeg',
		            'png' => 'image/png',
		            'gif' => 'image/gif',
		        ),
		        true
		    )) {
		        throw new RuntimeException('Format de fichier interdit.', Services::ERR_UP_FORBID_FORMAT);
		    }

		    // Obtain safe unique name from its binary data plus date part.
		    $destfname = sprintf('/%s-%s.%s', 
		    	sha1_file($_FILES[Services::UKEY_FILE][Services::UKEY_TMP_NAME]), 
		    	date('Y-m-d'),
		    	$ext);
		    // full dest (prepend absolute path)
		    $dest = $this->kernel->SettingValue(SettingsManager::KEY_UPLOAD_DIR).$destfname;
		    // include ext to filename
		    $filename = explode('.', $filename)[0].$ext;

		    if (!move_uploaded_file(
		        $_FILES[Services::UKEY_FILE][Services::UKEY_TMP_NAME],
		        $dest
		    )) {
		        throw new RuntimeException('Echec du déplacement du fichier reçu.', Services::ERR_UP_FILESYSTEM);
		    }

		    $id = null;
		    // write upload in database and retrieve its id
		    if($this->kernel->GetDBObject(UploadDBObject::OBJ_NAME)
		    	->GetResponseData(UploadServices::INSERT, array(
		    			UploadServices::PARAM_USER_ID => $this->kernel->GetUserId(),
		    			UploadServices::PARAM_FNAME => $filename,
		    			UploadServices::PARAM_STOR_FNAME => $destfname))) 
		    {
		    	// retrieve user
		    	$upload = $this->kernel->GetDBObject(UploadDBObject::OBJ_NAME)
		    				->GetResponseData(UploadServices::GET_UPLOAD_BY_STOR_FNAME, array(
		    					UploadServices::PARAM_STOR_FNAME => $destfname));
		    	if($upload != null) {
		    		// retrieve upload's id
		    		$id = $upload->GetId();
		    	} else {
		    		throw new RuntimeException("Impossible de retrouver l'identifiant de l'upload.", Services::ERR_UP_FILESYSTEM);
		    	}
		    } else {
		    	throw new RuntimeException("Erreur d'enregistrement dans la base de données.", Services::ERR_UP_FILESYSTEM);
		    }

		    // create service response
		    $response = new ServiceResponse($id);

		} catch (RuntimeException $e) {

		    $response = new ServiceResponse("", $e->getCode(), $e->getMessage());
		}
		// return response
		return $response;
	}

}
