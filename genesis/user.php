<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php include __DIR__ . '/functions.php'; ?>
<body>

<form class="form-horizontal" action="user.php" method="post">
            <h2><?=ucWords($action);?> Student</h2>
            <input type="text" name="action" value="<?=$action;?>">
            <input type="text" name="id" value="<?=$id;?>"> 

            <div class="form-group">
                <label class="control-label col-sm-2" for="firstName">Student ID:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="firstName" placeholder="" name="stud_id" value="<?=$stud_id;?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="firstName">Dorm Number:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="firstName" placeholder="" name="dorm_num" value="<?=$dorm_no;?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="firstName">First Name:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="firstName" placeholder="" name="firstName" value="<?=$firstName;?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="last name">Last Name:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="lastName" placeholder="" name="lastName" value="<?=$lastName;?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="last name">Phone:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="lastName" placeholder="" name="phoneNumber" value="<?=$phoneNumber;?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="last name">Phone 2:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="lastName" placeholder="" name="phoneNumber2" value="<?=$phoneNumber2;?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="last name">Email</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="lastName" placeholder="" name="stud_email" value="<?=$stud_email;?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="btnSubmit" class="btn btn-default"><?=ucWords($action);?> Student</button>

                    <?php
                        if($action == "update")
                        {
                    ?>
                        <button type="submit" name="btnDelete" class="btn btn-default" <?=$action=="update"?"":"hidden";?>>Delete Student</button>
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