<?php

include('config/config.php');
include('config/autoloader.php');
include('config/routes.php');

Log::start();

try {
	DB::init(DB_HOST, DB_SCHEME, DB_USER, DB_PASSWORD);

	// resolve intended controller name from URI
	$controllerName = Router::getControllerName($_SERVER['REQUEST_URI'], ROUTES);

	if (!class_exists($controllerName)) {
		Log::add('Class does not exists => 404');
		$controllerName = 'ControllerError404';
	}

        // load or start a session
        if (array_key_exists(Session::$cookie_name, $_COOKIE)) {
                $session = new Session($_COOKIE[Session::$cookie_name]);
        } else {
                $session = Session::start($_SERVER);
                Log::Add('New session started with cookie '.$session->session_id);
        }
        setcookie(Session::$cookie_name, $session->session_id, time()+Session::$lifetime, '/');

        $controller = new $controllerName($session, $_SERVER['REQUEST_URI'],$_POST,$_GET,$_FILES,$_SERVER['HTTP_HOST']);

	$controller->output($session);
} catch ( Exception $e ) {
	log::add('Main score exception: '.$e->getMessage());
	$controller->output($session);
}

error_log(Log::get());
