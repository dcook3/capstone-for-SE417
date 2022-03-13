<?php 
    $levels = 0;
    $subtotal = 0;
    
    include 'includes/front/top.php';
    include 'includes/front/header_static.php';
    include 'includes/models/dylan.php';
    include 'includes/models/lucas.php';
    echo '<div class="bg-primary" style="height:85px; width:100%;"></div>';

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        if(isset($_POST['action']))
        {
            $oid = $_POST['orderID'];
            if(isset($_POST['orderItemID']))
            {
                $oiid = $_POST['orderItemID'];
            }
            
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
                        setMessage("An error occured when updating quantity. Please contact administrator or try again later.");
                    }
                    
                    break;
                case "deleteItem":
                    if(Order_Item::deleteItem($oid, $oiid) == false)
                    {
                        setMessage("An error occured whilst deleting an item. Please contact administrator or try again later.");
                    }
                    break;
                case "updateStatus":
                    Order::updateOrderStatus($oid, 1);
                    header("Location: tracker.php");
                    break;
                
                $currentOrder = new Order();
                $currentOrder = $currentOrder->populateOrderByID($oid);
            }
        }
        else
        {
            setMessage("An error occured when determining action. Please contact administrator or try again later.");
        }
    }
    else
    {
        if($_SESSION['USER'] != null)
        {
            $currentOrder = Order::getIncompleteOrderByUserID($_SESSION["USER"]->user_id);

            if($currentOrder->order_status == 1)
            {
                header("Location: tracker.php");
            }
        }
        else
        {
            header("Location: login.php"); 
        }
        $itemsExist = ($currentOrder == false) ? false : true;
    }

    if($currentOrder != null && $itemsExist)
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
<link rel="stylesheet" href="main_lucas.css">
<h2 class="ms-2">Cart</h2>
<div class="d-flex flex-column align-items-center">
    <?php if($itemsExist && $currentOrder != null): ?>
        <?php for($i = 0; $i < count($menuItems); $i++): ?>
            <div class="m-1 col-10 rounded d-flex flex-row align-items-center bg-light border p-1">
                <span><?= $menuItems[$i]->getItemName() ?></span>
                <div class="quantitySelector mx-2 d-flex flex-row justify-content-around align-items-center input-group" data-oid="<?= $currentOrder->getOrderID() ?>">
                    <input type="button" class="btn btn-secondary" id="button-addon1" value="-">
                    <input type="number" class="form-control col-2" min="0" class="quantity" data-oid="<?= $currentOrder->getOrderID() ?>" data-oiid="<?= $orderItems[$i]->getOrderItemID(); ?>" data-action="updateQuantity" value="<?= $orderItems[$i]->getQuantity(); ?>">
                    <input type="button" class="btn btn-secondary" id="button-addon2" value="+">
                </div>
                <span class="me-1"  >$<span data-price="<?= $orderItems[$i]->getPrice()?>" data-quantity="<?= $orderItems[$i]->getQuantity()?>" class="price"><?= $orderItems[$i]->getPrice() * $orderItems[$i]->getQuantity(); ?></span></span>
                <a class="btn btn-secondary text-decoration-none delItemBtn" data-oid="<?= $currentOrder->getOrderID() ?>" data-oiid="<?= $orderItems[$i]->getOrderItemID(); ?>" data-action="deleteItem"><i class="fas fa-trash-alt"></i></a>
            </div>
            <?php $subtotal += $orderItems[$i]->getPrice(); ?>
        <?php endfor; ?>
    <?php else: ?>
        <div>
            <p><a href="main_menu.php">See menu to add items</a></p>
        </div>
    <?php endif; ?>
</div>

<div class="mt-5">
    <div class="ms-4">
        <?php if($itemsExist && $currentOrder != null): ?>
            <div class="mb-2">Subtotal: $<span id="subtotal"></span></div>
            <div class="mb-2">Tax: $<span id="tax"></span></div>
        <?php endif; ?>
    </div>
    <div class="d-flex justify-content-center">
        <button id="finalizeBtn" class ='btn btn-secondary col-10' data-oid="<?= $currentOrder->getOrderID() ?>">
            <div class="d-flex justify-content-between">
                <span class="d-inline-block">Finalize Order</span>
                <span class="d-inline-block">
                    Total: $<span id="total"></span>
                </span>
            </div>
        </button>
    </div>
</div>
<script>
    var qtySelectors = document.querySelectorAll(".quantitySelector");
    var spans = document.querySelectorAll(".price");
    var pageSubtotal = document.querySelector("#subtotal");
    var pageTax = document.querySelector("#tax");
    var pageTotal = document.querySelector("#total");
    var subtotal = 0;
    
    //Initial calulation using DB vals
    for(let i=0; i < spans.length; i++)
    {
        subtotal += Number(qtySelectors[i].children[1].value) * Number(spans[i].dataset.price);
    }
    pageSubtotal.innerHTML = subtotal;
    let firstTax = (subtotal * 0.07).toFixed(2);
    pageTax.innerHTML = firstTax;
    pageTotal.innerHTML = (Number(subtotal) + Number(firstTax)).toFixed(2);

    
    
    for(let i = 0; i < qtySelectors.length; i++)
    {
        //Post Quantity on Quantity Selector Change Event
        qtySelectors[i].children[1].addEventListener('change', e => {
            $.post( 'cart.php',
            {
                orderID: e.target.dataset.oid,
                orderItemID: e.target.dataset.oiid,
                quantity: e.target.value,
                action: e.target.dataset.action
            })
        });

        //Subtract Quantity Event
        qtySelectors[i].firstElementChild.addEventListener('click', e => {
            if(Number(qtySelectors[i].children[1].value) > 1)
            {
                qtySelectors[i].children[1].value--;
                

                //Calculate total item price and display
                let temp = Number(qtySelectors[i].children[1].value) * Number(spans[i].dataset.price);
                spans[i].innerHTML = `${Number(temp.toFixed(2))}`

                //Calculate subtotal
                subtotal -= Number(spans[i].dataset.price);
                let tax = (subtotal * 0.07).toFixed(2);

                //Update subtotal and tax onto page
                pageSubtotal.innerHTML = subtotal.toFixed(2);
                pageTax.innerHTML = Number(subtotal * 0.07).toFixed(2);
                pageTotal.innerHTML = (Number(subtotal) + Number(tax)).toFixed(2);
            }
        });

        //Add Quantity Event
        qtySelectors[i].lastElementChild.addEventListener('click', e => {
            if(Number(qtySelectors[i].children[1].value) < 10)
            {
                //Calculate total item price and display
                qtySelectors[i].children[1].value++;
                let temp = Number(qtySelectors[i].children[1].value) * Number(spans[i].dataset.price);
                spans[i].innerHTML = `${Number(temp.toFixed(2))}`

                //Calculate subtotal
                subtotal += Number(spans[i].dataset.price);
                let tax = (subtotal * 0.07).toFixed(2);

                //Update subtotal and tax onto page
                pageSubtotal.innerHTML = subtotal.toFixed(2);
                pageTax.innerHTML = Number(subtotal * 0.07).toFixed(2);
                pageTotal.innerHTML = (Number(subtotal) + Number(tax)).toFixed(2);
            }
        });
    }

    //Delete buttons functionality
    var delBtns = document.querySelectorAll('.delItemBtn')

    for(let i = 0; i < delBtns.length; i++)
    {
        //Post to tell php to delete
        delBtns[i].addEventListener('click', e => {
            $.post('cart.php', {
                orderID: e.target.dataset.oid,
                orderItemID: e.target.dataset.oiid,
                quantity: e.target.value,
                action: e.target.dataset.action
            });
        });
    }

    //Functionality for finalizing order
    document.querySelector("#finalizeBtn").addEventListener('click', e =>{
        $.post('cart.php', {
            orderID: e.target.dataset.oid,
            action: "updateStatus"
        });
    })
</script>
<script src="https://kit.fontawesome.com/4933cad413.js" crossorigin="anonymous"></script>