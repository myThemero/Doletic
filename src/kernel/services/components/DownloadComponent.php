<?php

require_once "services/components/UploadComponent.php";
require_once "services/components/ServiceResponse.php";

class DownloadComponent {

	public static function execute($kernel, $uploadId) {
		// initialize response with null
		$response = null;	
		// simple upload without check for now...
		try {
			// retrieve upload record 
			$upload = $kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
						->GetResponseData(UploadServices::GET_UPLOAD_BY_ID, array(
							UploadServices::PARAM_ID => $uploadId));
			// check if valid upload is recieved
			if(!isset($upload)) {
				// throw service exception
				throw new RuntimeException("Le service n'a pas réussi à retrouver le fichier dans la base.", ServiceResponse::ERR_DL_MISSING_ID);
			}   
			// set url
			$url = sprintf("%s%s",UploadComponent::UPLOAD_FOLDER,$upload->GetStorageFilename());
			// set src
			$src = sprintf("%s%s", 
						rtrim($kernel->SettingValue(SettingsManager::KEY_DOLETIC_DIR)," /"),
						$url);
			// set basename
			$basename = $upload->GetFilename(); 
			// if all goes as planned, send file directly and stop script execution
			if (!file_exists($src)) {
				// throw service exception
				throw new RuntimeException("Le service n'a pas réussi à retrouver le fichier sur le disque.", ServiceResponse::ERR_DL_MISSING_FILE);
			}
			// Everything is ok build and send link and log in kernel
			$response = new ServiceResponse(array("url" => $url, "basename" => $basename));
			// Log in kernel
			$kernel->LogInfo(get_class(), "User '".$kernel->GetCurrentUser()->GetUsername()."' downloaded '".$url."' successfully.");
		} catch (RuntimeException $e) {
		    $response = new ServiceResponse("", $e->getCode(), $e->getMessage());
		}
		// return response if no download
		return $response;
	}
}