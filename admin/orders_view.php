<?php
include '../models/sql_functions.php';

if($_SERVER['REQUEST_METHOD'] == "GET")
{
    if(isset($_GET["year"])
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
    $selectedYear = $_POST["year"];
    $selectedMonth = date('F', mktime(0, 0, 0, $_POST['month'], 10));
    $selectedDay = $_POST["day"];
    $selectedMonthInt = $_POST['month'];
    $selectedDate = new DateTime("{$selectedYear}/{$selectedMonthInt}/{$selectedDay}");
    $results = Order::getOrdersByDT($selectedDate->getTimestamp());
    if(isset($_POST['delOrderID']))
    {
        $deleteOID = $_POST['delOrderID'];
        $feedback = Order::deleteOrder($deleteOID);
    }
    else if(isset($_POST['updOrderID']))
    {

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders View</title>
    <script src="https://kit.fontawesome.com/4933cad413.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
    <form method="get" action="orders_view.php" id="dateFilter">
        <input type="hidden" name="dateChanged" value="1">
        <select class="dateFilter" name="year">
            <option disabled selected hidden>Year</option>
            <?php 
                for($i = $selectedYear ; $i>=$selectedYear-40; $i--)
                {
                    if($i == 1 && !isset($selectedYear))
                    {
                        echo '<option disabled selected hidden>Year</option>';
                    }
                    else
                    {
                        echo "<option disabled selected hidden>$selectedYear</option>";
                    }
                    echo '<option value="' . $i . '">' . $i . '</option>';
                }
            ?>
        </select>
        <select name="month" class="dateFilter">
            <option selected hidden><?= $selectedMonth ?></option>
            <option value=1>January</option>
            <option value=2>February</option>
            <option value=3>March</option>
            <option value=4>April</option>
            <option value=5>May</option>
            <option value=6>June</option>
            <option value=7>July</option>
            <option value=8>August</option>
            <option value=9>September</option>
            <option value=10>October</option>
            <option value=11>November</option>
            <option value=12>December</option>
        </select>
        <select name="day" class="dateFilter">
            <?php
                echo "<option selected hidden>{$selectedDay}</option>";
                for($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $selectedMonthInt, $selectedYear); $i++)
                {
                    echo "<option>{$i}</option>";
                }
            ?>
        </select>
    </form>
    <table class="table table-striped">
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
                                    $item->populateIngredientsById();
                                    $out .= "<li>{$item->getItemName()}";
                                    $out .= "<ul>";
                                    $ingredients = $tempOrder->getOrderItems()[$count]->getIngredients();
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
                                if($row['order_status'] == "0")
                                {
                                    echo "<form action='orders_view.php'><input type='hidden' name='orderID' value='true' /><input type='checkbox' name='updateOrderStatus' />Completed</form>";
                                }
                                else if($row['order_status'] == "1")
                                {
                                    echo "<form action='orders_view.php'><input type='hidden' name='orderID' value='true' /><input checked type='checkbox' name='updateOrderStatus' />Completed</form>";
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
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
