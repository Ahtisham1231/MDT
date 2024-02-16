<?php
isset($_SESSION['userIsLoggedIn']) ? redirectUserToPanel() : '';
$pageTitle = 'MDT - Login';

################ USER LOGIN ################
if (isset($_POST['login']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

	$v = new Validate();

	// 	check input presence
	$required = ['username', 'password', 'MDTLT'];
	foreach ($required as $key => $value) {
		if (!isset($_POST[$value])) {
			$v->SetPresenceError();
		}
	}

	if (!empty($v->getErrors())) {
		goLogin();
	} else {
		foreach ($_POST as $key => $value) {
			${$key} = trim($value);
		}
	}

	// 	check inputs
	$v->checkRequired($username, 'username');
	$v->checkRequired($password, 'password');
	$v->checkRequired($MDTLT, 'MDTLT');

	if (!empty($v->getErrors())) {
		$_SESSION['msg'] = 'Both fields are required!';
		goLogin();
	}
	// $password   = trim('admin786');
	// $hash   	= password_hash($password, PASSWORD_DEFAULT);
	// die($hash);

	// 	check if password matches DB record
	$sql =
		"
	SELECT
		U.id,
		U.username,
		U.password,
		UR.role,
		U.active
	FROM
		users as U
	LEFT JOIN
		user_roles AS UR
	ON
		U.role = UR.id
	WHERE
		U.username = :username
	";
	$result = $db->getRow($sql, ['username' => $username]);

	if (!$result || !password_verify($password, $result->password) || $_SESSION['loginToken'] !== $_POST['MDTLT'] || $result->active === 0) {

		$_SESSION['msg'] = 'Incorrect login credentials!';
		goLogin();
	} else {

		$_SESSION['userID'] 		= h($result->id);
		$_SESSION['userName']		= h($result->username);
		$_SESSION['userRole'] 		= h(ucfirst($result->role));
		$_SESSION['userActive'] 	= h($result->active);
		$_SESSION['userIP'] 		= h(getUserIP());

		$_SESSION['userIsLoggedIn'] = true;
		session_regenerate_id(true);
		redirectUserToPanel();
	}
}
