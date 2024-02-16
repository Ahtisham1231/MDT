<?php
// phpinfo();
/*
	"0" FOR LOCAL ENVIROMENT (DEVELOPMENT)
	"1" FOR SERVER ENVIROMENT (PRODUCTION)
*/

define('ENV_KEY', 0);

####################### FILE SYSTEM PATH ###########################

define('DS', DIRECTORY_SEPARATOR);
define('CONFIG', dirname(__FILE__) . DS);
define('PROJECT', dirname(CONFIG) . DS);
define('PUBLIC_PATH', PROJECT . 'public_html' . DS);
define('SRC', PROJECT . 'src' . DS);
define('CLASSES', SRC . 'classes' . DS);
define('CONT', SRC . 'controller' . DS);
define('AJAX', SRC . 'controller' . DS . 'ajax' . DS);
define('INC', SRC . 'inc' . DS);
// die(PROJECT);
####################################################################
####################### SERVER ROOT URL ############################
if (!ENV_KEY) {
	// LOCAL

	define('MYSITE', 'http://localhost/' . basename(PROJECT) . '/' . basename(PUBLIC_PATH) . '/');
	// die(MYSITE);
} else {
	// SERVER

	define('MYSITE', '');
}

####################################################################
####################### DATABASE CONNECTION ########################
if (!ENV_KEY) {
	// LOCAL

	define('DBHOST', '127.0.0.1');
	define('DBNAME', 'mdt');
	define('DBUSERNAME', 'root');
	define('DBPASS', 'Npassword!123');
} else {
	// SERVER
	define('DBHOST', '127.0.0.1');
	define('DBNAME', '');
	define('DBUSERNAME', '');
	define('DBPASS', '');
}
#####################################################################
####################### PHPMAILER CLASS SETTINGS ####################
if (!ENV_KEY) {
	// LOCAL

	define('MAILHOST', 'smtp.gmail.com');
	define('MAILUSERNAME', '');
	define('MAILPASSWORD', '');
	define('MAILFROM', '');
	define('MAILCONTACT', '');
} else {
	// SERVER
	define('MAILHOST', 'smtp.gmail.com');
	define('MAILUSERNAME', '');
	define('MAILPASSWORD', '');
	define('MAILFROM', '');
	define('MAILCONTACT', '');
}
