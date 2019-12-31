<?php

define ('ENVIRONMENT','development');

switch (ENVIRONMENT) {
	case 'development':
		define('HOSTNAME', 'http://'.$_SERVER['HTTP_HOST']);
		define('DB_HOST','devmysql');
		define('DB_ROOT_USER','root');
		define('DB_ROOT_PASSWORD','');
		define('DB_SCHEME','mydb');

		define('DB_USER','root');
		define('DB_PASSWORD', '');
		break;
	case 'production':
		break;
}
