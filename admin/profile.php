<?php include('includes/front/top.php'); 
if (!isset($_SESSION['ADMIN']['ADMINID'])) {
    redirect("login");
}?>
<?php $levels = 1;
    
include 'includes/front/header.php'; 
?>

