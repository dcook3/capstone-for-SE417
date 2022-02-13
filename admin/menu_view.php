<?php
    $levels = 1;
    include("..\models\lucas.php");
    $menuItems = Menu_Item::getMenuItems();
include '../include/header.php'; 
?>


<link rel="stylesheet" href="assets/css/lucas.css">
<div id="menuViewBodyWrapper">
    <h1 class = "centeredHeader">Menu Items</h1>
    <table class = "table table-hover table-striped text-center menuItemTable">
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
                        <td>$<?= $item->getItemPrice()?></td>
                        <td>
                            <button class = "btn btn-primary" onclick="edit('<?= $item->getMenuItemId()?>')"><i class="fas fa-pencil-alt"></i></button>
                            <button class = "btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?= $item->getMenuItemId()?>" ><i class="fas fa-trash-alt"></i></button>
                        </td>

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
</div>



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
<?php include '../include/footer.php'; ?>