<?php
include "config.php";

class  Database {
  public $pdo;
  // Construct Class
  public function __construct(){

    if (!isset($this->pdo)) {
      try {
        $link = new PDO('mysql:host='.DB_HOST.'; dbname='.DB_NAME, DB_USER, DB_PASS);
        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $link->exec("SET CHARACTER SET utf8");
        $this->pdo  =  $link;
      } catch (PDOException $e) {
        die("Connection error...".$e->getMessage());
      }

    }
  }
}

class Users {

  // Db Property
  private $db;
  // Db __construct Method
  public function __construct()
  {
    $this->db = new Database();
  }
  // Date formate Method
   public function formatDate($date)
   {
     // date_default_timezone_set('Asia/Dhaka');
      $strtime = strtotime($date);
    return date('Y-m-d H:i:s', $strtime);
   }
  // Check Exist Email Address Method
  public function checkExistEmail($email)
  {
    $sql = "SELECT email from  users WHERE email = :email";
    $stmt = $this->db->pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
     $stmt->execute();
    if ($stmt->rowCount()> 0) {
      return true;
    }else{
      return false;
    }
  }
  // Select All User Method
  public function selectAllUserData()
  {
    $sql = "SELECT * FROM users ORDER BY user_id";
    $stmt = $this->db->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
  public function selectAllDisabled()
  {
    $sql = "SELECT * FROM users WHERE isActive = 1";
    $stmt = $this->db->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
  public function selectRoles()
  {
    $sql = "SELECT * FROM roles WHERE isActive = 1";
    $stmt = $this->db->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
  // User login Autho Method
  public function userLoginAutho($email, $password)
  {
    $password = SHA1($password);
    $sql = "SELECT * FROM users WHERE email = :email and password = :password LIMIT 1";
    $stmt = $this->db->pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':password', $password);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }
  // Check User Account Satatus
  public function CheckActiveUser($email)
  {
    $sql = "SELECT * FROM users WHERE email = :email and isActive = :isActive LIMIT 1";
    $stmt = $this->db->pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':isActive', 1);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }
    // User Login Authotication Method
    public function userLoginAuthotication($data)
    {
      $email = $data['email'];
      $password = $data['password'];


      $checkEmail = $this->checkExistEmail($email);

      if ($email == "" || $password == "" ) {
        $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
                <strong>Error:</strong> Email or Password not be Empty!
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
      return $msg;

      }elseif (filter_var($email, FILTER_VALIDATE_EMAIL === FALSE)) {
        $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error !</strong> Invalid email address !</div>';
          return $msg;
      }elseif ($checkEmail == FALSE) {
        $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error !</strong> Email did not Found, use Register email or password please !</div>';
          return $msg;
      }else{


        $logResult = $this->userLoginAutho($email, $password);
        $chkActive = $this->CheckActiveUser($email);

        if ($chkActive == TRUE) {
          $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error !</strong> Sorry, Your account is Diactivated, Contact with Admin !</div>';
            return $msg;
        }elseif ($logResult) {

          Session::init();
          Session::set('login', TRUE);
          Session::set('id', $logResult->user_id);
          Session::set('roleid', $logResult->roleid);
          Session::set('name', $logResult->first_name);
          Session::set('email', $logResult->email);
          Session::set('student_id', $logResult->student_id);
          Session::set('logMsg', '<div class="alert alert-success alert-dismissible mt-3" id="flash-msg">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Success !</strong> You are Logged In Successfully !</div>');
          echo "<script>location.href='orders_view.php';</script>";

        }else{
          $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error !</strong> Email or Password did not Matched !</div>';
            return $msg;
        }
      }
    }
    public function userLogin($data)
    {
      $email = $data['email'];
      $password = $data['password'];


      $checkEmail = $this->checkExistEmail($email);

      if ($email == "" || $password == "" ) {
        $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
                <strong>Error:</strong> Email or Password not be Empty!
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
      return $msg;

      }elseif (filter_var($email, FILTER_VALIDATE_EMAIL === FALSE)) {
        $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error !</strong> Invalid email address !</div>';
          return $msg;
      }elseif ($checkEmail == FALSE) {
        $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error !</strong> Email did not Found, use Register email or password please !</div>';
          return $msg;
      }else{


        $logResult = $this->userLoginAutho($email, $password);
        $chkActive = $this->CheckActiveUser($email);

        if ($chkActive == TRUE) {
          $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error !</strong> Sorry, Your account is Diactivated, Contact with Admin !</div>';
            return $msg;
        }elseif ($logResult) {

          Session::init();
          Session::set('login', TRUE);
          Session::set('id', $logResult->user_id);
          Session::set('roleid', $logResult->roleid);
          Session::set('name', $logResult->first_name);
          Session::set('email', $logResult->email);
          Session::set('student_id', $logResult->student_id);
          Session::set('logMsg', '<div class="alert alert-success alert-dismissible mt-3" id="flash-msg">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Success !</strong> You are Logged In Successfully !</div>');
          echo "<script>location.href='cart.php';</script>";

        }else{
          $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error</strong>Email or Password did not Matched!</div>';
            return $msg;
        }
      }
    }

  // Get Single User Information By Id Method
    public function updateUserByIdInfo($userid, $data)
    {
      $student_id = $data['username'];
      $first_name = $data['first_name'];
      $middle_name = $data['middle_name'];
      $last_name = $data['last_name'];;
      $email = $data['email'];
      $phone = $data['phone'];
      $roleid = $data['roleid'];

      if ($first_name == "" || $last_name == "" || $student_id == ""|| $email == "" || $phone == "" ) 
      {
        $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
  <strong>Error:</strong> Input Fields must not be Empty!
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
          return $msg;
        }elseif (strlen($student_id) < 1) {
          $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
    <strong>Error: </strong> Username is too short, at least 1 Character!
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            return $msg;
        }elseif (filter_var($phone,FILTER_SANITIZE_NUMBER_INT) == FALSE) {
          $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
    <strong>Error: </strong> Enter only Number Characters for Mobile number field! <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            return $msg;


      }elseif (filter_var($email, FILTER_VALIDATE_EMAIL === FALSE)) {
        $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
  <strong>Error: </strong> Invalid email address!  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
          return $msg;
      }
      else  {
        $sql = "UPDATE users SET
          first_name = :first_name,
          middle_name = :middle_name,
          last_name = :last_name,
          student_id = :student_id,
          phone = :phone,
          phone2 = :phone2,
          email = :email,
          email2 = :email2,
          roleid = :roleid
          WHERE user_id = :id";
          $stmt= $this->db->pdo->prepare($sql);
          $stmt->bindValue(':first_name', $first_name);
          $stmt->bindValue(':middle_name', $middle_name);
          $stmt->bindValue(':last_name', $last_name);
          $stmt->bindValue(':student_id', $student_id);
          $stmt->bindValue(':phone', $phone);
          $stmt->bindValue(':phone2', $phone2);
          $stmt->bindValue(':email', $email);
          $stmt->bindValue(':email2', $email2);
          $stmt->bindValue(':roleid', $roleid);
          $stmt->bindValue(':id', $userid);
        $result =   $stmt->execute();

        if ($result) {
          echo "<script>location.href='orders_view.php';</script>";
          Session::set('msg', '<div class="alert alert-success alert-dismissible mt-3" id="flash-msg">
          <strong>Success:</strong> Wow, Your Information updated Successfully!
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>');
        }
        else {
          echo "<script>location.href='orders_view.php';</script>";
          Session::set('msg', '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error !</strong> Data not inserted !</div>');
        }
      }
    }

    // Delete User by Id Method
    public function deleteUserById($remove)
    {
      $sql = "DELETE FROM users WHERE user_id = :id ";
      $stmt = $this->db->pdo->prepare($sql);
      $stmt->bindValue(':id', $remove);
        $result =$stmt->execute();
        if ($result) {
          $msg = '<div class="alert alert-success alert-dismissible mt-3" id="flash-msg">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Success !</strong> User account Deleted Successfully !</div>';
            return $msg;
        }else{
          $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error !</strong> Data not Deleted !</div>';
            return $msg;
        }
    }

    // User Deactivated By Admin
    public function userDeactiveByAdmin($deactive)
    {
      $sql = "UPDATE users SET

       isActive=:isActive
       WHERE user_id = :id";

       $stmt = $this->db->pdo->prepare($sql);
       $stmt->bindValue(':isActive', 1);
       $stmt->bindValue(':id', $deactive);
       $result =   $stmt->execute();
        if ($result) {
          echo "<script>location.href='index.php';</script>";
          Session::set('msg', '<div class="alert alert-success alert-dismissible mt-3" id="flash-msg">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Success !</strong> User account Diactivated Successfully !</div>');

        }else{
          echo "<script>location.href='index.php';</script>";
          Session::set('msg', '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error !</strong> Data not Diactivated !</div>');

            return $msg;
        }
    }


    // User Deactivated By Admin
    public function userActiveByAdmin($active)
    {
      $sql = "UPDATE users SET
       isActive=:isActive
       WHERE user_id = :id";

       $stmt = $this->db->pdo->prepare($sql);
       $stmt->bindValue(':isActive', 0);
       $stmt->bindValue(':id', $active);
       $result =   $stmt->execute();
        if ($result) {
          echo "<script>location.href='index.php';</script>";
          Session::set('msg', '<div class="alert alert-success alert-dismissible mt-3" id="flash-msg">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Success !</strong> User account activated Successfully !</div>');
        }else{
          echo "<script>location.href='index.php';</script>";
          Session::set('msg', '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error !</strong> Data not activated !</div>');
        }
    }

    // Check Old password method
    public function CheckOldPassword($userid, $old_pass)
    {
      $old_pass = SHA1($old_pass);
      $sql = "SELECT password FROM users WHERE password = :password AND user_id =:id";
      $stmt = $this->db->pdo->prepare($sql);
      $stmt->bindValue(':password', $old_pass);
      $stmt->bindValue(':id', $userid);
      $stmt->execute();
      if ($stmt->rowCount() > 0) {
        return true;
      }else{
        return false;
      }
    }

    // Change User pass By Id
    public  function changePasswordBysingelUserId($userid, $data)
    {

      $old_pass = $data['old_password'];
      $new_pass = $data['new_password'];


      if ($old_pass == "" || $new_pass == "" ) {
        $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error !</strong> Password field must not be Empty !</div>';
          return $msg;
      }elseif (strlen($new_pass) < 6) {
        $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error !</strong> New password must be at least 6 character !</div>';
          return $msg;
       }

         $oldPass = $this->CheckOldPassword($userid, $old_pass);
         if ($oldPass == FALSE) {
           $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
     <strong>Error !</strong> Old password did not Matched !</div>';
             return $msg;
         }else{
           $new_pass = SHA1($new_pass);
           $sql = "UPDATE users SET

            password=:password
            WHERE user_id = :id";

            $stmt = $this->db->pdo->prepare($sql);
            $stmt->bindValue(':password', $new_pass);
            $stmt->bindValue(':id', $userid);
            $result =   $stmt->execute();

          if ($result) {
            echo "<script>location.href='index.php';</script>";
            Session::set('msg', '<div class="alert alert-success alert-dismissible mt-3" id="flash-msg">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Success !</strong> Great news, Password Changed successfully !</div>');

          }else{
            $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>Error !</strong> Password did not changed !</div>';
              return $msg;
          }
         }
    }

    
    // Get Single User Information By Id Method
    public function getUserInfoById($userid)
    {
      $sql = "SELECT * FROM users WHERE user_id = :id LIMIT 1";
      $stmt = $this->db->pdo->prepare($sql);
      $stmt->bindValue(':id', $userid);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_OBJ);
      if ($result) {
        return $result;
      }else{
        return false;
      }
    }
    
}
