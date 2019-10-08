<?php

// System
spl_autoload_register(function($cn) {
	$targetName = $cn.'.class.php';
	$app_dirs = array(
		'./controllers/',
		'./models/',
		'./system/'
	);
	foreach($app_dirs as $dir) {
		$it = new RecursiveDirectoryIterator($dir);
		foreach(new RecursiveIteratorIterator($it) as $file) {
			if ($file->getFilename() == $targetName) {
				require_once($file);
				return true;
			}
		}
	}
});

// twig
spl_autoload_register(function($cn) {
	$parts = explode('_',$cn);
	$filename = './lib/'.implode('/',$parts).'.php';
	if (is_file($filename)) {
		require_once($filename);
		return true;
	}
});

// PHP Spreadsheet
spl_autoload_register(function($cn) {
	$parts = explode('\\',$cn);
	$filename = './lib/'.implode('/',$parts).'.php';
	if (is_file($filename)) {
		require_once($filename);
		return true;
	}
});
