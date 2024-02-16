<?php
require_once '../src/init.php';
require_once SRC . 'controller/index.php';
goLogin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title>Weed Export-Import</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="<?php getAssetLink('css-v*.css', 'css'); ?>">
</head>

<body>
	<div id="w_e_i_main_wrapp">

		<?php getNavbar($pageTitle); ?>

		<!-- <div id="bck_imag_land" class="card bg-dark text-white">
			<img id="bck_imag_land_img" class="card-img" src="images/whitesolid.jpg" alt="Card image">
			<div class="card-img-overlay">
			    <h5 class="card-title">Name of the Company</h5>
			    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
			</div>
		</div> -->
		<!-- <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  			<div id="slider_custom" class="carousel-inner">
    			<div class="carousel-item active">
	    			<div class="carousel-caption d-none d-md-block">
	    				<h4>Marijuana 1</h4>
	    				<h6>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</h6>
	  				</div>
      				<img  class="d-block w-100" src="images/different.jpg" alt="First slide">
    			</div>
    			<div class="carousel-item">
	    			<div class="carousel-caption d-none d-md-block">
	   					<h4>Marijuana 2</h4>
	    				<h6>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</h6>
	  				</div>
      				<img  class="d-block w-100" src="images/different.jpg" alt="Second slide">
    			</div>
    			<div class="carousel-item">
    				<div class="carousel-caption d-none d-md-block">
					    <h4>Marijuana 3</h4>
					    <h6>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</h6>
  					</div>
      				<img  class="d-block w-100" src="images/different.jpg" alt="Third slide">
    			</div>
  			</div>
  			<a id="prev_contr_carus" class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
    			<span id="icon_color_1" aria-hidden="true"><h4>&#60;<h4></span>
    			<span class="sr-only">Previous</span>
  			</a>
  			<a id="next_contr_carus" class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
			    <span id="icon_color_2" aria-hidden="true"><h4>&gt;</h4></span>
			    <span class="sr-only">Next</span>
  			</a>
		</div> carouselExampleControls -->
		<!-- <div id="contact_main_wrapp">
			<div id="contact_main_wrapp_subb">
				<h2>Contact Us</h2>
				<div class="propFormContc">
					<h4>You can call us on this phone number:</h4>
					<h3>555-5555-5555-555</h3>
				</div>
				<div class="propFormContc">
					<h4>You can find us on this address:</h4>
					<h3>Some Avenue 76541, Some State, Some Town, Canada</h3>
				</div>
				<div class="propFormContc">
					<h4>Or send us message via this email:</h4>
					<h3>something@gmail.com</h3>
				</div>
			</div>
		</div> -->

		<?php getFooter($pageTitle); ?>
	</div><!-- w_e_i_main_wrapp -->

	<script src="<?php getAssetLink('index-v*.js', 'js'); ?>"></script>
</body>

</html>