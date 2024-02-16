<?php
require_once DIRNAME(DIRNAME(__FILE__)) . '/config/config.php';
require_once 'functions.php';

##### set up session settings #####
ini_set('session.name', 'MDTID');
ini_set('session.use_cookies', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
//ini_set('session.cookie_secure', '1');
ini_set('error_log', SRC . 'error.log');

#####  autoload classes #####
spl_autoload_register(function ($class) {
	$ClassFileName = str_replace('\\', DS, $class) . '.php';
	if (file_exists(CLASSES . $ClassFileName)) {
		require_once CLASSES . $ClassFileName;
	}
});

//	start session
session_start();

//	start output buffering with gzip
//ob_start('ob_gzhandler');
