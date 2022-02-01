<?php
include '../models/sql_functions.php';

if($_SERVER['REQUEST_METHOD'] == "GET")
{
    if(isset($GET["year"]) && isset($GET["month"]) && isset($GET["day"]))
    {
        $currentYear = $GET["year"];
        $currentMonth = $GET["month"];
        $currentDay = $GET["day"];
        // $results = getOrdersByDT();
    }
    
}
else
{
    $todayDate = new DateTime('NOW');
    $todayDate = getdate($todayDate->getTimestamp());
    $currentYear = $todayDate["year"];
    $currentMonth = $todayDate['month'];
    $currentDay = $todayDate['mday'];
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
        <select class="" name="year">
            <option disabled selected hidden>Year</option>
            <?php 
                for($i = $currentYear-40; $i<=$currentYear; $i++)
                {
                    if($i == 1 && !isset($_POST['year']))
                    {
                        echo '<option disabled selected hidden>Year</option>';
                    }
                    if($_SERVER['REQUEST_METHOD'] == "GET")
                    {
                        if(isset($_POST['year']))
                        {
                            if($i == $_POST['year'])
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
        <select name="month">
            <?php if(isset($currentMonth)): ?>
                <option><?= $currentMonth ?></option>
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
        <select>
            <?php
                if(isset($currentYear) && isset($currentMonth))
                {
                    for($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear); $i++)
                    {
                        echo "<option>{$i}</option>";
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
            
        </tbody>
    </table>

    <script>
        var form = document.querySelector('#dateFilter')
        var selects = document.querySelectorAll('select#dateFilter')

        for(let i=0; i<selects.length; i++)
        {
            selects[i].addEventListener('change', e =>{form.submit()})
        }

        if(selects[0].value == "Year" && selects[1].value == "Month")
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
