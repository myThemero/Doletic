<?php 

class ServiceResponse implements \JsonSerializable {
	
	// -- consts
	const ERR_NO_ERROR 			= 0x00;
	const ERR_MISSING_PARAMS	= 0x01;
	const ERR_MISSING_OBJ 		= 0x02;
	const ERR_MISSING_ACT 		= 0x03;
	const ERR_MISSING_SERVICE   = 0x04;
	const ERR_SERVICE_FAILED 	= 0x05;

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
	// --- services internal consts
	const OBJ_SERVICE = "service";
	// --- post attributes required
	const PPARAM_OBJ  = "obj"; 
	const PPARAM_ACT  = "act"; 
	const PPARAM_PARAMS  = "params"; 
	// --- high-level services
	const SERVICE_UPLOAD = "upload";

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

	private function __service_upload($post) {
		/// \todo implement here	
	}

}
