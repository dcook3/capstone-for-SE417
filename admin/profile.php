<?php
include 'include/header.php';
Session::CheckSession();

 ?>

<?php

if (isset($_GET['id'])) {
  $userid = preg_replace('/[^a-zA-Z0-9-]/', '', (int)$_GET['id']);

}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
  $updateUser = $users->updateUserByIdInfo($userid, $_POST);

}
if (isset($updateUser)) {
  echo $updateUser;
}




 ?>

 <div class="card ">
   <div class="card-header">
          <h3>User Profile <span class="float-right"> <a href="index.php" class="btn btn-primary">Back</a> </h3>
        </div>
        <div class="card-body">

    <?php
    $getUinfo = $users->getUserInfoById($userid);
    if ($getUinfo) {






     ?>


          <div style="width:600px; margin:0px auto">

          <form method="POST">
            <div class="form-group">
                <label for="">Creation Time</label>
                <input type="datetime-local" name="created_at" value="<?php echo $getUinfo->created_at; ?>" class="form-control">
              </div>

              <div class="form-group">
                <label for="student_id">Your Student ID</label>
                <input type="text" name="student_id" value="<?php echo $getUinfo->student_id; ?>" class="form-control">
              </div>
              <div class="form-group">
                <label for="first_name">Your first name</label>
                <input type="text" name="first_name" value="<?php echo $getUinfo->first_name; ?>" class="form-control">
              </div>
              <div class="form-group">
                <label for="middle_name">Your middle name</label>
                <input type="text" name="middle_name" value="<?php echo $getUinfo->middle_name; ?>" class="form-control">
              </div>
              <div class="form-group">
                <label for="last_name">Your last name</label>
                <input type="text" name="last_name" value="<?php echo $getUinfo->last_name; ?>" class="form-control">
              </div>
              <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo $getUinfo->phone; ?>" class="form-control">
              </div>
              <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone2" name="phone2" value="<?php echo $getUinfo->phone2; ?>" class="form-control">
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $getUinfo->email; ?>" class="form-control">
              </div>
              <div class="form-group">
                <label for="email2">Email</label>
                <input type="email" id="email2" name="email" value="<?php echo $getUinfo->email2; ?>" class="form-control">
              </div>

              <?php if (Session::get("roleid") == '1') { ?>

              <div class="form-group
              <?php if (Session::get("roleid") == '1' && Session::get("id") == $getUinfo->id) {
                echo "d-none";
              } ?>
              ">
                <div class="form-group">
                  <label for="sel1">Select user Role</label>
                  <select class="form-control" name="roleid" id="roleid">

                  <?php

                if($getUinfo->roleid == '1'){?>
                  <option value="1" selected='selected'>Admin</option>
                  <option value="2">Editor</option>
                  <option value="3">User only</option>
                <?php }elseif($getUinfo->roleid == '2'){?>
                  <option value="1">Admin</option>
                  <option value="2" selected='selected'>Editor</option>
                  <option value="3">User only</option>
                <?php }elseif($getUinfo->roleid == '3'){?>
                  <option value="1">Admin</option>
                  <option value="2">Editor</option>
                  <option value="3" selected='selected'>User only</option>


                <?php } ?>


                  </select>
                </div>
              </div>

          <?php }else{?>
            <input type="hidden" name="roleid" value="<?php echo $getUinfo->roleid; ?>">
          <?php } ?>

              <?php if (Session::get("id") == $getUinfo->user_id) {?>


              <div class="form-group">
                <button type="submit" name="update" class="btn btn-success">Update</button>
                <a class="btn btn-primary" href="changepass.php?id=<?php echo $getUinfo->id;?>">Password change</a>
              </div>
            <?php } elseif(Session::get("roleid") == '1') {?>


              <div class="form-group">
                <button type="submit" name="update" class="btn btn-success">Update</button>
                <a class="btn btn-primary" href="changepass.php?id=<?php echo $getUinfo->id;?>">Password change</a>
              </div>
            <?php } elseif(Session::get("roleid") == '2') {?>


              <div class="form-group">
                <button type="submit" name="update" class="btn btn-success">Update</button>

              </div>

              <?php   }else{ ?>
                  <div class="form-group">

                    <a class="btn btn-primary" href="index.php">Ok</a>
                  </div>
                <?php } ?>


          </form>
        </div>

      <?php }else{

        header('Location:index.php');
      } ?>



      </div>
    </div>


  <?php
  include 'include/footer.php';

  ?>
