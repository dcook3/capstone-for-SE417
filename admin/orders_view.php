<?php
include '../models/sql_functions.php';

$tempOrder = new Order();

if($_SERVER['REQUEST_METHOD'] == "GET")
{
    if(isset($_GET["year"]) && isset($_GET["month"]) && isset($_GET["day"]))
    {
        $selectedYear = $_GET["year"];
        $selectedMonth = $_GET["month"];
        $selectedDay = $_GET["day"];
        $selectedMonthInt = $_GET['month'];
        $selectedDate = new DateTime("{$selectedYear}/{$selectedMonthInt}/{$selectedDay}");
        $results = $tempOrder->getOrdersByDT($selectedDate->getTimestamp());
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
        $results = $tempOrder->getOrdersByDT($selectedDate);
    }
}

if(isset($_GET['updateOrderStatus']))
{
    $updateOID = $_GET['orderID'];

    //Use update method on a temporary order instance
}

?>
<style>
    table, td{
        border: 1px solid black;
    }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders View</title>
    <script src="https://kit.fontawesome.com/4933cad413.js" crossorigin="anonymous"></script>
</head>
<body>
    <form method="get" action="orders_view.php" id="dateFilter">
        <select class="dateFilter" name="year">
            <option disabled selected hidden>Year</option>
            <?php 
                for($i =$selectedYear ; $i>=$selectedYear-40; $i--)
                {
                    if($i == 1 && !isset($_GET['year']))
                    {
                        echo '<option disabled selected hidden>Year</option>';
                    }
                    if($_SERVER['REQUEST_METHOD'] == "GET")
                    {
                        if(isset($_GET['year']))
                        {
                            if($i == $_GET['year'])
                            {
                                $temp = '<option value="' . $i . '" selected>' . $i . '</option>';
                                echo $temp;
                            }
                            else
                            {
                                $temp = '<option value="' . $i . '">' . $i . '</option>';
                                echo $temp;
                            }
                        }
                        else
                        {
                            $temp = '<option value="' . $i . '">' . $i . '</option>';
                            echo $temp;
                        }
                    }
                    else
                    {
                        $temp = '<option value="' . $i . '">' . $i . '</option>';
                        echo $temp;
                    }
                }
            ?>
        </select>
        <select name="month" class="dateFilter">
            <?php if(isset($_GET['month'])): ?>
                <option selected hidden><?= $_GET['month'] ?></option>
            <?php else: ?>
                <option disabled selected hidden>Month</option>
            <?php endif; ?>
            <option value=1>1</option>
            <option value=2>2</option>
            <option value=3>3</option>
            <option value=4>4</option>
            <option value=5>5</option>
            <option value=6>6</option>
            <option value=7>7</option>
            <option value=8>8</option>
            <option value=9>9</option>
            <option value=10>10</option>
            <option value=11>11</option>
            <option value=12>12</option>
        </select>
        <select name="day" class="dateFilter">
            <?php
            if(isset($_GET['day']))
            {
                echo "<option selected hidden>{$_GET['day']}</option>";
                for($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $_GET['month'], $_GET['year']); $i++)
                {
                    echo "<option>{$i}</option>";
                }
            }
            else
            {
                if(isset($_GET['year']) && isset($_GET['month']))
                {
                    for($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $_GET['month'], $_GET['year']); $i++)
                    {
                        echo "<option>{$i}</option>";
                    }
                }
                else
                {
                    echo "<option>Select Month and Year</option>";
                }
            }
            ?>
        </select>
    </form>
    <table>
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
                            <?php 
                                $tempOrder = new Order();
                                $tempOrder->populateOrderByID($row['order_id']);
                                $out = "<ul>";
                                foreach($tempOrder->getMenuItems() as $item)
                                {
                                    
                                    $item->populateIngredientsById();
                                    $out .= "<li>{$item->getItemName()}";
                                    $out .= "<ul>";
                                    foreach($item->getIngredients() as $ingredient)
                                    {
                                        $out .= "<li>$ingredient->ingredientName</li>"; //Figure out how to get specific ingredients that user orders from menu_item_ingredients bridge
                                    }
                                    $out .= "</ul>";
                                }
                                $out .= "</ul>";
                                echo $out;
                            ?>
                        </td>
                        <td><a href="orders_view.php?orderID=<?= $row['order_id']; ?>"><i class="fas fa-trash-alt fa-2x"></i></a></td>
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

    <script>
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

        var itemLinks = document.querySelector('.orderItems');

        if(itemLinks != null)
        {
            for(let i=0; i<itemLinks.length; i++)
            {
                itemLinks[i].addEventListener(`click`, e => {
                    //This will trigger the showing/hiding of the context box once functionality is implemented
                });
            }
        }
    </script>
</body>
</html>
