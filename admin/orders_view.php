<?php include('includes/front/top.php'); ?>
<?php if (isset($_SESSION['ADMIN']['ADMINID'])) {
	redirect("index");
}
?>
<?php
    $levels = 1;
    $showDetails = false;
    include '../includes/models/sql_functions.php';
    date_default_timezone_set("America/New_York");

    if($_SERVER['REQUEST_METHOD'] == "GET")
    {
        if(!empty($_GET))
        {
            $dateString = $_GET['selectedDate'];
            $selectedDate = new DateTime($dateString);
            $results = Order::getOrdersByDT($selectedDate->getTimestamp());
        }
        else
        {
            $todayDate = new DateTime('NOW');
            $dateString = $todayDate->format('Y-m-d');
            $selectedDate = new DateTime($dateString);
            $results = Order::getOrdersByDT($selectedDate->getTimestamp());
        }
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {   
        if(isset($_POST['delOrderID']))
        {
            $deleteOID = $_POST['delOrderID'];
            $feedback = Order::deleteOrder($deleteOID);
        }
        else if(isset($_POST['updOrderID']))
        {
            if(isset($_POST['orderStatus']))
            {
                $updateOID = $_POST['updOrderID'];
                $feedback = Order::updateOrderStatus($updateOID, true);
            }
            else 
            {
                $updateOID = $_POST['updOrderID'];
                $feedback = Order::updateOrderStatus($updateOID, false);
            }
        }
        else if(isset($_POST['showDetails']))
        {
            $showDetails = true;
            $detailOrderID = $_POST['detOrderID'];
            $detailDateString = $_POST['dateString'];
        }
        $dateString = $_POST['selectedDate'];
        $selectedDate = new DateTime($dateString);
        $results = Order::getOrdersByDT($selectedDate->getTimestamp());
    }
    include '../include/header.php';
?>
    <h2 class="fw-bold text-center">Orders</h2>
    <div class="d-flex flex-column align-items-center">
        <button class="btn btn-secondary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#dateFilterDiv">Toggle Date Filter</button>
        <div class="col-3 collapse" id="dateFilterDiv">
            <form method="get" action="orders_view.php" id="dateFilter" class="p-2 col-6 border d-flex flex-column">
                <label for="selectedDate" class="form-label">Pick day to show orders from:</label>
                <?php if(!isset($_GET['selectedDate'])): ?>
                    <input type="date" name="selectedDate" class="form-control">
                <?php else: ?>
                    <input type="date" name="selectedDate" value="<?= $_GET['selectedDate']; ?>" class="form-control">
                <?php endif; ?>
            </form>
        </div>
    </div>
    <table class="table table-striped table-hover">
        <thead>
            <th>Student Name</th>
            <th>Student ID</th>
            <th>Total Price</th>
            <th>Order Items</th>
            <th>Cancel</th>
            <th>Complete</th>
        </thead>
        <tbody>
            <?php if(gettype($results) != "string"): ?>
                <?php foreach($results as $row): ?>
                    <tr>
                        <td><?= "{$row['first_name']} {$row['last_name']}" ?></td>
                        <td><?= $row['student_id']; ?></td>
                        <td><?= $row['order_price'] ?></td>
                        <td>
                            <a class="toggleDetails" data-oid="<?= $row['order_id'] ?>" data.dateString="<?= $dateString ?>" href="#">Show Details</a>
                            <div class="details<?= $row['order_id']?>"></div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-secondary modalbtn" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-orderid=<?= $row['order_id'] ?>>
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                        <td>
                            <?php
                                $id = $row['order_id'];
                                if($row['order_status'] == "0" || $row['order_status'] == "2")
                                {
                                    echo "<form action='orders_view.php' method='post' class='isCompleted'>
                                            <input type='checkbox' name='orderStatus' value='checked'/><label for='orderStatus'>Completed</label>
                                            <input type='hidden' name='updOrderID' value='$id' /> 
                                            <input type='hidden' name='selectedDate' value='$dateString'>
                                        </form>";
                                }
                                else if($row['order_status'] == "1")
                                {
                                    echo "<form action='orders_view.php' method='post' class='isCompleted'>
                                            <input checked type='checkbox' name='orderStatus' /><label for='orderStatus'>Completed</label>
                                            <input type='hidden' name='updOrderID' value='$id' /> 
                                            <input type='hidden' name='selectedDate' value='$dateString'>
                                        </form>";
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; 
            else:?>
                <tr><td><?= $results ?><td></tr>
            <?php endif; ?>
        </tbody>
    </table>
   
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you wish to delete this order? Once an order is deleted, you cannot go back.
                </div>
                <form class="modal-footer" action="orders_view.php" method="post">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary">Yes</button>
                    <input type="hidden" id="oidInput" name="delOrderID">
                    <input type='hidden' name='selectedDate' value='<?= $dateString; ?>'>
                </form>
            </div>
        </div>
    </div>

    <script>
        //Date Filter Code
        var form = document.querySelector('#dateFilter')
        document.querySelector('input[type=date]').addEventListener(`change`, e =>{
            form.submit();
        })
        

        //Showing/hiding order details code
        var itemLinks = document.querySelectorAll('.toggleDetails');

        if(itemLinks != null)
        {
            for(let i=0; i<itemLinks.length; i++)
            {
                // itemLinks[i].parentElement.children[1].classList.add('d-none');

                itemLinks[i].addEventListener(`click`, e => {
                    if(itemLinks[i].classList.contains('dataObtained'))
                    {
                        itemLinks[i].nextElementSibling.classList.toggle('d-none');
                        if(itemLinks[i].innerHTML == "Hide Details")
                        {
                            itemLinks[i].innerHTML = "Show Details"
                        }
                        else
                        {
                            itemLinks[i].innerHTML = "Hide Details"
                        }
                    }
                    else
                    {
                        $.ajax(
                            {
                                url: '../includes/models/ajaxHandler.php',
                                method: "POST",
                                data: 
                                {
                                    detOrderID: e.target.dataset.oid,
                                    action: "detailsUpdate"
                                }
                            })
                            .fail(function(e) {console.log(e)})
                            .done(function(data)
                            {
                                data = data.replace("action not set", "")
                                itemLinks[i].nextElementSibling.innerHTML = data;
                                itemLinks[i].innerHTML = "Hide Details"
                            })
                        itemLinks[i].classList.add('dataObtained');
                    }
                });
            }
        }

        //Update checkbox code
        var statusForms = document.querySelectorAll('.isCompleted')

        for(let i=0; i<statusForms.length; i++)
        {
            statusForms[i].children[0].addEventListener('change', e => {statusForms[i].submit()})
        }

        //Modal code
        var modalBtns = document.querySelectorAll('.modalbtn');
        var orderIDInput = document.querySelector('#oidInput');

        for(let i=0; i<modalBtns.length; i++)
        {
            modalBtns[i].addEventListener(`click`, e => {
                orderIDInput.value = modalBtns[i].dataset.orderid;
            });
        }
    </script>
</body>
</html>
<?php include '../include/footer.php'; ?>