<?php
    include("includes/front/top.php");
    include("includes/front/header_static.php");
    include("models/lucas.php");
    include("include/login.php");
    isset($_SESSION['USER']) ? $user = $_SESSION['USER'] : redirect("login.php");
?>
<link rel ="stylesheet" href = "main_lucas.css">
<script src="https://kit.fontawesome.com/4933cad413.js" crossorigin="anonymous"></script>
<div id="headerBackground">

</div>
<div id="profileWrapper">
    <div class="topProfileBtns">
        
        
    </div>
    <form id = "profileForm" data-id = "<?=$user->user_id;?>">
        <div class="form-group">
            <label>First name:</label>
            <input type = "text" name = "firstName" id = "firstName" value = "<?= $user->fname?>">
        </div>
        <div class="form-group">
            <label>Last Name:</label>
            <input type = "text" name = "lastName" id = "lastName" value = "<?= $user->lname?>">
        </div>
        <div class="form-group">
            <label>Student ID:</label>
            <input type = "text" name = "studentID" id = "studentID" value = "<?= $user->student_id?>">
        </div>
        <div class="form-group">
            <label>Phone Number:</label>
            <input type = "text" name = "phone" id = "phone" value = "<?= $user->phone?>">
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type = "text" name = "email" id = "email" value = "<?= $user->email?>" >
        </div>
        


    </form>
    <div>
        <button class = "btn btn-secondary" id="saveBtn">Save Changes</button>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src = "models/lucas.js"></script>
<script>
    var backBtn = document.querySelector("#backBtn");
    var saveBtn = document.querySelector("#saveBtn");
    var form = document.querySelector("#profileForm")
    var firstName = document.querySelector("#firstName");
    var lastName = document.querySelector("#lastName");
    var studentID = document.querySelector("#studentID");
    var phone = document.querySelector("#phone");
    var email = document.querySelector("#email");
    backBtn.classList.remove("btn-hidden")

    backBtn.addEventListener("click", function(e){
        window.location.replace("index.php")
    })

    saveBtn.addEventListener("click", function(e){
        user = new User(profileForm.dataset["id"], email.value, firstName.value, lastName.value, phone.value, studentID.value)
        user.updateUser();
    })
</script>
