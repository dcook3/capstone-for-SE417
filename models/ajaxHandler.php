<?php 
    include('lucas.php');
    include('dylan.php');
    if($_SERVER['REQUEST_METHOD']==='POST'){
        switch($_POST['action']){
            case 'addToDB':
                $postItem = $_POST['item'];
                $item = postToMenuItem($postItem);
                echo "hello". $item->addToDB();
                break;
            case 'updateItem':
                $postItem = $_POST['item'];
                $item = postToMenuItem($postItem);
                echo $item->updateMenuItem();
                break;
            case 'deleteIngredient':
                if(isset($_POST["ingredient_id"]) && isset($_POST["menu_item_id"])){
                    echo Ingredient::deleteIngredientById($_POST["ingredient_id"], $_POST["menu_item_id"]);

                }
                else{
                    echo false;
                }
                break;
            case 'deleteItem':
                if(isset($_POST["menu_item_id"])){
                    echo Menu_Item::deleteMenuItemByID($_POST["menu_item_id"]);
                }
                else{
                    echo false;
                }
                break;
            case 'getMenuItemsBySectionId':
                $wthImg = (isset($_POST["wthImg"])) ? (($_POST["wthImg"] == "true") ? true : false) : false;
                if(isset($_POST["section_id"])){
                    echo json_encode(Menu_Item::getMenuItemsBySectionId($_POST["section_id"], $wthImg));
                }
                else{
                    echo false;
                }
                break;
            case 'getMenuItemByID':
                $wthImg = (isset($_POST["wthImg"])) ? (($_POST["wthImg"] == "true") ? true : false) : false;
                if(isset($_POST["menu_item_id"])){
                    echo json_encode(Menu_Item::getMenuItemById($_POST["menu_item_id"], $wthImg));
                }
                else{
                    echo false;
                }
                break;
            case 'createOrderIfNoneExists':
                if(isset($_POST["user_id"])){
                    $result = Order::createOrderIfNoneExists($_POST["user_id"]);
                    
                    echo json_encode($result);
                    
                }
                else {
                    echo "user_id not set";
                }
                break;
            case 'addOrderItem':
                if(isset($_POST["order_item"]) && isset($_POST["order_id"])){
                    $order_item = postToOrderItem($_POST["order_item"]);
                    var_dump($order_item);
                    echo $order_item->addItemToOrder();
                }
                break;
            case 'detailsUpdate':
            {
                $tempOrder = new Order();
                $tempOrder->populateOrderByID($_POST['detOrderID']);
                $out = "<ul>";
                $count = 0;
                foreach($tempOrder->getMenuItems() as $item)
                {
                    $orderItems = $tempOrder->getOrderItems();
                    $out .= "<li><b>{$orderItems[$count]->getQuantity()}x</b> {$item->getItemName()}";
                    $out .= "<ul>";
                    $ingredients = $orderItems[$count]->getIngredients();
                    foreach($ingredients as $ingredient)
                    {
                        $out .= "<li>{$ingredient->getIngredientName()}</li>";
                    }
                    $out .= "</ul>";
                    $count++;
                }
                $out .= "</ul>";
                echo $out;
            }
            default:
                echo 'action not set';
                break;
        }
    }
    /**
     * Converts a javascript Order Item object sent through post to a php object
     */
    function postToOrderItem($postItem){
        $order_item = new Order_Item();
        $order_item->order_item_id = $postItem["order_item_id"];
        $order_item->order_id = $_POST["order_id"];
        $order_item->item_id = $postItem["menu_item_id"];
        $order_item->qty = $postItem["qty"];
        $order_item->price = $postItem["item_price"];
        foreach($postItem["ingredients"] as $ingredient){
            array_push($order_item->ingredients, postToIngredient($ingredient));
        }
        return($order_item);
    }
    /**
     * Converts a javascript Menu Item object sent through post to a php object
     */
    function postToMenuItem($postItem){
        $item = new Menu_Item($postItem['menu_item_id'],
                                new Section($postItem["section"]["section_id"], 
                                $postItem["section"]["section_name"], $postItem["section"]["section_img"]), 
                                $postItem["item_name"], 
                                $postItem["item_description"], 
                                floatval($postItem["item_price"]), 
                                $postItem["item_img"],
                                ($postItem["is_special"] == "true") ? true : false);
        if(isset($postItem["ingredients"])){
            foreach($postItem["ingredients"] as $postIngredient){
                $item->addIngredient(postToIngredient($postIngredient));
            }
        }
        return($item);
    }
    /**
     * Converts a javascript Ingredient object sent through post to a php object
     */
    function postToIngredient($ingredient){
        $checked = ($ingredient["is_default"] == "true") ? true : false;
        return new Ingredient($ingredient["ingredient_id"], 
                                $ingredient["ingredient_name"], 
                                floatval($ingredient["ingredient_price"]), 
                                $checked);
    }
?>