<?php

require_once "objects/phpword/PHPWord.php";

class DocumentProcessor {

	// -- consts
	const RESULT_STATUS = "status";
	const RESULT_DATA = "data";
	// --- file types
	const TYPE_WORD 	= "word";
	const TYPE_TEXT 	= "text";
	// --- document processor errors
	const ERROR_MKDIR_FAILED 		= "Can't make user tmp folder.";
	const ERROR_COPY_FAILED 		= "Can't copy template to user tmp folder.";
	const ERROR_SUBSTITUTION_FAILED = "Can't substitute in file.";
	// -- attributes
	private $kernel;

	// -- functions

	public function __construct($kernel) {
		$this->kernel = $kernel;
	}

	public function GenerateFromTemplates($basename, $templates, $dictionary, $type) {
		// create user tmp dir variable
		$user_tmp_dir = sprintf("%s/tmp/%s/",
								rtrim($this->kernel->SettingValue(SettingsManager::KEY_DOLETIC_DIR)," /"),
								$this->kernel->GetCurrentUser()->GetId());
		// make user directory if inexistant
		if(!is_dir($user_tmp_dir)) {
			if(!mkdir($user_tmp_dir)) {
				return $this->__make_result(false, DocumentProcessor::ERROR_MKDIR_FAILED);
			}
		}
		// build filenames
		$filenames = array();
		foreach ($templates as $template) {
			array_push($filenames, $user_tmp_dir.end(explode('/', $template)));
		}
		// copy templates in work directory
		foreach ($filenames as $filename) {
			if(!copy($template, $filename)) {
				return $this->__make_result(false, DocumentProcessor::ERROR_COPY_FAILED);
			}	
		}
		// substitute words in the copy of the file using given dictionary
		foreach ($filenames as $filename) {
			if(!$this->__substitute($filename, $dictionary, $type)) {
				return $this->__make_result(false, DocumentProcessor::ERROR_SUBSTITUTION_FAILED);
			}
		}
		$archive_name = str_replace(' ', '_', $basename).'.zip';
		$zipname = $user_tmp_dir.$archive_name;
		// create a zip archive using files
		$this->__zip_files($zipname, $filenames);
		// return relative server path for archive to enable downloading it
		return $this->__make_result(true, sprintf("/tmp/%s/%s", $this->kernel->GetCurrentUser()->GetId(), $archive_name));
	}

# PROTECTED & PRIVATE ##############################################################

	private function __make_result($success, $data) {
		return array(DocumentProcessor::RESULT_STATUS => $success, DocumentProcessor::RESULT_DATA => $data);
	}

	private function __zip_files($zipname, $files) {
		// create a zip archive instance
		$zip = new ZipArchive();
		// open archive
		if($zip->open($zipname, ZipArchive::CREATE) !== true) {
			return false;
		}
		// add files to archive
		foreach ($files as $file) {
			$zip->addFile($file, end(explode('/',$file)));
		}
		// close archive
		$zip->close();
	}

	private function __substitute($filename, $dictionary, $type) {
		$ok = true;
		switch ($type) {
			case DocumentProcessor::TYPE_TEXT:
				$this->__substitute_text($filename, $dictionary);
				break;
			case DocumentProcessor::TYPE_WORD:
				$this->__substitute_word($filename, $dictionary);
				break;
			default:
				$ok = false;
				break;
		}
		return $ok;
	}

	private function __substitute_text($filename, $dictionary) {
		// read file content
		$content = file_get_contents($filename);
		// execute substitution for each word
		foreach ($dictionary as $target => $replacement) {
			$content = str_replace($target, $replacement, $content);
		}
		// write file content
		file_put_contents($filename, $content);
	}

	private function __substitute_word($filename, $dictionary) {
		// create a PHPWord instance
		$phpword = new PHPWord();
		// create a phpword template
		$template = $phpword->loadTemplate($filename);
		// execute substitution for each word
		foreach ($dictionary as $target => $replacement) {
			$template->setValue($target, $replacement);
		}
		// write file with replaced content
		$template->save($filename);
	}

}