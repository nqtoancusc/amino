<?php

/**
 *
 * Class for rendering Controller with Twig
 *
 *
 */


class Renderer {
	var $data;
	var $timezone;

	public function __construct($data) {
		$this->data = $data;
	}


	public function getHtml($template,$timezone, Mailing $mailing = null) {
		$this->timezone = $timezone;

		$loader = new Twig_Loader_Filesystem('./html/templates/');
		$twig = new Twig_Environment($loader, array(
			//'cache' => TWIG_TEMPLATE_CACHE_DIR,
		));

		$twig->addFilter(
			new Twig_Filter('location_registration', function($registration) {
				return Location::$registrations[$registration];
			})
		);

		$twig->addFilter(
			new Twig_Filter('perc', function($percetange) {
				return '% '.number_format(100*$percetange,2,',','.');
			})
		);

		$twig->addFilter(
			// conver monthly rate to annual
			new Twig_Filter('m_to_a', function($monthly) {
				return pow(1+$monthly,12) - 1;
			})
		);


		$twig->addFilter(
			// conver monthly rate to annual
			new Twig_Filter('states_of_status', function($status) {
				return ProductLocation::getStatesForStatus($status);
			})
		);


		$twig->addFilter(
			new Twig_Filter('money', function ($amount,$currency = 'EUR', $showSign=false) {
				switch ($currency) {
					case 'EUR':
						return ($showSign ? $amount > 0 ? '+' : '' : null).number_format($amount/100,2,',','.').' €';
						break;
				}
			})
		);

		$twig->addFilter(new Twig_Filter('dt',array($this, 'datetimeFormat')));
		
		$twig->addFilter(new Twig_Filter('df',array($this, 'dateFormat')));

		$twig->addFunction(
			new Twig_Function('dt', function() {
				return forward_static_call_array( array('Renderer', 'dateFormat'),  func_get_args() );
			})
		);

		$twig->addFunction(
			new Twig_Function('t', function() {
				return forward_static_call_array( array('Dictionary', 'get'),  func_get_args() );
			})
		);

		$twig->addFunction(
			new Twig_Function('l', function() {
				return forward_static_call_array( array('Tr', 't'),  func_get_args() );
			})
		);
			
		$twig->addFunction(
			new Twig_Function('lt', function() {
				return forward_static_call_array( array('Helper', 'getLocalTime'),  func_get_args() );
			})
		);
			
		$twig->addFunction(
			new Twig_Function('d2h', function() {
				return forward_static_call_array( array('Helper', 'convertDecToHex'),  func_get_args() );
			})
		);
			
		$twig->addFunction(
			new Twig_Function('h2d', function() {
				return forward_static_call_array( array('Helper', 'convertHexToDec'),  func_get_args() );
			})
		);
		
		if (isset($template)) {	 
			return $twig->render($template, array( 'data' => $this->data ));
		}
		
		if (isset($mailing)) {
			$html =
				    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				    <html xmlns="http://www.w3.org/1999/xhtml">
				     <head>
				      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				      <title>'.$mailing->subject.'</title>
				      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
				    </head>
				    <body>{% block body %}
					    {% autoescape false %}'.$mailing->content.'{% endautoescape %}
				    {% endblock %}</body>
				    </html>';
			
			$t = $twig->createTemplate( $html );
			return $t->render( array( 'data' => $this->data ) );
		}
	}


	public function datetimeFormat($utc = null, $format=null) {

		if (!$format) {
			$format = 'j.n.Y H:i';
		}

		if ($utc) {
			$date = new DateTime($utc, new DateTimeZone('GMT'));
			$date->setTimezone(new DateTimeZone($this->timezone));
		} else {
			$date = new DateTime('now', new DateTimeZone($this->timezone));
		}
		return $date->format($format);
	}
	
	public function dateFormat($utc = null, $format=null) {

		if (!$format) {
			$format = 'j.n.Y';
		}

		if ($utc) {
			$date = new DateTime($utc, new DateTimeZone('GMT'));
			$date->setTimezone(new DateTimeZone($this->timezone));
		} else {
			$date = new DateTime('now', new DateTimeZone($this->timezone));
		}
		return $date->format($format);
	}

}