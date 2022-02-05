<?php
include '../models/sql_functions.php';

if($_SERVER['REQUEST_METHOD'] == "GET")
{
    if(isset($GET["year"]) && isset($GET["month"]) && isset($GET["day"]))
    {
        $selectedYear = $GET["year"];
        $selectedMonth = $GET["month"];
        $selectedDay = $GET["day"];
        $selectedMonthInt = date_parse($_GET['month'])['month'];
        $selectedDate = strtotime("{$selectedYear}/{$selectedMonthInt}/{$selectedDay}");
        $results = getOrdersByDT($selectedDate);
    }
    else
    {
        $todayDate = new DateTime('NOW');
        $todayDate = getdate($todayDate->getTimestamp());
        $currentYear = $todayDate["year"];
        $currentMonth = $todayDate['month'];
        $currentDay = $todayDate['mday'];
        $results = getOrders();
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
</head>
<body>
    <form method="get" action="orders_view.php" id="dateFilter">
        <select class="dateFilter" name="year">
            <option disabled selected hidden>Year</option>
            <?php 
                for($i =$currentYear ; $i>=$currentYear-40; $i--)
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
            <option>January</option>
            <option>Feburary</option>
            <option>March</option>
            <option>April</option>
            <option>May</option>
            <option>June</option>
            <option>July</option>
            <option>August</option>
            <option>September</option>
            <option>October</option>
            <option>November</option>
            <option>Decemeber</option>
        </select>
        <select name="day" class="dateFilter">
            <?php
            if(isset($_GET['day']))
            {
                echo "<option selected hidden>{$_GET['day']}</option>";
                for($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date_parse($_GET['month'])['month'], $_GET['year']); $i++)
                {
                    echo "<option>{$i}</option>";
                }
            }
            else
            {
                if(isset($_GET['year']) && isset($_GET['month']))
                {
                    for($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date_parse($_GET['month'])['month'], $_GET['year']); $i++)
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
            <?php foreach($results[0] as $row): ?>
                <tr>
                    <td><?= "{$row['first_name']} {$row['last_name']}" ?></td>
                    <td><?= $row['student_id'] ?></td>
                    <td>soon to be implemented</td>
                    <td></td>
                    <!-- PHP call to second query withing index 1 on result array-->
                </tr>
            <?php endforeach; ?>
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
    </script>
</body>
</html>
