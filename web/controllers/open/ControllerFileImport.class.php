<?php

class ControllerFileImport extends Controller {
	protected $template = 'pages/open/file_import.twig';
        
	public function execute (Session $session = null, $uri, $post, $get, $files) {
                try {   
                        $this->importedFileName = $session->get('import-file-name');
                        if (array_key_exists('confirmed', $get)) {
                                $dataSource = $session->get('import-file');
                                if (!isset($dataSource)) {
                                        $this->setRedirect('/');
                                }
                                $fileType = Helper::getFileType($this->importedFileName);
                                
                                $programNumberBeforeImport = ServiceLivetvProgram::getCount();
                                $scheduleNumberBeforeImport = ServiceLivetvSchedule::getCount();
                                
                                ParserAdapter::import($fileType, $dataSource);
                                
                                $programNumberAfterImport = ServiceLivetvProgram::getCount();
                                $scheduleNumberAfterImport = ServiceLivetvSchedule::getCount();
                                
                                $this->programNumberImported = $programNumberAfterImport - $programNumberBeforeImport;
                                $this->scheduleNumberImported = $scheduleNumberAfterImport - $scheduleNumberBeforeImport;
                                $this->template = 'pages/open/import_success.twig';
                        }
                } catch ( Exception $e ) {
                        Prompt::create($session, $e->getMessage(), 'alert');
                        Log::add('Exception while importing: '.$e->getMessage());
                }
	}
        
	public function authorize() {
		return true;
		// always authorized
	}
}