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
    <script src="..\models\lucas.js"></script>
    <link rel="stylesheet" href="assets/css/lucas.css">
    <title>Menu View</title>
</head>
<body>
    <?php include '../include/header.php'; ?>
    <h1>Menu Items</h1>
    <table class = "table table-hover table-striped">
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

            </td>
            <td>
                <button class = "btn btn-primary" id = "addNewButton">Add New</button>
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
                        <td><button class = "btn btn-primary" onclick="edit('<?= $item->getMenuItemId()?>')"><i class="fas fa-pencil-alt"></i></button></td>
                        <td><button class = "btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?= $item->getMenuItemId()?>" ><i class="fas fa-trash-alt"></i></button></td>

                    </tr>
                    <div class="modal fade" id="staticBackdrop<?= $item->getMenuItemId()?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">DeleteOrder</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>This is a permenant action do you wish to continue?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                <button type="button" class="btn btn-primary" onclick="del('<?= $item->getMenuItemId()?>')">Yes</button>
                            </div>
                            </div>
                        </div>
                    </div>
            
            <?php } ?>
        </tbody>
    </table>
    
    
    <?php include '../include/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/gh/mgalante/jquery.redirect@master/jquery.redirect.js"></script>
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
            console.log(id);

            $.redirect('menu_add.php', {'id' : String(id)})
        }
        async function del(id){
            let deleted = await Menu_Item.deleteItem(id);
            console.log(deleted)
            if(deleted == '1'){
                window.location.reload();
            }
            else{
                console.log("didn't delete i think")
            }
        }

        

    </script>
</body>
</html>