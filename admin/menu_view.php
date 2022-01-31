<?php
    include("..\models\sql_functions.php")
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
            ?>
                    <tr>
                        
                        <td><?= $item["item_name"]?></td>
                        <td><?= $item["section_id"]?></td>
                        <td><?= $item["item_description"]?></td>
                        <td><?= $item["item_price"]?></td>
                        <td><button class = "editButton" data-id = "<?= $item["item_id"]?>">Edit</button></td>
                        <td><button>Delete</button></td>
                    </tr>
            
            <?php } ?>
        </tbody>
    </table>

    <script>
        var addNewbutton = document.querySelector("#addNewButton");
        var editButtons = document.querySelectorAll(".editButton")
        addNewbutton.addEventListener("click", function(e){
            window.location.replace("localhost/as_capstone/admin/menu_add.php");
        })


        for(let i = 0; i < editButtons.length; i++){
            editButtons[i].addEventListener("click", function(e){
                console.log(e.target.dataset["id"])
            })
        }
        // $.ajax({
        //     type: "POST",
        //     url: "../Webservices/EmployeeService.asmx/GetEmployeeOrders",
        //     data: {id: },
        //     contentType: "application/json; charset=utf-8",
        //     dataType: "json",
        //     success: function(result){
        //         alert(result.d);
        //         console.log(result);
        //     }
        // });

    </script>
</body>
</html>