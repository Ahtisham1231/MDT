<?php
/* ************* EMAIL VARS ************* */
	$host 		= MAILHOST;       		//  set smtp server host
	$port 		= 587;             		//  set smtp server port (usually 465 for ssl, 587 for tls)
	$SMTPAuth 	= true;         		//  whether to use SMTP authentication, false otherwise
	$username 	= MAILUSERNAME; 		//  set server email username for authentication
	$password 	= MAILPASSWORD; 		//  set server email password for authentication
	$mailfrom 	= MAILFROM;     		//  set who the message appears to be sent from (part of mail headers)
	$mailTo 	= h($email);     		//  will receive mail

if (isset($_POST['add_user']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

} else {
	
}

if (! ENV_KEY) {
	// LOCAL
	$mail->Host = $host;
	$mail->isSMTP();
	$mail->SMTPAuth = $SMTPAuth;
	$mail->Username = $username;
	$mail->Password = $password;
	$mail->SMTPSecure = 'tls';
	$mail->Port = $port;
	$mail->IsHTML(true);
	$mail->Body = $msg;
	$mail->CharSet = 'UTF-8';
} else {
	// SERVER
	$mail->IsHTML(true);
	$mail->Body = $msg;
	$mail->CharSet = 'UTF-8';
}

/* ************* SET EMAIL SUBJECT ************* */
if (isset($_POST['add_user']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
	
} else {
	
}
/*
$mail->SMTPOptions = array(
'ssl' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true
));
*/
$mail->setFrom($mailfrom , 'MDT');
$mail->addAddress($mailTo);
$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';
?>