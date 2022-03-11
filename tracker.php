<?php 
    $levels = 0;
    include 'includes/header.php';
    include 'includes/models/dylan.php';
    include 'includes/models/lucas.php';;

    $currentOrder = new Order();
    
    if(Session::CheckLoginByUser())
    {
        $currentOrder = Order::getIncompleteOrderByUserID(Session::get('id'));

        
    }
    else
    {
        $currentOrder = Order::getIncompleteOrderByUserID(6);
        //header("Location: login.php");
    }
    if($currentOrder == false)
    {
        $currentOrder = Order::getOrderByUserID(6);
    }
    $oid = $currentOrder->getOrderID();
    $uid = 6;
?>
<?= "<span id='oid' class='d-none'>$oid</span><span id='uid' class='d-none'>$uid</span>" ?>
<h2 class="text-center">Order Tracker</h2>
<div class="mt-2 d-flex flex-column justify-content-center align-items-center">
    <div id="trackerMessage" class="bg-light border rounded d-flex flex-column align-items-center p-2">
        <p>Your order is currently:</p>
        <span id="orderStatusSpan">In Progress</span>
        <div id="trackerIcon"><i class="fa-regular fa-2x fa-clock"></i><div>
    </div>
    <div id="orderDetails">
    </div>
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
            data = data.replace("action not set", "")
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