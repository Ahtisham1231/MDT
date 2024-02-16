<?php
################ USER LOG OUT ################
if (isset($_GET['logout']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
	$_SESSION = [];
	session_destroy();
	goHome();
}
$pageTitle = 'MDT - Home';
?>