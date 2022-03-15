<?php
require 'connectDB.php';
function setMessage($error) {
	echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
		  ' . $error . '
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		  </button>
		</div>';
}
function clear_input($input) {
	$input = stripslashes($input);
	$input = htmlspecialchars($input);
	return $input;
}
function redirect($location) {
	return header("Location: $location");
}

class admin extends connectDB {
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

    public function display_customers()	{
        /*if (isset($_GET['logins']) && isset($_GET['admin'])) {
			$this->sql = "SELECT l.email, a.first_name, a.last_name, l.created_at, l.updated_at, a.admin_id FROM login_info l, admin a WHERE a.email = l.email";
			$this->send_query = $this->con->prepare($this->sql);
			if (isset($this->send_query)) {
				mysqli_stmt_bind_result($this->send_query, $this->email, $first_name, $last_name, $date_added, $date_modified, $admin_id);
				if (mysqli_stmt_execute($this->send_query) && mysqli_stmt_store_result($this->send_query)) {
					while (mysqli_stmt_fetch($this->send_query)) {
						if (mysqli_stmt_num_rows($this->send_query) > 1) {
							$delete_button = '
			                <td data-toggle="tooltip"  data-placement="top" title="" data-original-title="Delete ' . $first_name . '">
			                    <button class="btn btn-danger btn-circle btn-sm delete-btn-circle" data-toggle="modal" data-target="#exampleModal' . $admin_id . '">
			                        <i class="fas fa-trash"></i>
			                    </button>
			                </td>
							';
						} else {
							$delete_button = '
			                <td data-toggle="tooltip"  data-placement="top" title="" data-original-title="This admin cannot be removed. There has to be atleast 1 existing admin. ">
			                    <button class="btn btn-danger btn-circle btn-sm delete-btn-circle">
			                        <i class="fas fa-trash"></i>
			                    </button>
			                </td>
							';
						}
						echo '
		           		<tr>
                           <td>' . $admin_id . '</td>
	                        <td><a>' . $first_name . " " .  $last_name . '</a></td>
                            <td><a>' . $this->email . '</a></td>
	                        <td>' . $date_added . '</td>
	                        <td>' . $date_modified . '</td>
	                        ' . $delete_button . '                        
	                    </tr>';

						//delete modal
						echo '
						<div class="modal fade" id="exampleModal' . $admin_id . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel' . $admin_id . '" style="display: none;" aria-hidden="true">
						    <div class="modal-dialog" role="document">
						        <div class="modal-content">
						            <div class="modal-header">
						                <h5 class="modal-title" id="exampleModalLabel' . $admin_id . '">Are you sure? This action is irreversible!</h5>
						                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						                    <span aria-hidden="true">×</span>
						                </button>
						            </div>
						            <div class="modal-body">
						                <p class="mb-0">Do you want to permanently delete ' . $first_name . " " . $last_name . '?</p>	
						            </div>
						            <div class="modal-footer">
										<a href="index?logins&admin&del=' . base64_encode($admin_id) . '" class="btn btn-danger btn-icon-split delete-btn btn-padding">
										    <span class="icon text-white-50">
										    <i class="fas fa-trash"></i>
										    </span>
										    <span class="text">Delete</span>
										</a>
										<button class="btn btn-secondary btn-icon-split btn-padding gray-btn" data-dismiss="modal">
										    <span class="icon text-white-50">
										    <i class="fas fa-times"></i>
										    </span>
										    <span class="text">Close</span>
										</button>
						            </div>
						        </div>
						    </div>
						</div>';
					}
				}
				mysqli_stmt_free_result($this->send_query);
				mysqli_stmt_close($this->send_query);
			}
		}*/
		/*if (isset($_GET['logins']) && isset($_GET['staff'])) {
			$this->sql = "SELECT l.email, s.first_name, s.last_name, l.date_added, l.date_modified, s.staff_id FROM login_info l, staff s WHERE s.email = l.email";
			$this->send_query = $this->con->prepare($this->sql);
			if (isset($this->send_query)) {
				// mysqli_stmt_bind_param($this->send_query);
				mysqli_stmt_bind_result($this->send_query, $this->email, $first_name, $last_name, $date_added, $date_modified, $staff_id);
				if (mysqli_stmt_execute($this->send_query) && mysqli_stmt_fetch($this->send_query)) {
					do {
						echo '
		           		<tr>
	                        <td><a>' . $this->email . '</a></td>
	                        <td><a>' . $first_name . " " .  $last_name . '</a></td>
	                        <td>' . $date_added . '</td>
	                        <td>' . $date_modified . '</td>
			                <td data-toggle="tooltip"  data-placement="top" title="" data-original-title="Delete ' . $first_name . '">
			                    <button class="btn btn-danger btn-circle btn-sm delete-btn-circle" data-toggle="modal" data-target="#exampleModal' . $staff_id . '">
			                        <i class="fas fa-trash"></i>
			                    </button>
			                </td>                        
	                    </tr>';

						//delete modal
						echo '
						<div class="modal fade" id="exampleModal' . $staff_id . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel' . $staff_id . '" style="display: none;" aria-hidden="true">
						    <div class="modal-dialog" role="document">
						        <div class="modal-content">
						            <div class="modal-header">
						                <h5 class="modal-title" id="exampleModalLabel' . $staff_id . '">Are you sure? This action is irreversible!</h5>
						                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						                    <span aria-hidden="true">×</span>
						                </button>
						            </div>
						            <div class="modal-body">
						                <p class="mb-0">Do you want to permanently delete ' . $first_name . " " . $last_name . '?</p>	
						            </div>
						            <div class="modal-footer">
										<a href="index?logins&staff&del=' . base64_encode($staff_id) . '" class="btn btn-danger btn-icon-split delete-btn btn-padding">
										    <span class="icon text-white-50">
										    <i class="fas fa-trash"></i>
										    </span>
										    <span class="text">Delete</span>
										</a>
										<button class="btn btn-secondary btn-icon-split btn-padding gray-btn" data-dismiss="modal">
										    <span class="icon text-white-50">
										    <i class="fas fa-times"></i>
										    </span>
										    <span class="text">Close</span>
										</button>
						            </div>
						        </div>
						    </div>
						</div>';
					} while (mysqli_stmt_fetch($this->send_query));
				}
				mysqli_stmt_close($this->send_query);
			}
		}*/
       // if (isset($_GET['logins']) && isset($_GET['cust'])) {
			$this->sql = "SELECT l.email, c.student_id, c.first_name, c.middle_name, c.last_name, c.phone, l.created_at, l.updated_at, c.login_access, c.user_id 
            FROM login_info l, user c 
            WHERE c.email = l.email";

			$this->send_query = $this->con->prepare($this->sql);
			if (isset($this->send_query)) {
				// mysqli_stmt_bind_param($this->send_query);
				mysqli_stmt_bind_result($this->send_query, $this->email, $student_id, $first_name, $middle_name, $last_name, $phone, $date_added, $date_modified, $login_access, $customer_id);
				if (mysqli_stmt_execute($this->send_query) && mysqli_stmt_fetch($this->send_query)) {
					do {
						if ($this->email != "public") {
							echo '
			           		<tr>
                               <td><a>' . $customer_id . '</a></td>
                               <td><a>' . $student_id . '</a></td>
                               <td><a>' . $first_name . '</a></td>
							   <td><a>' . $middle_name . '</a></td>
							   <td><a>' . $last_name . '</a></td>
                               <td><a>' . $phone . '</a></td>
		                        <td><a>' . $this->email . '</a></td>
		                        <td>' . $date_added . '</td>
		                        <td>' . $date_modified . '</td>
								<td></td>
				                <td data-toggle="tooltip"  data-placement="top" title="" data-original-title="Delete ' . $first_name . '">
				                    <button class="btn btn-danger btn-circle btn-sm delete-btn-circle" data-toggle="modal" data-target="#exampleModal' . $customer_id . '">
				                        <i class="fas fa-trash"></i>
				                    </button>
				                </td>                        
		                    </tr>';
						}
					} while (mysqli_stmt_fetch($this->send_query));
				}
				mysqli_stmt_close($this->send_query);
			}
		//}
	}
    public function delete_logins() {
		if (isset($_GET['logins']) && isset($_GET['del'])) {
			$customer_id = base64_decode($_GET['del']);
			if (isset($_GET['cust'])) {
				$this->sql = "DELETE user, login_info FROM user INNER JOIN login_info ON user.email = login_info.email WHERE user.user_id = ?";
				$this->send_query = $this->con->prepare($this->sql);
				if (isset($this->send_query)) {
					mysqli_stmt_bind_param($this->send_query, "i", $customer_id);
					if (mysqli_stmt_execute($this->send_query) && mysqli_stmt_affected_rows($this->send_query) >= 1) {
						setMessage("1 customer removed.");
						unset($_GET['del']);
					}
				}
			}
			if (isset($_GET['admin'])) {
				$this->sql = "DELETE admin, login_info FROM admin INNER JOIN login_info ON admin.email = login_info.email WHERE admin.admin_id = ?";
				$this->send_query = $this->con->prepare($this->sql);
				if (isset($this->send_query)) {
					mysqli_stmt_bind_param($this->send_query, "i", $customer_id);
					if (mysqli_stmt_execute($this->send_query) && mysqli_stmt_affected_rows($this->send_query) >= 1) {
						setMessage("1 admin removed.");
						unset($_GET['del']);
					}
				}
				mysqli_stmt_close($this->send_query);
			}
			if (isset($_GET['staff'])) {
				$this->sql = "DELETE staff, login_info FROM staff INNER JOIN login_info ON staff.email = login_info.email WHERE staff.staff_id = ?";
				$this->send_query = $this->con->prepare($this->sql);
				if (isset($this->send_query)) {
					mysqli_stmt_bind_param($this->send_query, "i", $customer_id);
					if (mysqli_stmt_execute($this->send_query) && mysqli_stmt_affected_rows($this->send_query) >= 1) {
						setMessage("1 staff removed.");
						unset($_GET['del']);
					}
				}
				mysqli_stmt_close($this->send_query);
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
                           <td>' . $user_id . '</td>
						   <td>' . $username . '</td>
						   <td>' . $first_name . '</td>
						   <td>' . $middle_name . '</td>
						   <td>' . $last_name . '</td>
							<td>' . $phone . '</td>
                            <td><a>' . $this->email . '</a></td>
	                        <td>' . $date_added . '</td>
	                        <td>' . $date_modified . '</td>
							<td><a href="profile.php">Edit</a></td>
							<td><a href="">Edit</a></td>                         
	                    </tr>';
					}
				}
				mysqli_stmt_free_result($this->send_query);
				mysqli_stmt_close($this->send_query);
			}
	}
}
