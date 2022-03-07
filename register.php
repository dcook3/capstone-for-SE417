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
	<title>Document</title>
</head>
<body>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 form-row">
				<form class="login100-form validate-form needs-validation was-validated" method="POST" action="register" novalidate="">
					<span class="login100-form-title p-b-43">
						Create account
					</span>
					<?php
					if (!isset($_GET['s'])) {
						$user->register_account();
					?>
						<!--<div class="wrap-input100 validate-input" data-validate="Username is required">
							<input class="input100" type="text" name="username" pattern="" required="" data-toggle="tooltip" data-placement="top" data-original-title="9 characters">
							<span class="focus-input100"></span>
							<span class="label-input100">Student ID</span>
						</div>-->
						<div class="wrap-input100 validate-input" data-validate="Do not leave name field blank">
							<input class="input100" type="text" name="fname" pattern="[A-Za-z]{1,30}" required="" data-toggle="tooltip" data-placement="top" data-original-title="Min 1 characters. Alphabets only.">
							<span class="focus-input100"></span>
							<span class="label-input100">First Name</span>
						</div>
						<div class="wrap-input100 validate-input" data-validate="Do not leave name field blank">
							<input class="input100" type="text" name="lname" pattern="[A-Za-z]{1,30}" required="" data-toggle="tooltip" data-placement="top" data-original-title="Min 1 characters. Alphabets only.">
							<span class="focus-input100"></span>
							<span class="label-input100">Last Name</span>
						</div>
						<!--<div class="wrap-input100 validate-input" data-validate="Phone Number is required">
							<input class="input100" type="text" name="phone" required="">
							<span class="focus-input100"></span>
							<span class="label-input100">Phone Number</span>
						</div>-->
						<div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
							<input class="input100" type="text" name="email" required="">
							<span class="focus-input100"></span>
							<span class="label-input100">Email</span>
						</div>
						<div class="wrap-input100 validate-input" data-validate="Password is required">
							<input class="input100" type="password" name="pass" pattern=".{6,}" data-toggle="tooltip" data-placement="top" data-original-title="Min 6 characters." required="">
							<span class="focus-input100"></span>
							<span class="label-input100">Password</span>
						</div>
						<div class="wrap-input100 validate-input" data-validate="Password is required">
							<input class="input100" type="password" name="pass_confirm" pattern=".{6,}" data-toggle="tooltip" data-placement="top" data-original-title="Min 6 characters." required="">
							<span class="focus-input100"></span>
							<span class="label-input100">Confirm Password</span>
						</div>
						<div class="flex-sb-m w-full p-t-3 p-b-32">
							<div class="contact100-form-checkbox">
								<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember_me">
								<label class="label-checkbox100" for="ckb1">
									Keep me signed in
								</label>
							</div>
						</div>
						<div class="container-login100-form-btn">
							<button type="submit" class="login100-form-btn" name="register_submit">
								Create Account
							</button>
						</div>
						<div class="text-center p-t-46 p-b-20">
							<span class="no-account">
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
							setMessage("A verification link has been sent to your Email ID. Please click on the link and verify your email ID. <a href='register?s=1&cid=" . $_GET['cid'] . "&resend'>Click here</a> to resend the verification link. ");
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
					<div id="header">
						<div id="logo" class="pull-left">
							<h1><a href="index" class="scrollto">Website</a></h1>
							<a href="index"><img src="" alt="" title="" /></a>
						</div>
					</div>
					<footer id="footer">
						<div class="container">
							<div class="row">
								<?php isset($_SERVER['HTTP_REFERER']) ? $goback = $_SERVER['HTTP_REFERER'] : $goback = "index" ?>
								<a href="<?php echo $goback ?>"><button class="login100-form-btn">Go back</button></a>
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