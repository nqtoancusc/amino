<?php



class Router {
	
	static function getControllerName($uri, $routemap) {
		log::add('Resolving controller for URI: '.$uri);
		foreach ($routemap as $pattern => $className) {
			if (preg_match($pattern, $uri)) {
				log::add('controller is '.$className.' (matching pattern: '.$pattern.')');
				return $className;
			}
		}
		log::add('No matching route was found');
	}
	
	static function getPath($uri) {
		$uri = str_replace('%2F', chr(1), $uri);
		if ($f = strpos ( $uri , '?' )) {
			$path = explode ( '/', urldecode( substr ( $uri, 0, $f ) ) );
		} else {
			$path = explode ( '/', urldecode( $uri ) );
		}
		foreach ($path as $i=>$p) {
			$path[$i] = str_replace(chr(1),'/',$p);
		}
		return $path;
	}
	
}

