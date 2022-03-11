<?php require_once 'includes/config.php'; ?>
<?php isset($_SERVER['HTTP_REFERER']) && !isset($_SESSION['REFERER']) ? $_SESSION['REFERER'] = $_SERVER['HTTP_REFERER'] : 0;
isset($_SESSION['USER']) ? redirect("index") : 0; ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="">
	<meta content="" name="">
	<link rel="icon" type="image/png" href="" />
	<link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="lib/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="lib/login/css/util.css">
	<link rel="stylesheet" type="text/css" href="lib/login/css/main.css">
	<link rel="stylesheet" type="text/css" href="lib/css/index.css">
	<link rel="stylesheet" type="text/css" href="lib/css/style.css">
	<link rel="stylesheet" type="text/css" href="lib/login/css/login.css">
	<title>Document</title>
</head>
<body>

				<?php
				if (isset($_GET['forgotPassword'])) {
					include('forgotPassword.php');
				} else {
					include('loginAccount.php');
				}
				?>
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
					<footer id="footer">
						<div class="container">
							<div class="row">
								<?php isset($_SERVER['HTTP_REFERER']) ? $goback = $_SERVER['HTTP_REFERER'] : $goback = "index" ?>
								<a href="<?php echo $goback; ?>"><button class="login100-form-btn">Go back</button></a>
							</div>
						</div>
					</footer>

			</div>
	<div id="preloader"></div>

	<script src="lib/jquery/jquery.min.js"></script>
	<script src="lib/login/vendor/bootstrap/js/popper.js"></script>
	<script src="lib/bootstrap/js/bootstrap.min.js"></script>
	<script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
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
	<script>
		$(function() {
			$('[data-toggle="tooltip"]').tooltip();
		})
	</script>
</body>
</html>