<?php

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