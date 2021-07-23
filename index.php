<?php


	define('ENVIRONMENT', 'development');

if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'development':
		//	error_reporting(E_ALL & ~E_DEPRECATED); // report errors
			error_reporting(E_ALL & E_DEPRECATED); // no report errors
			ini_set('display_errors', '1');
		break;
	
		case 'testing':
		case 'production':
			error_reporting(0);
		break;

		default:
			exit('The application environment is not set correctly.');
	}
}

require_once 'example.php';
?>