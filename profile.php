<?php
    include("header.php");
?>
<link rel ="stylesheet" href = "main_lucas.css">
<script src="https://kit.fontawesome.com/4933cad413.js" crossorigin="anonymous"></script>


<div id="profileWrapper">
    <div class="topProfileBtns">
        <a id = "backBtn">
            <i class="fa fas fa-arrow-left"></i>
        </a>
        <a id = "editBtn" >
            <i class="fas fa-pencil-alt"></i>
        </a>
    </div>
    <form>
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
            <label>2nd Phone Number:</label>
            <input type = "text" name = "phone2" id = "phone2">
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
