<?php 
    $levels = 0;
    $subtotal = 0;
    include 'models/dylan.php';
    include 'models/lucas.php';

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
                        $currentOrder = Order::populateOrderByID($oid);
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
                $currentOrder = Order::populateOrderByID($oid);
            }
        }
        else
        {
            //display no action error
        }
    }
    else
    {
        //How to get current user's id? What to do if not a user?
        $currentOrder = Order::getIncompleteOrderByUserID(1);
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
<a href="#" onclick="window.location.href = window.history.back(1);"><i class="fa fas fa-arrow-left"></i></a>
<h2>Cart</h2>
<?php if($itemsExist && $currentOrder): ?>
    <?php for($i = 0; $i < count($menuItems); $i++): ?>
        <div class="col-6 d-flex flex-column justify-content-between">
            <span><?= $menuItems[$i]->getItemName() ?></span>
            <div class="quantitySelector" data-oid="<?= $currentOrder->getOrderID() ?>">
                <input type="button" value="-">
                <input type="number" min="0" class="quantity" data-oid="<?= $currentOrder->getOrderID() ?>" data-oiid="<?= $orderItems[$i]->getOrderItemID(); ?>" data-action="updateQuantity" value="<?= $orderItems[$i]->getQuantity(); ?>">
                <input type="button" value="+">
            </div>
            <span><?= $orderItems[$i]->getPrice(); ?></span>
            <button class="btn btn-secondary delItemBtn" data-oid="<?= $currentOrder->getOrderID() ?>" data-oiid="<?= $orderItems[$i]->getOrderItemID(); ?>" data-action="deleteItem"><i class="fas fa-trash-alt"></i></a>
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
        <p><a href="menu_view.php">See menu to add items</a></p>
    </div>
<?php endif; ?>

<div>
    <p>Subtotal: <b><?= $subtotal ?></b></p>
    <p>Tax: <b><?= $tax ?></b></p>
    <button>PayPal <b><?= $total ?></b></button>
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