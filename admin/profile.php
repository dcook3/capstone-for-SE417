<?php include('includes/front/top.php'); ?>
<?php
if (!isset($_SESSION['ADMIN']['ADMINID'])) {
    redirect("login");
}
    $levels = 1;
    
include '../include/header.php'; 
?>