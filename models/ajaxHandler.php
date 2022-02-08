<?php 
    include('lucas.php');
    include('dylan.php');
    if($_SERVER['REQUEST_METHOD']==='POST'){
        if($_POST['action'] == 'addToDB'){
            $postItem = $_POST['item'];
            $item = postToMenuItem($postItem);
            echo "hello". $item->addToDB();
            
        }
        else if($_POST['action'] == 'updateItem'){
            $postItem = $_POST['item'];
            $item = postToMenuItem($postItem);
            echo $item->updateMenuItem();
            
        }
        else if($_POST['action'] == 'deleteIngredient'){
            if(isset($_POST["ingredient_id"]) && isset($_POST["menu_item_id"])){
                echo Ingredient::deleteIngredientById($_POST["ingredient_id"], $_POST["menu_item_id"]);

            }
            else{
                echo false;
            }
        }
    }


    function postToMenuItem($postItem){
        $item = new Menu_Item($postItem['menu_item_id'],
                                new Section($postItem["section"]["section_id"], 
                                $postItem["section"]["section_name"]), 
                                $postItem["item_name"], 
                                $postItem["item_description"], 
                                floatval($postItem["item_price"]), 
                                $postItem["item_img"]);
        if(isset($postItem["ingredients"])){
            foreach($postItem["ingredients"] as $ingredientArr){
                $checked = ($ingredientArr["is_default"] == "true") ? true : false;
                var_dump($ingredientArr["is_default"]);
                $item->addIngredient(new Ingredient($ingredientArr["ingredient_id"], 
                                                    $ingredientArr["ingredient_name"], 
                                                    $ingredientArr["ingredient_price"], 
                                                    $checked));
            }
        }
        return($item);
    }
?>