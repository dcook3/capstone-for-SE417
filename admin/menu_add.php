<?php
    include("..\models\lucas.php");
    $post = false;
    if($_SERVER['REQUEST_METHOD']==='POST'){
        if(isset($_POST["id"])){
            $post = true;
            $item = Menu_Item::getMenuItemByID($_POST["id"]);
            $item->populateIngredientsById();
        }
        
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="..\models\lucas.js"></script>
    <title><?= ($post) ? "Edit" : "Add" ?> Menu Item</title>
    <link rel="stylesheet" href="assets/css/lucas.css">
</head>
<body data-id = "<?= ($post) ? $item->getMenuItemId() : ""?>">
    <table class = "hidden">
        <tbody>
            <tr class = "ingredientRow" id = "templateRow" data-id = "-1">
                <td><input type = "text" placeholder ="Name"></td>
                <td><input type="number" min="0.00" max="10000.00" step="0.01" placeholder="Price"/></td>
                <td><label for = "isDefault">Default Option</label><input type = "checkbox" name = "isDefault" /></td>
                <td><button class = "btn btn-primary deleteButton" onclick="deleteRow(this.parentElement.parentElement)"><i class="fas fa-trash-alt"></i></button></td>
            </tr>
        </tbody>
    </table>
    <?php include '../include/header.php'; ?>
    <button id = "backBtn" class = "btn btn-primary">Back</button>
    <div class="d-flex justify-content-around flex-row">
        <div class="formWrapper">
            
            <h1><?= ($post) ? "Edit" : "Add New" ?> Item</h1>
            <form>
                <p>Item Name:</p>
                <input id = "nameInput" type = "text" <?= ($post) ? "value = '{$item->getItemName()}'" : ""?>>
                <p>Section:</p>
                <select id = "selectInput">
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
                <p>Description:</p>
                <input id = "descriptionInput" type = "text" value = "<?= ($post) ? $item->getItemDescription() : ""?>">
                <p>Price:</p>
                <input id = "priceInput" type="number" min="0.00" max="10000.00" step="0.01" <?= ($post) ? "value = '{$item->getItemPrice()}'" : ""?> />
                


            </form>
        </div>
        <div class="ingredientWrapper">
            <table >
                <thead>
                    <td>
                        <h2>Ingredients</h2>
                    </td>
                    <td></td>
                    <td></td>
                    <td >
                        <button id = "addIngredientBtn" class = "btn btn-primary">+</button>
                    </td>
                </thead>
                <tbody id = "ingredientBody">
                <?php 
                    if($post){
                        foreach($item->getIngredients() as $key=>$ingredient){
                            $isDefaultStr = ($ingredient->getIsDefault()) ? "checked" : ""; 
                            echo("<tr class = 'ingredientRow' data-id = '{$ingredient->getIngredientId()}'><td><input type = 'text' placeholder ='Name' value = '{$ingredient->getIngredientName()}'/></td><td><input type='number' min='0.00' max='10000.00' step='0.01' value = '{$ingredient->getIngredientPrice()}'/></td><td><label for = 'isDefault'>Default Option</label><input type = 'checkbox' name = 'isDefault' {$isDefaultStr}/></td><td><button id = 'deleteButton' class = 'btn btn-primary' onclick='deleteRow(this.parentElement.parentElement)'>Delete</button></td></tr>");
                            
                        }
                    }
                ?>
                </tbody>
            </table>
            <button id = "doneBtn" class = "btn btn-primary">Done</button>
        </div>
        
    </div>
    <?php include '../include/footer.php'; ?>
    <script>
        var body = document.querySelector("body")
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
            if(confirm("This action is permenant do you wish to proceed")){
                ingredientBody.removeChild(row);
                if(row.dataset["id"] != "-1" || row.dataset["id"] != ""){
                    Ingredient.deleteIngredient(row.dataset["id"], body.dataset["id"])
                }
            }
            
        }
        
       


    </script>
</body>
</html>