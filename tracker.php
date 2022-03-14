<?php 
    $levels = 0;

    include 'includes/front/top.php';
    include 'includes/front/header_static.php';
    include 'includes/models/dylan.php';
    include 'includes/models/lucas.php';;

    echo '<div class="bg-primary" style="height:85px; width:100%;"></div>';

    $currentOrder = new Order();
    if($_SESSION['USER'] != null)
    {
        $currentOrder = Order::getPaidOrderByUserID($_SESSION['USER']->user_id);
        $oid = $currentOrder->getOrderID();
        $uid = $currentOrder->user_id;
    }
    else
    {
        header("Location: login.php");
    }

    if($currentOrder == false)
    {
        setMessage("An error occured when fetching your order. Please contact administrator or try again later.");
    }

    
?>
<?php
    if($currentOrder != false)
    {
        echo "<span id='oid' class='d-none'>$oid</span><span id='uid' class='d-none'>$uid</span>";
    }
?>
<script src="https://kit.fontawesome.com/4933cad413.js" crossorigin="anonymous"></script>
<h2 class="text-center">Order Tracker</h2>
<div class="mt-2 d-flex flex-column justify-content-center align-items-center">
    <div id="trackerMessage" class="bg-light border rounded d-flex flex-column align-items-center p-2">
        <p>Your order is currently:</p>
        <div id="trackerIcon"><i class="fa-solid fa-2x fa-clock"></i><div>
        <span id="orderStatusSpan">In Progress</span>
    </div> 
</div>
<div id="orderDetails">
</div>
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
<script>

    $.ajax({
        url: 'includes/models/ajaxHandler.php',
        method: "POST",
        data: 
        {
            detOrderID: document.querySelector('#oid').innerText,
            action: "detailsUpdate"
        }
        }).fail(function(e) {console.log(e)})
        .done(function(data)
        {
            document.querySelector('#orderDetails').innerHTML = data;
        }
    );

    $.ajax({
        url: 'includes/models/ajaxHandler.php',
        method: "POST",
        data: 
        {
            userID: document.querySelector('#uid').innerText,
            action: "trackerStatus"
        }
    }).fail(function(e) {console.log(e)})
    .done(function(data)
    {
        document.querySelector('#orderStatusSpan').innerHTML = "Complete"
        document.querySelector('#trackerIcon').innerHTML = "<i class='fa-regular fa-2x fa-circle-check'></i>"
    });

</script>