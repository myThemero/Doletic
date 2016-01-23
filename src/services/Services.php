<?php 

class ServiceResponse implements \JsonSerializable {
	
	// -- consts
	const ERR_NO_ERROR 			= 0x00;
	const ERR_MISSING_PARAMS	= 0x01;
	const ERR_MISSING_OBJ 		= 0x02;
	const ERR_MISSING_ACT 		= 0x03;
	const ERR_SERVICE_FAILED 	= 0x04;

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
	const PPARAM_OBJ  = "obj"; 
	const PPARAM_ACT  = "act"; 
	const PPARAM_PARAMS  = "params"; 

	// -- attributes
	private $kernel;

	// -- functions

	public function __construct(&$kernel) {
		$this->kernel = $kernel;
	}

	public function Response($post = array()) {
		// declare response var
		$response = null;
		// retreive db object
		$obj = $this->kernel()->GetDBObject($post[Services::PPARAM_OBJ]);
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
		// return response
		return $response;
	}

	public function DefaultResponse() {
		return new ServiceResponse("", ServiceResponse::ERR_MISSING_PARAMS, "Parameters (obj and/or act) are missing.");
	}

}
