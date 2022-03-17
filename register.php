<?php require_once 'includes/config.php'; ?>
<?php isset($_SESSION['USER']) ? redirect("index") : 0; ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="" />
	<link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="lib/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="lib/login/css/util.css">
	<link rel="stylesheet" type="text/css" href="lib/login/css/main.css">
	<link rel="stylesheet" type="text/css" href="lib/css/style.css">
	<link rel="stylesheet" type="text/css" href="lib/login/css/register.css">
	<title>Tiger Eats - Create Account</title>
</head>
<body>
<?php //include("includes/front/header_static.php"); ?>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 form-row">
				<form class="login100-form validate-form needs-validation was-validated" method="POST" action="register" novalidate="">
					<span class="login100-form-title p-b-43">
						Create your account
					</span>
					<?php
					if (!isset($_GET['s']))
					{
						$user->register_account();
					?>
						<div class="wrap-input100 validate-input" data-validate="Username is required">
							<input class="input100" type="text" name="username" data-toggle="tooltip" data-placement="top" data-original-title="">
							<span class="focus-input100"></span>
							<span class="label-input100">Student ID</span>
							<div class="invalid-feedback">
					Please provide a valid email!
				</div>
						</div>
						<div class="wrap-input100 validate-input" data-validate="First name is required">
							<input class="input100" type="text" name="fname" required="" data-toggle="tooltip" data-placement="top" data-original-title="">
							<span class="focus-input100"></span>
							<span class="label-input100">First Name</span>
						</div>
						<div class="wrap-input100 validate-input" data-validate="Last name is required">
							<input class="input100" type="text" name="lname" required="" data-toggle="tooltip" data-placement="top" data-original-title="">
							<span class="focus-input100"></span>
							<span class="label-input100">Last Name</span>
						</div>
						<div class="wrap-input100 validate-input" data-validate="Phone number is required">
							<input class="input100" type="tel" name="phone" required="">
							<span class="focus-input100"></span>
							<span class="label-input100">Phone Number</span>
						</div>
						<div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
							<input class="input100" type="text" name="email" required="">
							<span class="focus-input100"></span>
							<span class="label-input100">Email</span>
						</div>
						<div class="wrap-input100 validate-input" data-validate="Password is required">
							<input class="input100" type="password" name="pass" pattern=".{6,}" data-toggle="tooltip" data-placement="top" data-original-title="" required="">
							<span class="focus-input100"></span>
							<span class="label-input100">Password</span>
						</div>
						<div class="wrap-input100 validate-input" data-validate="Confirm password is required">
							<input class="input100" type="password" name="pass_confirm" pattern=".{6,}" data-toggle="tooltip" data-placement="top" data-original-title="" required="">
							<span class="focus-input100"></span>
							<span class="label-input100">Confirm Password</span>
						</div>
						<div class="container-login100-form-btn">
							<button id="registerButton" class="login100-form-btn" name="register_submit">
								Create your account
							</button>
						</div>
						<div class="text-center p-t-46 p-b-20">
							<span class="no-account"> Have a account?
								<span class="no-account-a"><a href="login">Sign in</a></span>
							</span>
						</div>
						<div>
							<?php isset($_SERVER['HTTP_REFERER']) ? $goback = $_SERVER['HTTP_REFERER'] : $goback = "index" ?>
							<a href="<?php echo $goback; ?>"><button type="button" class="go-back-phone login100-form-btn">Go back</button></a>
						</div>
					<?php
						}
					if (isset($_GET['s'])) {
						if ($_GET['s'] == 1 && isset($_GET['cid'])) {
							successMessage("A verification link has been sent to your Email. Please click on the link and verify your email. <a href='register?s=1&cid=" . $_GET['cid'] . "&resend'>Click here</a> to resend the verification link. ");
							$user->resend_verify_email();
						}
						if ($_GET['s'] == 1 && isset($_GET['resend']) && !isset($_GET['cid'])) {
							$user->resend_verify_email();
						}
						if ($_GET['s'] == 2 && isset($_GET['email']) && isset($_GET['key'])) {
							$user->verify_email();
						}
					}
					?>
				</form>
				<script>
					// Example starter JavaScript for disabling form submissions if there are invalid fields
					(function() {
						'use strict';
						window.addEventListener('load', function() {
							// Fetch all the forms we want to apply custom Bootstrap validation styles to
							var forms = document.getElementsByClassName('needs-validation');
							// Loop over them and prevent submission
							var validation = Array.prototype.filter.call(forms, function(form) {
								form.addEventListener('submit', function(event) {
									if (form.checkValidity() === false) {
										event.preventDefault();
										event.stopPropagation();
									}
									form.classList.add('was-validated');
								}, false);
							});
						}, false);
					})();
				</script>

				<div class="login100-more" >
					<footer id="footer">
						<div class="container">
							<div class="row">
								<?php //isset($_SERVER['HTTP_REFERER']) ? $goback = $_SERVER['HTTP_REFERER'] : $goback = "index" ?>
								<a href="index<?php //echo $goback ?>"><button class="login100-form-btn">Go back</button></a>
							</div>
						</div>
					</footer>
				</div>

			</div>
		</div>
	</div>
	<div id="preloader"></div>
	<script src="lib/jquery/jquery.min.js"></script>

	<script src="lib/login/vendor/bootstrap/js/popper.js"></script>
	<script src="lib/bootstrap/js/bootstrap.min.js"></script>

	<script type="text/javascript">
		var form = document.querySelector('form');

		$(function() {
			$('[data-toggle="tooltip"]').tooltip()
		})
	</script>
	<script src="lib/login/js/main.js"></script>
	<script src="lib/wow/wow.min.js"></script>
	<script src="lib/superfish/hoverIntent.js"></script>
	<script src="lib/superfish/superfish.min.js"></script>
	<script src="lib/touchSwipe/jquery.touchSwipe.min.js"></script>
	<script src="lib/waypoints/waypoints.min.js"></script>
	<script src="lib/counterup/counterup.min.js"></script>
	<script src="lib/isotope/isotope.pkgd.min.js"></script>
	<script src="lib/owlcarousel/owl.carousel.min.js"></script>
	<script src="js/main.js"></script>
</body>
</html>