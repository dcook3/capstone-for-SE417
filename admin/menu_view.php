<?php
    include("..\models\lucas.php");
    $menuItems = Menu_Item::getMenuItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Menu View</title>
    <script src="https://cdn.jsdelivr.net/gh/mgalante/jquery.redirect@master/jquery.redirect.js"></script>
    <script src="..\models\lucas.js"></script>
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
                foreach($menuItems as $item){
            ?>
                    <tr>
                        
                        <td><?= $item->getItemName()?></td>
                        <td><?= $item->getSection()->getSectionName()?></td>
                        <td><?= $item->getItemDescription()?></td>
                        <td><?= $item->getItemPrice()?></td>
                        <td><button onclick="edit('<?= $item->getMenuItemId()?>')">Edit</button></td>
                        <td><button onclick="del('<?= $item->getMenuItemId()?>')">Delete</button></td>
                    </tr>
            
            <?php } ?>
        </tbody>
    </table>

    <script>
        var addNewbutton = document.querySelector("#addNewButton");
        var editButtons = document.querySelectorAll(".editButton")
        addNewbutton.addEventListener("click", function(e){
            window.location.replace("menu_add.php");
        })


        for(let i = 0; i < editButtons.length; i++){
            editButtons[i].addEventListener("click", function(e){
                console.log(e.target.dataset["id"])
            })
        }
        function edit(id){
            $.redirect('menu_add.php', id)
        }
        function del(id){
            Ingredient.deleteIngredientById(id);
            window.location.reload();
        }

        

    </script>
</body>
</html>