<?php 

include_once "models/Session.php";
Session::init();
spl_autoload_register(function($models) {

  include 'models/'.$models.".php";

});
$users = new Users();
Session::CheckLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
  $register = $users->userRegistration($_POST);
}
if (isset($register)) {
  echo $register;
}

 ?>


 <div class="card ">
   <div class="card-header">
          <h3 class='text-center'>User Registration</h3>
        </div>
        <div class="cad-body">
            <div style="width:600px; margin:0px auto">
            <form method="post">
                <div class="form-group">
                  <label for="username">Student ID</label>
                  <input type="text" name="username"  class="form-control">
                </div>  
                <div class="form-group pt-3">
                  <label for="name">First Name</label>
                  <input type="text" name="firstName"  class="form-control">
                </div>
                <div class="form-group pt-3">
                  <label>Middle Name</label>
                  <input type="text" name="middleName"  class="form-control">
                </div>
                <div class="form-group pt-3">
                  <label>Last Name</label>
                  <input type="text" name="lastName"  class="form-control">
                </div>
                <div class="form-group">
                  <label for="mobile">Phone</label>
                  <input type="text" name="mobile"  class="form-control">
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" name="email"  class="form-control">
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" name="password" class="form-control">
                  <input type="hidden" name="roleid" value="3" class="form-control">
                </div>
                <div class="form-group">
                  <button type="submit" name="register" class="btn btn-success">Register</button>
                </div>
            </form>
          </div>
        </div>
      </div>

      <?php include 'include/footer.php';?>