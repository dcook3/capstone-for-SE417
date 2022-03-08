<?php
    include("includes/front/top.php");
    include("includes/front/header_static.php");
    include("models/lucas.php");
    include("include/login.php");
?>
<link rel ="stylesheet" href = "main_lucas.css">
<script src="https://kit.fontawesome.com/4933cad413.js" crossorigin="anonymous"></script>

<div id="headerBackground">

</div>
<div id="profileWrapper">
    <div class="topProfileBtns">
        
        
    </div>
    <form id = "profileForm">
        <div class="form-group">
            <label>First name:</label>
            <input type = "text" name = "firstName" id = "firstName">
        </div>
        <div class="form-group">
            <label>Last Name:</label>
            <input type = "text" name = "lastName" id = "lastName">
        </div>
        <div class="form-group">
            <label>Student ID:</label>
            <input type = "text" name = "studentID" id = "studentID">
        </div>
        <div class="form-group">
            <label>Phone Number:</label>
            <input type = "text" name = "phone" id = "phone">
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type = "text" name = "email" id = "email">
        </div>
        <div class="form-group">
            <label>Dorm Number:</label>
            <input type = "text" name = "dormNum" id = "dormNum">
        </div>


    </form>
    <div>
        <button class = "btn btn-secondary" id="submitUpdateBtn">Submit Update</button>
    </div>
</div>

<script>
    var backBtn = document.querySelector("#backBtn");
    backBtn.classList.remove("btn-hidden")

    backBtn.addEventListener("click", function(e){
        window.location.replace("index.php")
    })
</script>
