<?php
    $levels = 1;
    include("..\models\lucas.php");
    
    $post = false;
    if($_SERVER['REQUEST_METHOD']==='POST'){
        if(isset($_POST["id"])){
            $post = true;
            $item = Menu_Item::getMenuItemByID($_POST["id"]);
            $item->populateIngredientsById();
        }
    }
    include '../include/header.php';
    Session::CheckSession();
?>
<script  src="../models/lucas.js"></script>
<link rel="stylesheet" href="assets/css/lucas.css">
<table class = "hidden">
    <tbody>
        <tr class = "ingredientRow" id = "templateRow" data-id = "-1">
            <td><input class = "form-control" type = "text" placeholder ="Name"></td>
            <td><input class = "form-control" type="number" min="0.00" max="10000.00" step="0.01" placeholder="Price"/></td>
            <td><div class="isDefaultWrapper"><label for = "isDefault">Default</label><input type = "checkbox" name = "isDefault" /></div></td>
            <td><button class = "btn btn-primary deleteButton" onclick="deleteRow(this.parentElement.parentElement)"><i class="fas fa-trash-alt"></i></button></td>
        </tr>
    </tbody>
</table>
<div class="d-flex justify-content-around flex-row">
    <div id="formWrapper">
        <div id="topWrapper">
            <button id = "backBtn" class = "btn btn-primary"  data-id = "<?= ($post) ? $item->getMenuItemId() : ""?>">Back</button>
            <h1><?= ($post) ? "Edit" : "Add New" ?> Item</h1>
            <p></p>
        </div>
        <form>
            <div class="form-group">
                <label for = "nameInput" class = "form-label">Item Name:</label>
                <input id = "nameInput" name = "nameInput" class = "form-control" type = "text" <?= ($post) ? "value = '{$item->getItemName()}'" : ""?>>
            </div>
            <div class="form-group">
                <label for = "selectInput" class = "form-label">Section:</label>
                <select id = "selectInput" class = "form-control">
                    <?php 
                        foreach(Section::getSections() as $section){
                            if($post && $section->getSectionId() == $item->getSection()->getSectionId()){
                                echo "<option selected value = ".$item->getSection()->getSectionId().">". $section->getSectionName() . "</option>";
                            }
                            else{
                                echo "<option value = ".$section->getSectionId().">". $section->getSectionName() . "</option>";
                            }
                        }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for = "descriptionInput" class = "form-label">Description:</label>
                <textarea name = "descriptionInput" id = "descriptionInput" class = "form-control"><?= ($post) ? $item->getItemDescription() : ""?></textarea>
            </div>
            <div class="form-group">
                <p>Price:</p>
                <input id = "priceInput" type="number" min="0.00" max="10000.00" step="0.01" <?= ($post) ? "value = '{$item->getItemPrice()}'" : ""?> />
            </div>
        </form>
    </div>
    <div id="ingredientWrapper">
        <table id = "ingredientTable" class = "table table-hover table-striped text-center">
            <thead id = "ingredientHeader">
                <tr>
                    <td>
                        <h2>Ingredients</h2>
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                        <button id = "addIngredientBtn" class = "btn btn-primary">+</button>
                    </td>
                </tr>
                
            </thead>
        
            <tbody id = "ingredientBody">
                <?php 
                    if($post){
                        foreach($item->getIngredients() as $key=>$ingredient){
                            $isDefaultStr = ($ingredient->getIsDefault()) ? "checked" : ""; 
                            ?>
                            <tr class = 'ingredientRow' data-id = '<?=$ingredient->getIngredientId()?>'>
                                <td><input type = 'text' placeholder ='Name' value = '<?=$ingredient->getIngredientName()?>'/></td>
                                <td><input type='number' min='0.00' max='10000.00' step='0.01' value = '<?=$ingredient->getIngredientPrice()?>'/></td>
                                <td>
                                    <div class='isDefaultWrapper'>
                                        <label for = 'isDefault'>Default</label>
                                        <input type = 'checkbox' name = 'isDefault' $isDefaultStr/>
                                    </div>
                                </td>
                                <td>
                                    <button id = 'deleteButton' class = 'btn btn-primary' onclick='deleteRow(this.parentElement.parentElement)'><i class='fas fa-trash-alt'></i></button>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                ?>
            </tbody>
        </table>
        <div id="doneBtnWrapper">
            <p></p>
            <button id = "doneBtn" class = "btn btn-primary">Done</button>
        </div>
    </div>
    
</div>

<script>
    var backBtn = document.querySelector("#backBtn")
    var addIngredientBtn = document.querySelector("#addIngredientBtn")
    var ingredientBody = document.querySelector("#ingredientBody")
    var templateRow = document.querySelector("#templateRow")
    var doneBtn = document.querySelector("#doneBtn")

    //form inputs
    var nameInput = document.querySelector("#nameInput");
    var selectInput = document.querySelector("#selectInput");
    var descriptionInput = document.querySelector("#descriptionInput");
    var priceInput = document.querySelector("#priceInput");
    
    var post
    if("<?= $post?>" == "1"){
        post = true;
    }
    else{
        post = false;
    }

    backBtn.addEventListener("click", function(e){
        window.location.replace("menu_view.php")
    })

    addIngredientBtn.addEventListener("click", function(e){
        addRow();
    });
    doneBtn.addEventListener("click", async function(e){
        let selectedOption = selectInput.children[selectInput.selectedIndex];
        item = new Menu_Item((post) ? body.dataset["id"] : "-1", 
                                new Section(selectedOption.value, selectedOption.innerText),
                                nameInput.value,
                                descriptionInput.value,
                                priceInput.value,
                                "")
        for(let i = 0; i < ingredientBody.children.length; i++){
            let ingredientRow = ingredientBody.children[i];
            let price = (ingredientRow.children[1].children[0].value == "") ? "0" : ingredientRow.children[1].children[0].value;
        
            item.addIngredient(new Ingredient(ingredientRow.dataset["id"],
                                                ingredientRow.children[0].children[0].value,
                                                price,
                                                ingredientRow.children[2].children[1].checked))
            
        }
        if(!post){
            item.addToDatabase();
        }
        else{
            item.updateItem();
        }
        setTimeout(function(){
            window.location.replace("menu_view.php")
        }, 10);
    })

    function addRow(){
        ingredientBody.appendChild(templateRow.cloneNode(true))
    }
    function deleteRow(row){
        if(row.dataset["id"] != "-1" && confirm("This action is permenant do you wish to proceed")){
            ingredientBody.removeChild(row);
            if(row.dataset["id"] != "-1" || row.dataset["id"] != ""){
                Ingredient.deleteIngredient(row.dataset["id"], backBtn.dataset["id"])
            }
        }
        else{
            ingredientBody.removeChild(row);
        }
        
    }
    
    


</script>
<?php include '../include/footer.php'; ?>