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
        else if($_POST['action'] == 'deleteItem'){
            if(isset($_POST["menu_item_id"])){
                echo Menu_Item::deleteMenuItemByID($_POST["menu_item_id"]);
            }
            else{
                echo false;
            }
        }
        else if($_POST['action'] == 'getMenuItemsBySectionId'){
            if(isset($_POST["section_id"])){
                echo json_encode(Menu_Item::getMenuItemsBySectionId($_POST["section_id"]));
            }
            else{
                echo false;
            }
        }
        else if($_POST['action'] == 'createOrderIfNoneExists'){
            if(isset($_POST["user_id"])){
                $result = Order::createOrderIfNoneExists($_POST["user_id"]);
                
                echo json_encode($result);
                
            }
            else {
                echo "user_id not set";
            }
        }
        else if($_POST['action'] == 'addOrderItem'){
            if(isset($_POST["order_item"]) && isset($_POST["order_id"])){
                $order_item = postToOrderItem($_POST["order_item"]);
                var_dump($order_item);
                echo $order_item->addItemToOrder();
            }
        }
        else{
            echo 'action not set';
        }
    }

    function postToOrderItem($postItem){
        $order_item = new Order_Item();
        $order_item->order_item_id = $postItem["order_item_id"];
        $order_item->order_id = $_POST["order_id"];
        $order_item->item_id = $postItem["menu_item_id"];
        $order_item->qty = $postItem["qty"];
        foreach($postItem["ingredients"] as $ingredient){
            array_push($order_item->ingredients, postToIngredient($ingredient));
        }
        return($order_item);
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
            foreach($postItem["ingredients"] as $postIngredient){
                $item->addIngredient(postToIngredient($postIngredient));
            }
        }
        return($item);
    }

    function postToIngredient($ingredient){
        $checked = ($ingredient["is_default"] == "true") ? true : false;
        return new Ingredient($ingredient["ingredient_id"], 
                                $ingredient["ingredient_name"], 
                                floatval($ingredient["ingredient_price"]), 
                                $checked);
    }
?>