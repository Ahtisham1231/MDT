<?php
// ajax request token generator
function generateAjaxToken() {
	$token = bin2hex(random_bytes(32));
	$_SESSION['ajaxToken'] = $token;
	return $token;
}
// login token generator
function generateLoginToken() {
	$token = bin2hex(random_bytes(32));
	$_SESSION['loginToken'] = $token;
	return $token;
}
// registration token generator
function generateRegisterToken() {
	$token = bin2hex(random_bytes(32));
	$_SESSION['registerToken'] = $token;
	return $token;
}
// pass reset token generator
function generatePasswordResetToken() {
	$token = bin2hex(random_bytes(32));
	$_SESSION['passwordResetToken'] = $token;
	return $token;
}
// forgot pass token generator
function generateForgotPasswordToken() {
	$token = bin2hex(random_bytes(32));
	$_SESSION['forgotPasswordToken'] = $token;
	return $token;
}
// mail token generator
function generateMailToken() {
	$token = bin2hex(random_bytes(32));
	return $token;
}
// html escape
function h($string = '') {
	return htmlentities($string, ENT_QUOTES, 'UTF-8', false);
}
// test die
function die_r($v) {
	echo '<pre>';
	print_r($v);
	echo '</pre>';
	die;
}
// redirect to home page
function goHome() {
	header('Location: ' . MYSITE);
	die;
}
// redirect to login page
function goLogin() {
	header('Location: ' . MYSITE . 'login/');
	die;
}

/*
 * 	Automatically generate link for javascript and stylesheet assets
 * 	If there are multiple file versions, target the file with the latest modified date
 *
 * 	@param string  $file 	- name of the file
 * 	@param string  $folder 	- folder name
*/

function getAssetLink($file, $folder) {

	$file_paths = glob(PUBLIC_PATH . $folder . DS . $file);

	if (count($file_paths) > 1) {

		foreach ($file_paths as $file_path) {

			$key 			= basename($file_path);
			$value 			= filemtime($file_path);
			$files[$key] 	= time() - $value;
		}

		asort($files);
		$files 	= array_keys($files);
		$file 	= $files[0];

	} else {
		$file = basename(implode('', glob(PUBLIC_PATH . $folder . DS . $file)));
	}
	echo MYSITE . $folder . '/' . $file;
}

// return forwarded ip
function forwarded_ip() {

	$headers = [
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'HTTP_CLIENT_IP',
		'HTTP_X_CLUSTER_CLIENT_IP'
	];

	foreach ($headers as $header) {
		if (isset($_SERVER[$header])) {
			$ip_array = explode(',', $_SERVER[$header]);
			foreach ($ip_array as $ip) {
				$ip = trim($ip);
				if (is_valid_ipv6($ip)) {
					return $ip;
				} else if (is_valid_ipv4($ip)) {
					return $ip;
				}
			}
		}
	}
	return false;
}
// valid ipv4?
function is_valid_ipv4($ip) {
	if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
		return false;
	} else {
		return true;
	}
}
// valid ipv6?
function is_valid_ipv6($ip) {
	if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
		return false;
	} else {
		return true;
	}
}
// get user ip (forwarded or not)
function getUserIP() {
	$ip = forwarded_ip();
	if ($ip === false) {
		if (is_valid_ipv6($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		} else if (is_valid_ipv4($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		} else {
			$ip = 'not valid';
		}
	}
	return $ip;
}

/*
 * 	checks database for existing user by username
 * 	@param mixed $db - database connection
 * 	@param string $username - username
 *  @return boolean
*/
function userExists($db, $username) {

	$sql = "SELECT COUNT(id) FROM users WHERE username = :username";
	$result = $db->getColumn($sql, ['username' => $username]);

	if ($result) {
		return true;
	} else {
		return false;
	}
}
function getNavbar($pageTitle = '') {
	require_once INC . 'navbar.php';
}
function getFooter() {
	require_once INC . 'footer.php';
}
function redirectUserToPanel() {

	if (isAdmin()) {
		header('Location: ' . MYSITE . 'administrator/');
		die;
	} else if (isUser()) {
		header('Location: ' . MYSITE . 'user/');
		die;
	}
}
function userLoggedIn() {
	if (! isset($_SESSION['userIsLoggedIn'])) {
		goLogin();
	}
}
function isAdmin() {
	if ($_SESSION['userRole'] === 'Administrator') {
		return true;
	}
}
function isUser() {
	if ($_SESSION['userRole'] === 'User') {
		return true;
	}
}
function prettyArrayPrint($var) {
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}
?>