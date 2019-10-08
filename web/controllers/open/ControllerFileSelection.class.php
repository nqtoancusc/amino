<?php

class ControllerFileSelection extends Controller {
	protected $template = 'pages/open/file_selection.twig';
        
	public function execute (Session $session = null, $uri, $post, $get, $files) {
                try {
                        if ($files) {
                                if (!$this->isSupportedFile($files['upload']['name'])) {
                                        throw new Exception('Invalid file type');
                                }
                                $tempFile = tempnam(sys_get_temp_dir(), 'import');
                                Log::add('Temp file = '.$tempFile);
                                move_uploaded_file($files['upload']['tmp_name'], $tempFile);
				$session->set('import-file', $tempFile);
                                $session->set('import-file-name', $files['upload']['name']);
                                unset($tempFile);
                                $this->setRedirect('/import');
                        }
                } catch ( Exception $e ) {
                        Prompt::create($session, $e->getMessage(), 'alert');
                        Log::add('Exception while loading: '.$e->getMessage());
                }
	}
        
	private function isSupportedFile(string $fileName) {
		$fileType = Helper::getFileType($fileName);
                return in_array($fileType, array('xml','csv','json'));
	}
        
	public function authorize() {
		return true;
		// always authorized
	}
}