<?php
$levels = 1;
include '../models/sql_functions.php';
date_default_timezone_set("America/New_York");

if($_SERVER['REQUEST_METHOD'] == "GET")
{
    if(!empty($_GET))
    {
        $selectedYear = $_GET["year"];
        $selectedMonth = $_GET["month"];
        $selectedDay = $_GET["day"];
        $selectedMonthInt = date_parse($_GET['month'])['month'];
        $selectedDate = new DateTime("{$selectedYear}/{$selectedMonthInt}/{$selectedDay}");
        $results = Order::getOrdersByDT($selectedDate->getTimestamp());
    }
    else
    {
        $todayDate = new DateTime('NOW');
        $todayDate = getdate($todayDate->getTimestamp());
        $selectedYear = $todayDate["year"];
        $selectedMonth = $todayDate['month'];
        $selectedMonthInt = date_parse($selectedMonth)['month'];
        $selectedDay = $todayDate['mday'];
        $selectedDate = strtotime("{$selectedYear}/{$selectedMonthInt}/{$selectedDay}");
        $results = Order::getOrdersByDT($selectedDate);
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
    $selectedYear = $_POST["year"];
    $selectedMonth = date('F', mktime(0, 0, 0, $_POST['month'], 10));;
    $selectedDay = $_POST["day"];
    $selectedMonthInt = $_POST['month'];
    $selectedDate = new DateTime("{$selectedYear}/{$selectedMonthInt}/{$selectedDay}");
    $results = Order::getOrdersByDT($selectedDate->getTimestamp());
}
include '../include/header.php';
?>
    <h2 class="fw-bold text-center">Orders</h2>
    <div class="d-flex flex-column align-items-center">
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#dateFilterDiv">Toggle Date Filter</button>
        <div class="d-flex mx-3 col-8">
            <div id="dateFilterDiv" class="col-12 collapse">
                <h3 class="fw-bold fs-3">Date Filter</h3>
                <form method="get" action="orders_view.php" id="dateFilter" class="p-2 col-6 border d-flex flex-column">
                    <div class="d-flex flex-row row justify-content-evenly">
                        <div class="form-group col-4">
                            <label for="year" class="form-label">Year</label>
                            <select class="dateFilter form-control" name="year">
                                <option selected hidden>Year</option>
                                <?php 
                                    for($i = $selectedYear ; $i>=$selectedYear-40; $i--)
                                    {
                                        if($i == 1 && !isset($selectedYear))
                                        {
                                            echo '<option selected hidden>Year</option>';
                                        }
                                        else
                                        {
                                            echo "<option selected hidden>$selectedYear</option>";
                                        }
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-4">
                            <label for="month" class="form-label">Month</label>
                            <select name="month" class="dateFilter form-control">
                                <option selected hidden><?= $selectedMonth ?></option>
                                <option>January</option>
                                <option>February</option>
                                <option>March</option>
                                <option>April</option>
                                <option>May</option>
                                <option>June</option>
                                <option>July</option>
                                <option>August</option>
                                <option>September</option>
                                <option>October</option>
                                <option>November</option>
                                <option>December</option>
                            </select>
                        </div>
                        
                        <div class="form-group col-4">
                            <label for="day" class="form-label">Day</label>
                            <select name="day" class="dateFilter form-control">
                                <?php
                                    echo "<option selected hidden>{$selectedDay}</option>";
                                    for($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $selectedMonthInt, $selectedYear); $i++)
                                    {
                                        echo "<option>{$i}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
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
                        <td>soon to be implemented</td>
                        <td>
                            <a class="toggleDetails" href="">Show Details</a>
                            <?php 
                                $tempOrder = new Order();
                                $tempOrder->populateOrderByID($row['order_id']);
                                $out = "<ul>";
                                $count = 0;
                                foreach($tempOrder->getMenuItems() as $item)
                                {
                                    $orderItems = $tempOrder->getOrderItems();
                                    $item->populateIngredientsById();
                                    $out .= "<li><b>{$orderItems[$count]->getQuantity()}x</b> {$item->getItemName()}";
                                    $out .= "<ul>";
                                    $ingredients = $orderItems[$count]->getIngredients();
                                    foreach($ingredients as $ingRow)
                                    {
                                        $out .= "<li>{$ingRow['ingredient_name']}</li>";
                                    }
                                    $out .= "</ul>";
                                    $count++;
                                }
                                $out .= "</ul>";
                                echo $out;
                            ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary modalbtn" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-orderid=<?= $row['order_id'] ?>>
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                        <td>
                            <?php
                                $id = $row['order_id'];
                                if($row['order_status'] == "0")
                                {
                                    echo "<form action='orders_view.php' method='post' class='isCompleted'>
                                            <input type='checkbox' name='orderStatus' value='checked'/><label for='orderStatus'>Completed</label>
                                            <input type='hidden' name='updOrderID' value='$id' /> 
                                            <input type='hidden' name='year' value='$selectedYear'>
                                            <input type='hidden' name='month' value='$selectedMonthInt'>
                                            <input type='hidden' name='day' value='$selectedDay'>
                                        </form>";
                                }
                                else if($row['order_status'] == "1")
                                {
                                    echo "<form action='orders_view.php' method='post' class='isCompleted'>
                                            <input checked type='checkbox' name='orderStatus' /><label for='orderStatus'>Completed</label>
                                            <input type='hidden' name='updOrderID' value='$id' /> 
                                            <input type='hidden' name='year' value='$selectedYear'>
                                            <input type='hidden' name='month' value='$selectedMonthInt'>
                                            <input type='hidden' name='day' value='$selectedDay'>
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
                    <input type="hidden" id="oidInput" name="delOrderID" value="">
                    <input type="hidden" name="year" value="<?= $selectedYear ?>">
                    <input type="hidden" name="month" value="<?= $selectedMonthInt ?>">
                    <input type="hidden" name="day" value="<?= $selectedDay ?>">
                </form>
            </div>
        </div>
    </div>

    <script>
        //Date Filter Code
        var form = document.querySelector('#dateFilter')
        var selects = document.querySelectorAll('select.dateFilter')
        // var filterDiv = document.querySelector('#dateFilterDiv')
        // var get
        // if("<>" == "1"){
        //     get = true;
        // }
        // else{
        //     get = false;
        // }

        // if(get){
        //     filterDiv.classList.remove('collapse')
        // }

        for(let i=0; i<selects.length; i++)
        {
            selects[i].addEventListener('change', e =>{form.submit()})
        }

        if(selects[0].value == "Year" || selects[1].value == "Month")
        {
            selects[2].disabled = true;
        }
        else
        {
            selects[2].disabled = false;
        }

        //Showing/hiding order details code
        var itemLinks = document.querySelectorAll('.toggleDetails');

        if(itemLinks != null)
        {
            for(let i=0; i<itemLinks.length; i++)
            {
                itemLinks[i].parentElement.children[1].classList.add('d-none');

                itemLinks[i].addEventListener(`click`, e => {
                    e.preventDefault();

                    let details = e.target.parentElement.children[1];
                    details.classList.toggle('d-none');
                    if(details.classList.contains('d-none'))
                    {
                        e.target.innerHTML = 'Show Details';
                    }
                    else
                    {
                        e.target.innerHTML = 'Hide Details';
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