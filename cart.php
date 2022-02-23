<?php 
include 'include/sql_functions.php';

if(!isset($_GET['orderID']))
{
    header('Location: menu_view.php');
}
else{
    $currentOrder = new Order();
    //Get order from menu page
    $workingMenuItems = $currentOrder->getOrderItems();
}

//On post update PHP object after getting from DB
?>
<a href="#" onclick="window.location.href = window.history.back(1);"><-</a>
<h2>Cart</h2>
<?php foreach($workingMenuItems as $menuItem): ?>
    <div class="d-flex justify-content-between"> 
        <span><?= $menuItem->getItemName() ?></span>
        <form id="quantitySelector">
            <input type="hidden" name="orderID" value="<?= $currentOrder->getOrderID() ?>">
            <input type="button" value="+">
            <input type="number" min="0">
            <input type="button" value="-">
        </form>
        <span><?= $menuItem->getItemPrice(); ?></span>
        <a class="btn btn-secondary">Trash</a>
    </div>
<?php endforeach; ?>