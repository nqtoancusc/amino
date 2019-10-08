<?php

class LoginRequiredException extends Exception { }
class AgentLoginRequiredException extends Exception { }
class CustomerException extends Exception {}
class ApiException extends Exception { }


abstract class Controller {
	private $_redirect = null;

	protected $_headers = array(
		'Content-Type' => 'text/html; charset=UTF-8'
	);
	protected $template = 'test.twig';

	public $prompt;					// this may be set for page rendering

	public $uri;
	public $path;
	public $host;					// HTTP host
	public $timezone = 'Europe/Helsinki';


	abstract function authorize();
	abstract function execute (Session $session = null, $uri, $post, $get, $files);

	public function __construct(Session $session = null, $uri, $post, $get, $files, $host) {
		$this->uri = $uri;
		$this->path = Router::getPath($uri);
		$this->host = $host;
                
		if ($session) {
			if ($contact_id = $session->get('contact-id')) {
				$this->currentContact = new Contact($contact_id);
			}
		}                
                
		$this->authorize();
		$this->execute($session, $uri, $post, $get, $files);
	}

	public function checkCustomer(Customer $customer) {
		if ($customer) {
			if ($customer->state == 'registered') {
				throw new CustomerException('registration is pending', 100);
			}
			if ($customer->state == 'locked') {
				throw new CustomerException('customer is locked', 101);
			}
		}
	}

	protected function reload() {
		$this->setRedirect($this->uri);
	}

	protected function setRedirect($url) {
		$this->_redirect = $url;
	}

	public function getRedirect() {
		return $this->_redirect;
	}

	public function output(Session $session = null) {
		if ($this->_redirect) {
			header('Location: '.$this->_redirect);
			return;
		}

		//Dictionary::load('fi');
		if ($session) {
			if ($prompt = Prompt::get($session)) {
				// translate the
				$prompt->message = $prompt->message; // Dictionary::get($prompt->message.'.prompt');
				$this->prompt = $prompt;
			}
		}


		$renderer = new Renderer($this);
		$html = $renderer->getHtml($this->template, $this->timezone);

		$this->_headers['Content-Length'] = strlen($html);
		foreach ($this->_headers as $k=>$v) {
			header($k.': '.$v, true);
		}

		echo $html;
	}

	public function __toString() {
		return print_r($this,true);
	}

}
