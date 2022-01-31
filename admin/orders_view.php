<?php
include '../models/sql_functions.php';

if($_SERVER['REQUEST_METHOD'] == "GET")
{
    $results = getOrdersByDT();
}
?>
<form method="get" action="orders_view.php">
    <select class="form-select col" name="year">
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