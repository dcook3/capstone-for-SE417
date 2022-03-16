<?php include('includes/front/top.php'); 
?>

<?php /*if (!isset($_SESSION['ADMIN']['ADMINID'])) {
    redirect("login");
}*/
    $levels = 1;
    
include 'includes/front/header.php'; 
?>
<h2 class="fw-bold text-center">Users</h2>
<table class = "table table-hover table-striped text-center">
        <thead>
            <th>Student ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Created</th>
            <th>Last Update</th>
            <th><button>Add</button></th>
        </thead>
        <tbody>
            <tr>     
                <?php $admin->DisplayAllUsers(); ?>
            </tr>
        </tbody>
<div class="modal fade" id="profileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id = "profileForm">
                    <input type="hidden" id="userID">
                    <div class="form-group">
                        <label class="form-label">First name:</label>
                        <input class="form-control" type = "text" name = "firstName" id = "firstName" value = "<?= (isset($user)) ? $user->fname : ""?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name:</label>
                        <input class="form-control" type = "text" name = "lastName" id = "lastName" value = "<?= (isset($user)) ? $user->lname : ""?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Student ID:</label>
                        <input class="form-control" type = "text" name = "studentID" id = "studentID" value = "<?= (isset($user)) ? $user->student_id : ""?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number:</label>
                        <input class="form-control" type="tel" id="phone" name="phone" title="Phone Number" value = "<?= (isset($user)) ? $user->phone : ""?>">
                    </div>
                    <div class="form-group" style = "display: none">
                        <label class="form-label">Email:</label>
                        <input class="form-control"name = "email" id = "email" value = "<?= (isset($user)) ? $user->email : ""?>" >
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="update" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Update User</button>
                <button id="delete" type="button" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>
<script>
    var profileModal = new bootstrap.Modal(document.getElementById('profileModal'), {backdrop: true, keyboard: false, focus: true})
    var id = document.querySelector('#userID')
    var fName = document.querySelector('#firstName');
    var lName = document.querySelector('#lastName')
    var studentID = document.querySelector('#studentID')
    var phone = document.querySelector('#phone')
    var email = document.querySelector('#email')
    var updateBtn = document.querySelector("#update")
    var deleteBtn = document.querySelector("#delete")

    var edits = document.querySelectorAll(".edit-user");

    for(let i = 0; i < edits.length; i++)
    {
        edits[i].addEventListener('click', e => {
            e.preventDefault()
            $.ajax({
                url: '../includes/models/ajaxHandler.php',
                method: 'POST',
                data: 
                {
                    uid: e.target.dataset.uid,
                    action: "getUser"
                }
            })
            .fail(e => {console.log(e)})
            .done(data => 
            {
                var person = JSON.parse(data)
                id.value = person.user_id
                fName.value = person.first_name
                lName.value = person.last_name
                studentID.value = person.username
                phone.value = person.phone
                email.value = person.email
            })
            profileModal.show()
        })
    }

    updateBtn.addEventListener('click', e => {
        $.ajax({
            url: '../includes/models/ajaxHandler.php',
            method: 'POST',
            data: 
            {
                uid: id.value,
                fName: fName.value,
                lName: lName.value,
                studentID: studentID.value,
                phone: phone.value,
                email: email.value,
                action: 'updateUserBackend'
            }
        })
        .fail(e => {console.log(e)})
        .done(e => {
            window.location.replace("users");
        })
    });

    deleteBtn.addEventListener('click', e => {
        $.ajax({
            url: '../includes/models/ajaxHandler.php',
            method: 'POST',
            data: 
            {
                uid: id.value,
                action: 'deleteUser'
            }
        }).fail(e => {console.log(e)})
        .done(e => {
            window.location.replace("users");
        })
    });
</script>