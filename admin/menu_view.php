<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu View</title>
</head>
<body>
    <h1>Menu Items</h1>
    <table>
        <thead>
            <td>
                Menu Item
            </td>
            <td>
                Section
            </td>
            <td>
                Description
            </td>
            <td>
                Price
            </td>
            <td>
                <button id = "addNewButton">Add New</button>
            </td>
        </thead>
        <tbody>
            <?php 
                foreach(getMenuItems() as $item){
                    
                }
            ?>
        </tbody>
    </table>
</body>
</html>