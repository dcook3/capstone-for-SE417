<?php 
    include('lucas.php');
    include('dylan.php');
    if($_SERVER['REQUEST_METHOD']==='POST'){
        if($_POST['action'] == 'addToDB'){
            $postItem = $_POST['item'];
            $item = new Menu_Item($postItem['menu_item_id'],
                                  new Section($postItem["section"]["section_id"], 
                                  $postItem["section"]["section_name"]), 
                                  $postItem["item_name"], 
                                  $postItem["item_description"], 
                                  floatval($postItem["item_price"]), 
                                  $postItem["item_img"]);
            if(isset($postItem["ingredients"])){
                foreach($postItem["ingredients"] as $ingredientArr){
                    $item->addIngredient(new Ingredient($ingredientArr["ingredient_id"], 
                                                        $ingredientArr["ingredient_name"], 
                                                        $ingredientArr["ingredient_price"], 
                                                        ($ingredientArr["ingredient_id"] == "true") ? true : false));
                }
            }
            echo "hello". $item->addToDB();
            
        }

    }

?>