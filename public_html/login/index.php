<?php
require_once '../../src/init.php';
require_once SRC . 'connect.php';
require_once SRC . 'controller/login.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="MDTAT" content="<?= generateAjaxToken(); ?>">
	<title><?= $pageTitle ?? ''; ?></title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="<?php getAssetLink('css-v*.css', 'css'); ?>">
</head>

<body>
	<div id="w_e_i_main_wrapp">

		<?php //getNavbar(); 
		?>

		<div class="">
			<form id="loginForm" method="post">
				<div>
					<h2>Login</h2>
					<div class="">
						<input placeholder="Username" maxlength="100" type="text" name="username" required>
					</div>
					<br>
					<div class="">
						<input placeholder="Password" maxlength="255" pattern=".{8,}" type="password" name="password" title="8 characters minimum" required>
					</div>
					<br>
					<input type="hidden" name="MDTLT" value="<?= generateLoginToken(); ?>" />
					<div class="">
						<div>
							<input class="" value="Login" type="submit" name="login">
						</div>
					</div>
				</div>
				<br>
			</form>
		</div>
		<div class="info_messages"><?= $_SESSION['msg'] ?? '';
									unset($_SESSION['msg']); ?></div>

		<?php getFooter(); ?>
	</div><!-- w_e_i_main_wrapp -->

	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</body>

</html>