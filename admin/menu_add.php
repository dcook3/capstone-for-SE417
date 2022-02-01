<?php
    include("..\models\sql_functions.php");
    $post = false;
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $post = true;
        if(isset($_POST["id"])){
            $item = getMenuItemByID($_POST["id"])[0];
            // var_dump($item);
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
    <title>Add Menu Item</title>
    
</head>
<body>
    <button id = "backBtn">Back</button>
    <h1>Add New Item</h1>
    <form>
        <p>Item Name:</p>
        <input type = "text" <?= ($post) ? "value = '{$item["item_name"]}'" : ""?>>
        <p>Section:</p>
        <select>
            <?php 
                foreach(getSections() as $section){
                    if($section["section_id"] == $item["section_id"]){
                        echo "<option selected>". $section["section_name"] . "</option>";
                    }
                    else{
                        echo "<option>". $section["section_name"] . "</option>";
                    }
                }
            ?>
        </select>
        <p>Description:</p>
        <input type = "text" <?= ($post) ? "value = '{$item["item_description"]}'" : ""?>>
        <p>Price:</p>
        <input type="number" min="0.00" max="10000.00" step="0.01" <?= ($post) ? "value = '{$item["item_price"]}'" : ""?> />
        


    </form>

    <table>
        <thead>
            <td>
                <h2>Ingredients</h2>
            </td>
            <td>
                <button id = "addIngredientBtn">+</button>
            </td>
        </thead>
        <tbody>
            
        </tbody>
    </table>
    
    <script>
        var backBtn = document.querySelector("#backBtn")
        backBtn.addEventListener("click", function(e){
            window.location.replace("menu_view.php")
        })
    </script>
</body>
</html>