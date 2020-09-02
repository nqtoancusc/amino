<?php

define ('ENVIRONMENT','development');

switch (ENVIRONMENT) {
	case 'development':
		define('HOSTNAME', 'YOUR_HOST_NAME');
		define('DB_HOST','YOUR_DB_HOST_NAME');
		define('DB_SCHEME','YOUR_DB_NAME');
		define('DB_USER','YOUR_DB_USER_NAME');
		define('DB_PASSWORD', 'YOUR_DB_PASWWORD');
		break;
	case 'production':
		break;
}
