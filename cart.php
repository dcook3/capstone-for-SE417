<?php 
    $levels = 0;
    $subtotal = 0;
    include 'header.php';
    include 'models/dylan.php';
    include 'models/lucas.php';
    include 'models/Session.php';

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        if(isset($_POST['action']))
        {
            $oid = $_POST['orderID'];
            $oiid = $_POST['orderItemID'];
            switch ($_POST['action']) {
                case "updateQuantity":
                    $qty = $_POST['quantity'];
                    if(Order_Item::updateQuantity($qty, $oiid))
                    {
                        $currentOrder = new Order();
                        $currentOrder = $currentOrder->populateOrderByID($oid);
                        $itemsExist = ($currentOrder == false) ? true : false;
                    }
                    else
                    {
                        //display failed update error
                    }
                    
                    break;
                case "deleteItem":
                    if(Order_Item::deleteItem($oid, $oiid) == false)
                    {
                        //display failed delete error
                    }
                    break;
                
                $currentOrder = new Order();
                $currentOrder = $currentOrder->populateOrderByID($oid);
            }
        }
        else
        {
            //display no action error
        }
    }
    else
    {
        if(Session::CheckLoginByUser())
        {
            $currentOrder = Order::getIncompleteOrderByUserID(Session::get('id'));
        }
        else
        {
            $currentOrder = Order::getIncompleteOrderByUserID(6);
            //header("Location: register.php"); //Pass param to let user know they need an account?
        }
        $itemsExist = ($currentOrder == false) ? false : true;
    }

    if($currentOrder != null)
    {
        $menuItems = $currentOrder->getMenuItems();
        $orderItems = $currentOrder->getOrderItems();
    }
    else
    {
        $menuItems = [];
        $orderItems = [];
    }
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="dylan_styles.css">
<a href="#" onclick="window.location.href = window.history.back(1);"><i class="fa fas fa-arrow-left"></i></a>
<h2>Cart</h2>
<div class="d-flex flex-column align-items-center">
    <?php if($itemsExist && $currentOrder != null): ?>
        <?php for($i = 0; $i < count($menuItems); $i++): ?>
            <div class="m-1 rounded d-flex flex-row align-items-center bg-light border p-1">
                <span><?= $menuItems[$i]->getItemName() ?></span>
                <div class="col-4 quantitySelector d-flex flex-row justify-content-around align-items-center input-group" data-oid="<?= $currentOrder->getOrderID() ?>">
                    <input type="button" class="btn btn-outline-secondary" id="button-addon1" value="-">
                    <input type="number" class="form-control col-2" min="0" class="quantity" data-oid="<?= $currentOrder->getOrderID() ?>" data-oiid="<?= $orderItems[$i]->getOrderItemID(); ?>" data-action="updateQuantity" value="<?= $orderItems[$i]->getQuantity(); ?>">
                    <input type="button" class="btn btn-outline-secondary" id="button-addon2" value="+">
                </div>
                <span><?= $orderItems[$i]->getPrice(); ?></span>
                <a class="text-decoration-none delItemBtn" data-oid="<?= $currentOrder->getOrderID() ?>" data-oiid="<?= $orderItems[$i]->getOrderItemID(); ?>" data-action="deleteItem"><i class="fas fa-trash-alt"></i></a>
            </div>
            <?php
                $subtotal += $orderItems[$i]->getPrice();
            ?>
        <?php endfor; 
            $tax = round(($subtotal * 0.07), 2);
            $total = $subtotal + $tax;
        ?>
    <?php else: ?>
        <div>
            <p><a href="main_menu.php">See menu to add items</a></p>
        </div>
    <?php endif; ?>
</div>


<div>
    <?php if($itemsExist && $currentOrder != null): ?>
        <p>Subtotal: <b><?= $subtotal ?></b></p>
        <p>Tax: <b><?= $tax ?></b></p>
        <button>PayPal <b><?= $total ?></b></button>
    <?php endif; ?>
</div>
<script>
    var qtySelectors = document.querySelectorAll(".quantitySelector");

    for(let i = 0; i < qtySelectors.length; i++)
    {
        qtySelectors[i].children[1].addEventListener('change', e => {
            $.post('cart.php', {
                orderID: e.target.dataset.oid,
                orderItemID: e.target.dataset.oiid,
                quantity: e.target.value,
                action: e.target.dataset.action
            });
        });
        qtySelectors[i].firstElementChild.addEventListener('click', e => {
            qtySelectors[i].children[1].value--;
            $.post('cart.php', {
                orderID: e.target.parentElement.children[1].dataset.oid,
                orderItemID: e.target.parentElement.children[1].dataset.oiid,
                quantity: e.target.parentElement.children[1].value,
                action: e.target.parentElement.children[1].dataset.action
            });
        });
        qtySelectors[i].lastElementChild.addEventListener('click', e => {
            qtySelectors[i].children[1].value++;
            $.post('cart.php', {
                orderID: e.target.parentElement.children[1].dataset.oid,
                orderItemID: e.target.parentElement.children[1].dataset.oiid,
                quantity: e.target.parentElement.children[1].value,
                action: e.target.parentElement.children[1].dataset.action
            });
        });
    }

    var delBtns = document.querySelectorAll('.delItemBtn')

    for(let i = 0; i < delBtns.length; i++)
    {
        delBtns[i].addEventListener('click', e => {
            $.post('cart.php', {
                orderID: e.target.dataset.oid,
                orderItemID: e.target.dataset.oiid,
                quantity: e.target.value,
                action: e.target.dataset.action
            });
        });
    }
</script>
<script src="https://kit.fontawesome.com/4933cad413.js" crossorigin="anonymous"></script>