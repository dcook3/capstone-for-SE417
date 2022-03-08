<?php
function setMessage($error) {
	echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
		  ' . $error . '
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		  </button>
		</div>';
}
function successMessage($error) {
	echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
		  ' . $error . '
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		  </button>
		</div>';
}
function clear_input($input) 	//htmlspclchars, removes empty spaces
{
	$input = stripslashes($input);
	$input = htmlspecialchars($input);
	return $input;
}
function redirect($location)	//Simplifying the HEADER(location: $loc) function
{
	return header("Location: $location");
}

class user
{
	private $con, $sql, $send_query, $get, $row, $datetime;
	public $temp, $flag;
	public $email, $fname, $lname, $phone, $student_id;

	public function __construct()	//constructor to connect to DB
	{
		$this->row = array();
		$this->con = new connectDB();
		$this->con->connect();
		date_default_timezone_set('America/New_York');
		$this->datetime = date("Y-m-d H:i:s");
		$this->temp = $this->flag = 0;
		require 'PHPMailer/PHPMailerAutoload.php';		
	}
	public function __destruct() {
		$this->con->disconnect();
	}
	public function register_account() {//http://localhost/as_capstone/register
		if (isset($_POST['register_submit']) && !isset($_GET['s'])) {
			$pass = md5($this->con->escape($_POST['pass']));
			$pass_confirm = md5($this->con->escape($_POST['pass_confirm']));
			if ($pass === $pass_confirm) {
				//$this->student_id = $this->con->escape(clear_input($_POST['username']));
				$this->fname = $this->con->escape(clear_input($_POST['fname']));
				$this->lname = $this->con->escape(clear_input($_POST['lname']));
				//$this->phone = $this->con->escape(clear_input($_POST['phone']));
				$this->email = $this->con->escape(clear_input($_POST['email']));
				$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';		//generate verify key
				$verify_key = substr(str_shuffle($str_result), 0, 20); 		//generate verify key

				$this->sql = "INSERT INTO `login_info` (`email`, `password`, `created_at`) VALUES (?, ?, ?)";
				$this->send_query = $this->con->prepare($this->sql);
				mysqli_stmt_bind_param($this->send_query, "sss", $this->email, $pass, $this->datetime);

				$sql2 = "INSERT INTO `user` (`first_name`, `last_name`, `email`, `login_access`, `verify_key`, `isVerified`) VALUES (?, ?, ?, ?, ?, ?)";

				$this->temp = 0;	//LOGIN ACCESS
				$send_query2 = $this->con->prepare($sql2);
				mysqli_stmt_bind_param($send_query2, "sssisi", $this->fname, $this->lname, $this->email, $this->temp, $verify_key, $this->temp);

				if (isset($this->send_query) && isset($send_query2)) {
					if (mysqli_stmt_execute($this->send_query) && mysqli_stmt_execute($send_query2)) {
						if ($this->verify_email_register($this->email, $this->fname, $this->lname, $verify_key)) {
							mysqli_stmt_close($this->send_query);
							mysqli_stmt_close($send_query2);
							redirect("register?s=1&cid=" . base64_encode($this->con->last_id()));
						} else {
							mysqli_stmt_close($this->send_query);
							mysqli_stmt_close($send_query2);
							setMessage("Could not send verification email. Please check your internet connection. ");
						}
					} else {
						mysqli_stmt_close($this->send_query);
						mysqli_stmt_close($send_query2);
						setMessage("This email ID is already regstered with us. Please use a different email ID. Or <a href='register?resend=" . base64_encode($this->email) . "&s=1'>Click here</a> to resend the verification email");
					}
				}
			} 
			else {
				setMessage("The passwords don't match.");
			}
		}
	}

	public function verify_email_register(&$email, &$fname, &$lname, &$verify_key) {		//CALLED IN THE PREVIOUS FUNCTION
		$mail = new PHPMailer;
		$mail->isSMTP();
		//$mail->SMTPDebug = 2;

		$config = parse_ini_file('dbconfig.ini', true);

		$mail->Host = 'smtp.gmail.com'; // Which SMTP server to use.
		$mail->Port = 587; // Which port to use, 587 is the default port for TLS security.
		$mail->SMTPSecure = 'tls'; // Which security method to use. TLS is most secure.
		$mail->SMTPAuth = true; // Whether you need to login. This is almost always required.

		$mail->Username = $config['user']; // Your Gmail address.
		$mail->Password = $config['pass']; // Your Gmail login password or App Specific Password.

		$name = $fname . " " . $lname;
		// $cc = "email@gmail.com";
		$_SERVER['SERVER_PORT'] == 80 ? $port = "http://" : $port = "https://ascapstone.herokuapp.com";

		$message = $port . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?key=' . base64_encode($verify_key) . '&s=2&email=' . base64_encode($email);
		$subject = 'Website - Email verification';
		// $mail->AddReplyTo($reply_to, $fname);
		$mail->setFrom($mail->Username, 'Website'); // Set the sender of the message.
		$mail->addAddress($email, $name); // Set the recipient of the message.
		// $mail->AddCC($cc, 'Name - Website Form');	//CC email
		$mail->Subject = $subject; // The subject of the message.

		$mail->Body = $message; // Set a plain text body.

		// ... or send an email with HTML.
		//$mail->msgHTML(file_get_contents('contents.html'));
		// Optional when using HTML: Set an alternative plain text message for email clients who prefer that.
		//$mail->AltBody = 'This is a plain-text message body'; 

		// Optional: attach a file
		//$mail->addAttachment('images/phpmailer_mini.png');

		if ($mail->send()) {
			return 1;
		} else {
			return 0;
		}
	}

	public function resend_verify_email() {//register?s=1&cid=Mzc=
		if (isset($_GET['resend']) && !isset($_GET['cid'])) {
			$this->email = base64_decode($_GET['resend']);
			$this->sql = "SELECT c.first_name, c.last_name, c.email, c.verify_key FROM user c WHERE c.email = ?";
			$this->send_query = $this->con->prepare($this->sql);
			if (isset($this->send_query)) {
				mysqli_stmt_bind_param($this->send_query, "s", $this->email);
				mysqli_stmt_bind_result($this->send_query, $this->fname, $this->lname, $this->email, $verify_key);
				mysqli_stmt_execute($this->send_query);
				mysqli_stmt_store_result($this->send_query);
				if (mysqli_stmt_num_rows($this->send_query) == 1) {
					while (mysqli_stmt_fetch($this->send_query)) {
						$emailsent = $this->verify_email_register($this->email, $this->fname, $this->lname, $verify_key);
						if (!$emailsent) {
							setMessage("Could not send verification email. Please check your internet connection. ");
						} else {
							successMessage("A verification link has been sent to your Email ID. Please click on the link and verify your email ID. <a href='register?s=1&resend=" . $_GET['resend'] . "'>Click here</a> to resend the verification link. ");
						}
					}
				} else {
					setMessage("Could not resend the email. Please try registering again after some time. ");
				}
				mysqli_stmt_free_result($this->send_query);
				mysqli_stmt_close($this->send_query);
			}
		}
		if (isset($_GET['cid']) && isset($_GET['resend']))			//isset $get['s'] and $get['s'] == 1 is already checked
		{
			$cid = base64_decode($this->con->escape(clear_input($_GET['cid'])));
			$this->sql = "SELECT c.first_name, c.last_name, c.email, c.verify_key FROM user c WHERE c.user_id = ?";
			$this->send_query = $this->con->prepare($this->sql);
			if (isset($this->send_query)) {
				mysqli_stmt_bind_param($this->send_query, "i", $cid);
				mysqli_stmt_bind_result($this->send_query, $this->fname, $this->lname, $this->email, $verify_key);
				mysqli_stmt_execute($this->send_query);
				mysqli_stmt_store_result($this->send_query);
				if (mysqli_stmt_num_rows($this->send_query) == 1) {
					while (mysqli_stmt_fetch($this->send_query)) {
						$emailsent = $this->verify_email_register($this->email, $this->fname, $this->lname, $verify_key);
						if (!$emailsent) {
							setMessage("Could not send verification email. Please check your internet connection. ");
						} else {
							successMessage("A verification link has been sent to your Email ID. Please click on the link and verify your email ID. <a href='register?s=1&cid=" . $_GET['cid'] . "&resend'>Click here</a> to resend the verification link. ");
						}
					}
				} else {
					setMessage("Could not resend the email. Please try registering again after some time. ");
				}
				mysqli_stmt_free_result($this->send_query);
				mysqli_stmt_close($this->send_query);
			}
		}
	}

	public function verify_email()		//verifies the email sent to user
	{
		$this->temp = 0;
		$this->flag = 1;
		$verify_key = base64_decode(clear_input($_GET['key']));
		$this->email = base64_decode($_GET['email']);

		$this->sql = "SELECT c.user_id FROM user c WHERE c.email = ? AND c.verify_key = ? AND isVerified = ? AND login_access = ?";
		$this->send_query = $this->con->prepare($this->sql);
		if (isset($this->send_query)) {
			mysqli_stmt_bind_param($this->send_query, "ssii", $this->email, $verify_key, $this->temp, $this->temp);
			mysqli_stmt_bind_result($this->send_query, $cid);
			mysqli_stmt_execute($this->send_query);
			mysqli_stmt_store_result($this->send_query);
			if (mysqli_stmt_num_rows($this->send_query) == 1) {
				mysqli_stmt_free_result($this->send_query);
				mysqli_stmt_close($this->send_query);

				$sql2 = "UPDATE user c SET c.isVerified = ?, c.login_access = ? WHERE c.email = ?";
				$send_query2 = $this->con->prepare($sql2);
				if (isset($send_query2)) {
					mysqli_stmt_bind_param($send_query2, "iis", $this->flag, $this->flag, $this->email);
					mysqli_stmt_execute($send_query2);
					mysqli_stmt_store_result($send_query2);
					if (mysqli_stmt_affected_rows($send_query2) == 1) {
						successMessage("Yay! &#x1f389; Email verified. ");
						echo '
						<div class="text-center p-t-46 p-b-20">
							<span class="no-account">
							<span class="no-account-a"><a href="login">Click here</a></span> to sign in with your credentials.
						</span>
						</div>
						';
					}
				}
			} else {
				mysqli_stmt_free_result($this->send_query);
				mysqli_stmt_close($this->send_query);
				redirect("notfound");
			}
		}
	}

	public function login() {
		if (isset($_POST['login_submit'])) {
			if (isset($_SESSION['USER'])) {
				unset($_SESSION['USER']);
			}
			$this->get = $_SESSION['REFERER'];
			$this->temp = 1;
			$isLogin = 0;
			$this->email = $this->con->escape($_POST['email']);
			$pass = md5($this->con->escape($_POST['pass']));

			$this->sql = "SELECT c.first_name, c.last_name, c.user_id FROM user c, login_info l WHERE c.email = l.email AND l.email = ? AND l.password = ? AND c.isVerified = ? AND c.login_access = ?";
			$this->send_query = $this->con->prepare($this->sql);

			mysqli_stmt_bind_param($this->send_query, "ssii", $this->email, $pass, $this->temp, $this->temp);
			mysqli_stmt_bind_result($this->send_query, $this->fname, $this->lname, $user_id);

			if (isset($this->send_query) && mysqli_stmt_execute($this->send_query)) {
				while (mysqli_stmt_fetch($this->send_query)) {
					$isLogin = 1;
				}
				mysqli_stmt_close($this->send_query);
				if (!$isLogin) {
					setMessage("The email and password combination didn't match. Please try again. ");
				} else {
					$_SESSION['USER'] = array('NAME' => $this->fname . " " . $this->lname, 'EMAIL' => $this->email, 'USERID' => $user_id);
					unset($_SESSION['location_query']);
					unset($_SESSION['REFERER']);
					redirect("index");
				}
			}
		}
	}

	public function send_mail($receiver_email, $receiver_name, $message, $subject) {
		$mail = new PHPMailer;
		$mail->isSMTP();
		//$mail->SMTPDebug = 2;
		$config = parse_ini_file('dbconfig.ini', true);
		$mail->Host = 'smtp.gmail.com'; // Which SMTP server to use.
		$mail->Port = 587; // Which port to use, 587 is the default port for TLS security.
		$mail->SMTPSecure = 'tls'; // Which security method to use. TLS is most secure.
		$mail->SMTPAuth = true; // Whether you need to login. This is almost always required.
		$mail->Username = $config['user']; // Your Gmail address.
		$mail->Password = $config['pass']; // Your Gmail login password or App Specific Password.
		$mail->setFrom($mail->Username, 'Website'); // Set the sender of the message.
		$mail->addAddress($receiver_email, $receiver_name); // Set the recipient of the message.
		$mail->Subject = $subject; // The subject of the message.
		$mail->Body = $message; // Set a plain text body.
		if ($mail->send()) {
			return 1;
		} else {
			return 0;
		}
	}
	public function forgotPassword() {
		if (isset($_GET['forgotPassword']) && isset($_POST['forgot_submit']) && !isset($_GET['key']) && !isset($_GET['ts'])) {
			$this->flag = 0;
			$this->email = $_POST['email'];
			$this->sql = "SELECT c.verify_key, c.first_name, c.last_name from user c, login_info l WHERE l.email = ? AND c.email = l.email";
			$this->send_query = $this->con->prepare($this->sql);
			if (isset($this->send_query)) {
				mysqli_stmt_bind_param($this->send_query, "s", $this->email);
				mysqli_stmt_bind_result($this->send_query, $verify_key, $first_name, $last_name);
				if (mysqli_stmt_execute($this->send_query) && mysqli_stmt_fetch($this->send_query)) {
					$this->flag = 1;
				} else {
					setMessage("This email is not registered. Please register an account with this email ID. ");
				}
				mysqli_stmt_close($this->send_query);
			}
			if ($this->flag === 1) {
				$name = $first_name . " " . $last_name;
				$subject = "Website Restaurant - Forgot Password";
				$message = "Click on the link below to change your password. This link expires in 5 minutes. \n";
				if ($_SERVER['SERVER_PORT'] == 8080) {
					$host = "http://";
				} else {
					$host = "https://ascapstone.herokuapp.com/";
				}
				$this->datetime = new DateTime();
				$this->time = new DateTime();
				$this->time = $this->datetime->getTimestamp();
				$message .= $host . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "&key=" . base64_encode($verify_key) . "&ts=" . base64_encode($this->time);
				if ($this->send_mail($this->email, $name, $message, $subject)) {
					successMessage("A password reset link has been sent to " . $this->email . ". Please check your mail. ");
				} else {
					setMessage("Password reset link couldn't be sent. ");
				}

				$this->datetime = $this->datetime->format('Y-m-d H:i:s');
				$this->sql = "UPDATE `login_info` SET `updated_at` = ? WHERE email = ?";
				$this->send_query = $this->con->prepare($this->sql);
				mysqli_stmt_bind_param($this->send_query, "ss", $this->datetime, $this->email);
				if (isset($this->send_query)) {
					mysqli_stmt_execute($this->send_query);
					// mysqli_stmt_store_result($this->send_query);
					// if(mysqli_stmt_affected_rows($this->send_query) === 1)
					// {
					// 	setMessage("Date modified");
					// }
					// mysqli_stmt_free_result($this->send_query);
					mysqli_stmt_close($this->send_query);
				}
			}
		} else if (isset($_GET['forgotPassword']) && isset($_GET['key']) && isset($_GET['ts'])) {
			$this->flag = 0;
			$this->get = 0;
			$verify_key = base64_decode($_GET['key']);
			$this->sql = "SELECT c.email, c.verify_key, l.updated_at from user c, login_info l WHERE c.verify_key = ? AND c.email = l.email";
			$this->send_query = $this->con->prepare($this->sql);
			if (isset($this->send_query)) {
				mysqli_stmt_bind_param($this->send_query, "s", $verify_key);
				mysqli_stmt_bind_result($this->send_query, $this->email, $verify_key, $date_modified);
				if (mysqli_stmt_execute($this->send_query) && mysqli_stmt_fetch($this->send_query)) {
					$this->flag = 1;
					$currentTime = new DateTime();
					$linkSentTime = new DateTime($date_modified);
					if (($currentTime->getTimestamp() - $linkSentTime->getTimestamp()) < 300) {
						$this->get = 1;
					} else {
						redirect("login?forgotPassword&expired");
						//setMessage("This link has expired! ");
					}
				} else {
					redirect("login?forgotPassword&error");
					//setMessage("Invalid link. ");
				}
				mysqli_stmt_close($this->send_query);
			}
			if ($this->flag === 1 && $this->get === 1 && isset($_POST['change_pass'])) {
				$newPass = md5(trim($_POST['pass']));
				$newPassRepeat = md5(trim($_POST['confirm_pass']));
				$this->datetime = $currentTime->format('Y-m-d H:i:s');
				if ($newPass === $newPassRepeat) {
					$this->sql = "UPDATE `login_info` SET `password` = ?, `updated_at` = ? WHERE `email` = ?";
					$this->send_query = $this->con->prepare($this->sql);
					if (isset($this->send_query)) {
						mysqli_stmt_bind_param($this->send_query, "sss", $newPass, $this->datetime, $this->email);
						if (mysqli_stmt_execute($this->send_query)) {
							$this->get = 1;
							redirect("login?forgotPassword&changed");
							//setMessage("Password updated. ");
						} else {
							$this->get = 0;
							setMessage("Password couldn't be updated.");
						}
						mysqli_stmt_close($this->send_query);
					}
				} else {
					setMessage("The Passwords don't match! Please try again. ");
				}
			}
		}
	}
}
