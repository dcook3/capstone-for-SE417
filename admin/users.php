<?php include('includes/front/top.php'); ?>
<?php
if (!isset($_SESSION['ADMIN']['ADMINID'])) {
    redirect("login");
}
    $levels = 1;
    
include '../include/header.php'; 
?>
<h2 class="fw-bold text-center">Users</h2>
<table class = "table table-hover table-striped text-center">

        <thead>
            <th>ID</th>
            <th>Student ID</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Created</th>
            <th>Last Update</th>
            <th>Delete</th>
            <th>Edit</th>
        </thead>
        <tbody>
            <tr>     
            <?php $admin->DisplayAllUsers(); ?>
            </tr>
        </tbody>
    </table>