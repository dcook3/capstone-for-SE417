<?php 
    include 'include/sql_functions.php';

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $qty = $_POST['quantity'];
        $oiid = $_POST['orderItemID'];
        if(Order_Item::updateQuantity($qty, $oiid))
        {
            $currentOrder = Order::populateOrderByID($_POST['orderID']);
        }
        else
        {
            //display error
        }

    }

    if(!isset($_GET['orderID']))
    {
        header('Location: menu_view.php');
    }
    else
    {
        // $currentOrder = new Order();
        // //Get order from menu page
        // $workingMenuItems = $currentOrder->getOrderItems();
    }

    $tempOrder = Order::populateOrderByID(5);
?>
<a href="#" onclick="window.location.href = window.history.back(1);"></a>
<h2>Cart</h2>
<?php 
    $count = 0;
    foreach($workingMenuItems as $menuItem): 
        $count++;?>
    <div class="d-flex justify-content-between">
        <span><?= $menuItem->getItemName() ?></span>
        <div>
            <input type="button" value="+">
            <input type="number" min="0" class="quantity" data-oid="<?= $currentOrder->getOrderID() ?>" data-oiid="<?= $currentOrder->getOrderItems()[$count]->getOrderItemID(); ?>" data-action="updateQuantity">
            <input type="button" value="-">
        </div>
        <span><?= $menuItem->getItemPrice(); ?></span>
        <a class="btn btn-secondary">Trash</a>
    </div>
<?php endforeach; ?>
<script>
    var qtyInputs = document.querySelectorAll(".quantity");

    for(let i=0; i < qtyInputs.length; i++){
        qtyInputs[i].addEventListener('change', e => {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "cart.php", true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(JSON.stringify({
                orderID => e.target.dataset.oid,
                orderItemID => e.target.dataset.oiid,
                quantity => e.target.value,
                action => e.target.dataset.action
            }));
        });
    }
</script>