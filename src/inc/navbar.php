<nav id="main_nav_wrapp" class="navbar navbar-expand-sm  navbar-dark justify-content-end">
	<a class="navbar-brand" href=""> Logo </a>
	<?php if (isset($_SESSION['userIsLoggedIn'])) : ?>
		<div class="collapse navbar-collapse flex-grow-0" id="navbarSupportedContent">
			<ul class="navbar-nav text-right">

				<?php if (isAdmin()) :  ?>

					<li class="nav-item active">
						<a class="nav-link" href="../administrator">Dashboard</a>
					</li>
					<li class="nav-item active">
						<a class="nav-link" href="../documents">Documents</a>
					</li>

				<?php endif ?>

				<!-- <li class="nav-item active">
					<button class="btn btn-success ml-auto">Logout</button></a>
					<button class="dropdown-item btn btn-primary ml-2" id="change_password_link">Change Password</button>
				</li> -->
				<li class="nav-item active">
					<div class="btn-group">
						<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							Action
						</button>
						<div class="dropdown-menu dropdown-menu-right">
							<button class="dropdown-item" type="button" id="change_password_link">Change Password</button>
							<a href="<?= MYSITE . '?logout=1'; ?>"><button class="dropdown-item" type="button">Logout</button></a>
						</div>
					</div>
				</li>
			</ul>
		</div>
	<?php else : ?>
		<div class="collapse navbar-collapse flex-grow-0" id="navbarSupportedContent">
			<ul class="navbar-nav text-right">
				<li class="nav-item active">
					<a class="nav-link" href="#">Contact</a>
				</li>
				<li class="nav-item active">
					<button id="loginButton" class="btn btn-success ml-auto">Login</button>
				</li>
			</ul>
		</div>
	<?php endif ?>

	<button id="toggle_button" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
		<span class="navbar-toggler-icon"></span>
	</button>

	<?php if ($pageTitle == "MDT - Home") : ?>
	<?php else : ?>
	<?php endif ?>
</nav>