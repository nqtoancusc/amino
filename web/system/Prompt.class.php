<?php


class Prompt {
	static $types = array('secondary','primary','success','warning','alert' );
	public $message;
	public $type;

	public function __construct($message, $type) {
		$this->message = $message;
		$this->type = $type;
	}

	static function get(Session $session) {
		if ($prompt = $session->get('prompt')) {
			$session->drop('prompt');
			return $prompt;
		}
	}

	static function create(Session $session,$message,$type = 'primary') {
		$prompt = new Prompt($message, $type);
		$session->set('prompt', $prompt);
	}

}