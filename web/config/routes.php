<?php

define('ROUTES',
	array(
		'/^\/$/' => 'ControllerFileSelection',
                '/^\/import(\?confirmed)?$/' => 'ControllerFileImport'
	)
);