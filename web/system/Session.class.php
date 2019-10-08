<?php

/**
 * Session handler
 *
 *
 */


class Session {
	static $cookie_name = 'ec';
	static $lifetime = 86400;

	var $session_id;

	var $_data = array();
	var $_details = array();		// Landing data
	var $_dirty = false;

	public function __construct($session_id) {
		$this->session_id = $session_id;
		if (is_file($this->_getFileName())) {
			$this->_load();
		}
	}

	public function __destruct() {
		if ($this->_dirty) {
			Log::add('Saving dirty session data');
			$this->_save();
		}
	}

	public function clearData() {
		$this->_data = array();
		$this->_dirty = true;
	}

	public function set($key, $value) {
		$this->_data[$key] = $value;
		$this->_dirty = true;
	}

	public function drop($key) {
		if (array_key_exists($key, $this->_data)) {
			unset($this->_data[$key]);
			$this->_dirty = true;
		}
	}

	public function get($key) {
		if (array_key_exists($key, $this->_data)) {
			return $this->_data[$key];
		}
	}
	/**
	 *
	 *
	 * @param array $data
	 * @return Session
	 */
	static function start(array $data=null) {
		$session_id =  base_convert(bin2hex(openssl_random_pseudo_bytes(10)), 16, 36);
		$session = new Session($session_id);
		if ($data) {
			$obj = new stdClass();
			$obj->host 				= $data['HTTP_HOST'];
			$obj->user_agent 		= $data['HTTP_USER_AGENT'];
			$obj->accept_language 	= $data['HTTP_ACCEPT_LANGUAGE'];
			$obj->remote_addr		= $data['REMOTE_ADDR'];
			$obj->remote_port		= $data['REMOTE_PORT'];
			$obj->landing_uri		= $data['REQUEST_URI'];
			$session->_details = $obj;
			$session->_dirty = true;
		}
		return $session;
	}

	private function _getFileName() {
		return '/tmp/'.$this->session_id;
	}

	private function _save() {
		file_put_contents($this->_getFileName(), serialize(array($this->_data,$this->_details)));
	}

	private function _load() {
		list( $this->_data, $this->_details ) = unserialize(file_get_contents($this->_getFileName()));
	}

}

