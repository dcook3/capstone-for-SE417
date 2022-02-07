<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php include '../models/sql_functions.php'; ?>
<body>

<form class="form-horizontal" action="user.php" method="post">
            <h2><?=ucWords($action);?> User</h2>
            <input type="text" name="action" value="<?=$action;?>" >
            <input type="text" name="id" value="<?=$id;?>" > 

            <div class="form-group">
                <label class="control-label col-sm-2">Student ID:</label>
                <div class="col-sm-5">
                    <input type="number" class="form-control" name="studentID" value="<?=$student_id;?>" required>
                </div>
            </div>
            <?php
                        if($action == "update")
                        {
                    ?>
            <div class="form-group">
                <label class="control-label col-sm-2" >Dorm Number:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control"  name="dorm_num" value="<?=$dorm_num;?>">
                </div>
            </div>
            <?php } ?>
            <div class="form-group">
                <label class="control-label col-sm-2">First Name:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="firstName" value="<?=$first_name;?>" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" >Middle Name:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control"  name="middleName" value="<?=$middle_name;?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" >Last Name:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control"  name="lastName" value="<?=$last_name;?>" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" >Phone:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="phone" value="<?=$phone;?>" required>
                </div>
            </div>
            <?php
                        if($action == "update")
                        {
                    ?>
            <div class="form-group">
                <label class="control-label col-sm-2">Phone 2:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="phone2" value="<?=$phone2;?>">
                </div>
            </div>
            <?php } ?>
            <div class="form-group">
                <label class="control-label col-sm-2">Email</label>
                <div class="col-sm-5">
                    <input type="email" class="form-control"  name="email" value="<?=$email;?>" required>
                </div>
            </div>
            <?php
                        if($action == "update")
                        {
                    ?>
            <div class="form-group">
                <label class="control-label col-sm-2">Email 2:</label>
                <div class="col-sm-5">
                    <input type="email" class="form-control" name="email2" value="<?=$email2;?>">
                </div>
            </div>
            <?php } ?>
            <?php
                        if($action == "add")
                        {
                    ?>
            <div class="form-group">
                <label class="control-label col-sm-2">Password</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control" name="password" value="<?=$password;?>" required>
                </div>
            </div>
<?php } ?>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="btnSubmit" class="btn btn-default"><?=ucWords($action);?></button>

                    <?php
                        if($action == "update")
                        {
                    ?>
                        <button type="submit" name="btnDelete" class="btn btn-default" <?=$action=="update"?"":"hidden";?>>Delete</button>
                    <?php
                        }
 
                    if (isPostRequest()) 
                    {
                        echo "<h1>".$results."</h1>";
                    }
                    ?>
                </div>
            </div>

        </form>

</body>
</html>