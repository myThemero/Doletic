<?php

require_once "services/components/ServiceResponse.php";

class UploadComponent
{
    // -- consts
    // --- some file size consts
    const SIZE_2MO = 2097152;
    const SIZE_4MO = 4194304;
    const SIZE_8MO = 8388608;
    // --- upload related consts
    const KEY_FILE = 'file';
    const KEY_ERROR = 'error';
    const KEY_SIZE = 'size';
    const KEY_TMP_NAME = 'tmp_name';
    const KEY_NAME = 'name';
    // allowed files dictionary
    const FILE_SPECS = array(
        // format 'mime_type' 														=> array('ext'	, max_size) (max_size en octets)
        'image/jpeg' => array('jpeg', UploadComponent::SIZE_2MO), //(2Mo max)
        'image/png' => array('png', UploadComponent::SIZE_2MO), //(2Mo max)
        'application/pdf' => array('pdf', UploadComponent::SIZE_8MO), //(8Mo max)
        'application/zip' => array('zip', UploadComponent::SIZE_4MO), //(4Mo max)
        'application/x-gzip' => array('gz', UploadComponent::SIZE_4MO), //(4Mo max)
        'text/x-tex' => array('tex', UploadComponent::SIZE_2MO), //(2Mo max)
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => array('docx', UploadComponent::SIZE_8MO)  //(8Mo max)
    );
    // --- filesystem related consts
    const UPLOAD_FOLDER = '/uploads';

    public static function execute($kernel)
    {
        // initialize response with null
        $response = null;
        // simple upload without check for now...
        try {

            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (!isset($_FILES[UploadComponent::KEY_FILE][UploadComponent::KEY_ERROR]) ||
                is_array($_FILES[UploadComponent::KEY_FILE][UploadComponent::KEY_ERROR])
            ) {
                throw new RuntimeException('Paramètres invalides', ServiceResponse::ERR_UP_INVALID_PARAMS);
            }

            // Check $_FILES['upfile']['error'] value. ====> PHP . INI related errors
            switch ($_FILES[UploadComponent::KEY_FILE][UploadComponent::KEY_ERROR]) {
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

            // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
            // Check MIME Type by yourself.
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES[UploadComponent::KEY_FILE][UploadComponent::KEY_TMP_NAME]);
            if (!array_key_exists($mime, UploadComponent::FILE_SPECS)) {
                throw new RuntimeException('Format de fichier interdit.', ServiceResponse::ERR_UP_FORBID_FORMAT);
            }

            $file_spec = UploadComponent::FILE_SPECS[$mime];

            // You should also check filesize here.
            if ($_FILES[UploadComponent::KEY_FILE][UploadComponent::KEY_SIZE] > $file_spec[1]) {
                throw new RuntimeException('Taille limite de fichier dépassée.', ServiceResponse::ERR_UP_FILE_TOO_BIG);
            }

            // retrieve filename
            $filename = $_FILES[UploadComponent::KEY_FILE][UploadComponent::KEY_NAME];
            // Obtain safe unique name from its binary data plus date part.
            $destfname = sprintf('/%s_%s.%s',
                sha1_file($_FILES[UploadComponent::KEY_FILE][UploadComponent::KEY_TMP_NAME]),
                date('Y_m_d_H_i_s'),
                $file_spec[0]);
            // full dest (prepend absolute path)
            $dest = rtrim($kernel->SettingValue(SettingsManager::KEY_DOLETIC_DIR), " /") . UploadComponent::UPLOAD_FOLDER . $destfname;
            // move uploaded file and throw error if it fails
            if (!move_uploaded_file(
                $_FILES[UploadComponent::KEY_FILE][UploadComponent::KEY_TMP_NAME],
                $dest
            )
            ) {
                throw new RuntimeException('Echec du déplacement du fichier reçu.', ServiceResponse::ERR_UP_FILESYSTEM);
            }

            $id = null;
            $upload_params = array(
                UploadServices::PARAM_USER_ID => $kernel->GetCurrentUser()->GetId(),
                UploadServices::PARAM_FNAME => $filename,
                UploadServices::PARAM_STOR_FNAME => $destfname);
            // write upload in database and retrieve its id
            if (!$kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
                ->GetResponseData(UploadServices::INSERT, $upload_params)
            ) {
                throw new RuntimeException("Erreur d'enregistrement dans la base de données.", ServiceResponse::ERR_UP_FILESYSTEM);
            }
            // retrieve user
            $upload = $kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
                ->GetResponseData(UploadServices::GET_UPLOAD_BY_STOR_FNAME, array(
                    UploadServices::PARAM_STOR_FNAME => $destfname));
            if (!isset($upload)) {
                throw new RuntimeException("Impossible de retrouver l'identifiant de l'upload.", ServiceResponse::ERR_UP_FILESYSTEM);
            }
            // retrieve upload's id
            $id = $upload->GetId();
            // create service response
            $response = new ServiceResponse($id);
            // Log in kernel
            $kernel->LogInfo(get_class(), "User '" . $kernel->GetCurrentUser()->GetUsername() . "' uploaded '" . $filename . "' as '" . $destfname . "' successfully.");
        } catch (RuntimeException $e) {
            $response = new ServiceResponse("", $e->getCode(), $e->getMessage());
        }
        // return response
        return $response;
    }
}