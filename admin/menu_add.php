<?php
    include("..\models\lucas.php");
    $post = false;
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $post = true;
        if(isset($_POST["id"])){
            $item = Menu_Item::getMenuItemByID($_POST["id"]);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title><?= ($post) ? "Edit" : "Add" ?> Menu Item</title>
    <script src="..\models\lucas.js"></script>
</head>
<body>
    <button id = "backBtn">Back</button>
    <h1><?= ($post) ? "Edit" : "Add New" ?> Item</h1>
    <form>
        <p>Item Name:</p>
        <input type = "text" <?= ($post) ? "value = '{$item->getItemName()}'" : ""?>>
        <p>Section:</p>
        <select>
            <?php 
                foreach(Section::getSections() as  $section){
                    if($post && $section->getSectionId() == $item->getSection()->getSectionId()){
                        echo "<option selected>". $section->getSectionName() . "</option>";
                    }
                    else{
                        echo "<option>". $section->getSectionName() . "</option>";
                    }
                }
            ?>
        </select>
        <p>Description:</p>
        <input type = "text" value = "<?= ($post) ? $item->getItemDescription() : ""?>">
        <p>Price:</p>
        <input type="number" min="0.00" max="10000.00" step="0.01" <?= ($post) ? "value = '{$item->getItemPrice()}'" : ""?> />
        


    </form>

    <table>
        <thead>
            <td>
                <h2>Ingredients</h2>
            </td>
            <td></td>
            <td></td>
            <td>
                <button id = "addIngredientBtn">+</button>
            </td>
        </thead>
        <tbody id = "ingredientBody"><?php 
                if($post){
                    foreach($item->getIngredients() as $ingredient){
                        $isDefaultStr = ($ingredient->isDefault()) ? "checked" : ""; 
                        echo  `<tr class = "ingredientRow">`.
                                `<td>{$ingredient->getIngredientName()}</td>`.
                                `<td><input type="number" min="0.00" max="10000.00" step="0.01" {$ingredient->getIngredientPrice()}/></td>`.
                                `<td><input type = "checkbox" name = "isDefault" {$isDefaultStr}/></td>`.
                                `<td><button id = "deleteButton" data-id = "{$ingredient->getIngredientID()}"></td>`.
                              `</tr>`;
                        
                    }
                }
            ?></tbody>
    </table>
    
    <script>
        var backBtn = document.querySelector("#backBtn")
        var addIngredientBtn = document.querySelector("#addIngredientBtn")
        var ingredientBody = document.querySelector("#ingredientBody")
        var templateIngredientHtml = `<tr class = "ingredientRow">`+
                                        `<td><input type = "text"></td>`+
                                        `<td><input type="number" min="0.00" max="10000.00" step="0.01"/></td>`+
                                        `<td><input type = "checkbox" name = "isDefault" /></td>`+
                                        `<td><button id = "deleteButton"></td>`+
                                      `</tr>`
        var post
        if("<?= $post?>" == "true"){
            
        }
        backBtn.addEventListener("click", function(e){
            window.location.replace("menu_view.php")
        })

        addIngredientBtn.addEventListener("click", function(e){
            
            ingredientBody.innerHtml = ingredientBody.innerHTML + templateIngredientHtml;
        })


    </script>
</body>
</html>