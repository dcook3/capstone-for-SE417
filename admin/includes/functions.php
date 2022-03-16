<?php
	function clear_input($input) {
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		return $input;
	}

	function setMessage($error) 
	{
		echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
			  ' . $error . '
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			  </button>
			</div>';
	}
	function redirect($location)	//Simplifying the HEADER(location: $loc) function
	{
		return header("Location: $location");
	}
require 'connectDB.php';
class admin {
	private $con, $sql, $send_query, $get, $row;
	public $temp, $flag, $email, $datetime;
	public $admin_id;

	public function __construct() {
		$this->row = array();
		$this->con = new connectDB();
		$this->con->connect();
		date_default_timezone_set('America/New_York');
		$this->datetime = date("Y-m-d H:i:s");
		$this->temp = $this->flag = 0;
	}
	public function __destruct() {
		$this->con->disconnect();
	}

	public function login() {
		if (isset($_POST['login_submit'])) {
			if (isset($_SESSION['ADMIN'])) {
				unset($_SESSION['ADMIN']);
			}
			$this->temp = 1;
			$isLogin = 0;
			$this->email = $this->con->escape($_POST['email']);
			$pass = md5($this->con->escape($_POST['pass']));

			//$this->sql = "SELECT a.first_name, a.last_name, a.admin_id FROM admin a, login_info l WHERE a.email = l.email AND l.email = ? AND l.password = ?";
			$this->sql = "SELECT a.first_name, a.last_name, a.admin_id FROM admin a, login_info l WHERE a.email = l.email AND l.email = ? AND l.password = ?";
			$this->send_query = $this->con->prepare($this->sql);

			mysqli_stmt_bind_param($this->send_query, "ss", $this->email, $pass);
			mysqli_stmt_bind_result($this->send_query, $this->fname, $this->lname, $this->admin_id);

			if (isset($this->send_query) && mysqli_stmt_execute($this->send_query)) {
				while (mysqli_stmt_fetch($this->send_query)) {
					$isLogin = 1;
				}
				mysqli_stmt_close($this->send_query);
				if (!$isLogin) {
					setMessage("The email and password combination didn't match. Please try again. ");
				} else {
					$_SESSION['ADMIN'] = array('NAME' => $this->fname . " " . $this->lname, 'EMAIL' => $this->email, 'ADMINID' => $this->admin_id);
					redirect("index");
				}
			}
		}
	}

    public function changePassword() {
		if (isset($_GET['changePassword']) && isset($_POST['change_pass'])) {
			$this->get = 0;
			preg_match("/(admin\/index.php)$/", $_SERVER['PHP_SELF']) ? $this->email = $_SESSION['ADMIN']['EMAIL'] : $this->email = $_SESSION['STAFF']['EMAIL'];
			$this->datetime = date("Y-m-d H:i:s");
			$currentPass = md5(trim($_POST['current_pass']));
			$newPass = md5(trim($_POST['new_pass']));
			$newPassRepeat = md5(trim($_POST['new_pass_repeat']));

			$this->sql = "SELECT `password` FROM login_info WHERE email = ?";
			$this->send_query = $this->con->prepare($this->sql);
			if (isset($this->send_query)) {
				mysqli_stmt_bind_param($this->send_query, "s", $this->email);
				mysqli_stmt_bind_result($this->send_query, $password);

				if (mysqli_stmt_execute($this->send_query) && mysqli_stmt_fetch($this->send_query)) {
					if ($currentPass === $password) {
						$this->get = 1;
					} else {
						$this->get = 0;
						setMessage("Entered current password is wrong. Please try again. ");
					}
				} else {
					setMessage("Query failed. ");
				}
				mysqli_stmt_close($this->send_query);
			}
			if ($this->get === 1 && $newPass == $newPassRepeat) {

			} else if ($this->get === 1 && $newPass != $newPassRepeat) {
				setMessage("The entered new passwords don't match. Please try again. ");
			}
		}
	}
	public function DisplayAllUsers() {
		$this->sql = "SELECT l.email, a.username, a.first_name, a.middle_name, a.last_name, a.phone, l.created_at, l.updated_at, a.user_id 
		FROM login_info l, user a 
		WHERE a.email = l.email";

		$this->send_query = $this->con->prepare($this->sql);
		if (isset($this->send_query)) {
			mysqli_stmt_bind_result($this->send_query, $this->email, $username, $first_name, $middle_name, $last_name, $phone, $date_added, $date_modified, $user_id);
			if (mysqli_stmt_execute($this->send_query) && mysqli_stmt_store_result($this->send_query)) {
				while (mysqli_stmt_fetch($this->send_query)) {
					echo '
					   <tr>
					   <td>' . $username . '</td>
					   <td>' . $first_name . '</td>
					   <td>' . $last_name . '</td>
						<td>' . $phone . '</td>
						<td><a>' . $this->email . '</a></td>
						<td>' . $date_added . '</td>
						<td>' . $date_modified . '</td>
						<td><a class="edit-user" data-uid=' . $user_id . ' href="#">Edit</a></td>                       
					</tr>';
				}
			}
			mysqli_stmt_free_result($this->send_query);
			mysqli_stmt_close($this->send_query);
		}
}
}
