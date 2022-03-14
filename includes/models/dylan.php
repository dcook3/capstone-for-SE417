<?php
include 'db.php';
// SQL Date -> PHP Date
// $phpdate = strtotime($sqldate);
// PHP Date -> SQL Date
// $mysqldate = date( 'Y-m-d H:i:s', $phpdate );

class Order_Item
{
    public $order_item_id, $order_id, $item_id, $qty, $price, $notes;
    public $ingredients = [];
    
    public function addItemToOrder()
    {
        global $db;
        if($this->order_item_id == "-1"){
            $stmt = $db->prepare("INSERT INTO order_items (order_id, menu_item_id, qty) VALUES (:order_id, :menu_item_id, :qty); ");
            $binds = array(
                ":order_id" => $this->order_id,
                ":menu_item_id" => $this->item_id,
                ":qty" => $this->qty,
            );
            $stmt2 = $db->prepare("UPDATE orders SET order_price = (order_price + :order_price) WHERE order_id = :order_id;");
            $binds2 = array(
                ":order_price" => $this->calcPrice(),
                ":order_id" => $this->order_id
            );
            var_dump($binds2);
            if($stmt->execute($binds)){
                $this->order_item_id = $db->lastInsertId();
                if($stmt2->execute($binds2)){
                    foreach($this->ingredients as $ingredient){
                        $ingredientStmt = $db->prepare("INSERT INTO order_item_ingredients (item_ingredient_id, order_item_id) VALUES (:ingredient_id, :order_item_id)");
                        $ingredientBinds = array(
                            ":ingredient_id" => $ingredient->getIngredientId(),
                            ":order_item_id" => $this->order_item_id
                        );
                        if(!$ingredientStmt->execute($ingredientBinds)){
                            return($ingredientStmt->errorInfo());
                        }
                        
    
                    }
                    return(true);
                }
                else{
                    return($stmt->errorInfo());
                }
            
                
            }
            else{
                return($stmt->errorInfo());
            }
        }
        else{
            return("CAN'T ADD ITEM THAT ALREADY EXISTS");
        }
    }

    public static function getOrderItemsByOID($orderID)
    {
        global $db;
        $results = [];

        $SQL = $db->prepare("SELECT * FROM order_items WHERE order_id = :oid;");

        $SQL->bindValue(":oid", $orderID, PDO::PARAM_INT);

        if($SQL->execute() && $SQL->rowCount() > 0)
        {
            $results = $SQL->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            $results = "A SQL error occured on fetching Orders Items from server.";
        }
        return $results;
    }

    public function populateOrderItemByID($oiid)
    {
        global $db;
        $results = [];

        $SQL = $db->prepare("SELECT order_item_id, order_id, order_items.menu_item_id, qty, item_price, item_notes FROM order_items INNER JOIN menu_items ON order_items.menu_item_id= menu_items.menu_item_id WHERE order_items.order_item_id = :oid;");

        $SQL->bindValue(":oid", $oiid, PDO::PARAM_INT);

        if($SQL->execute() && $SQL->rowCount() == 1)
        {
            $results = $SQL->fetchAll(PDO::FETCH_ASSOC)[0];
            $this->order_item_id = $results['order_item_id'];
            $this->order_id = $results['order_id'];
            $this->item_id = $results['menu_item_id'];
            $this->qty = $results['qty'];
            $this->populateIngredientsByOID();
            $this->price = $results['item_price'];
            $this->notes = $results['item_notes'];
        }
        else
        {
            $results = "A SQL error occured on fetching Orders Items from server.";
        }
        return $results;
    }

    public function populateIngredientsByOID()
    {
        global $db;
        $results = [];

        $SQL = $db->prepare("SELECT ingredients.ingredient_id, ingredient_name, ingredient_price, is_default FROM order_item_ingredients INNER JOIN ingredients ON order_item_ingredients.item_ingredient_id = ingredients.ingredient_id WHERE order_item_ingredients.order_item_id = :oid");
        
        $SQL->bindValue(":oid", $this->order_item_id);

        if($SQL->execute() && $SQL->rowCount() > 0)
        {
            foreach($SQL->fetchAll(PDO::FETCH_ASSOC) as $row){
                array_push($this->ingredients, new Ingredient($row["ingredient_id"], $row["ingredient_name"], floatval($row["ingredient_price"]), ($row['is_default'] == '1') ? true : false));
            }
        }
        else
        {
            $results = "A SQL error occured on fetching Orders Item Ingredients from server.";
        }

        return $results;
    } 

    public static function updateQuantity($qty, $oiid){
        global $db;

        $SQL = $db->prepare("UPDATE order_items SET qty = :qty WHERE order_item_id = :oiid");

        $binds = array(
            ":qty" => $qty,
            ":oiid" => $oiid
        );

        if($SQL->execute($binds) && $SQL->rowCount() == 1)
        {
            return true;
        }
        return false;
    }

    public static function deleteItem($orderID, $itemID)
    {
        global $db;

        $SQL = $db->prepare("DELETE FROM order_items WHERE order_item_id = :oiid AND order_id = :oid");

        $binds = array(
            ":oid" => $orderID,
            ":oiid" => $itemID
        );

        if($SQL->execute($binds) && $SQL->rowCount() == 1)
        {
            return true;
        }
        return false;
    }

    public function calcPrice()
    {
        foreach($this->ingredients as $ingredient)
        {
            $this->price += $ingredient->getIngredientPrice();
        }
        $this->price = $this->price *$this->getQuantity();
        return $this->price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getItemID()
    {
        return $this->item_id;
    }

    public function getOrderItemID()
    {
        return $this->order_item_id;
    }

    public function getIngredients()
    {
        return $this->ingredients;
    }

    public function getQuantity()
    {
        return $this->qty;
    }

    public function getNotes(){
        return $this->notes;
    }

    public function setQuantity($_qty){
        $this->qty = $_qty;
    }
}

class Order 
{
    public $orderID, $user_id, $first_name, $last_name, $total_price, $order_status, $order_notes, $order_price, $order_datetime;
    public $order_items = [];
    public $menu_items = [];


    public static function createOrderIfNoneExists($user_id){
        global $db;

        $stmt = $db->prepare("SELECT order_id, order_status FROM orders WHERE user_id = :user_id AND (order_status = 0 OR order_status = 1)");
        $binds = array(
            ":user_id" =>$user_id
        );

        if($stmt->execute($binds)){
            if($stmt->rowCount() == 0){
                return Order::addOrder($user_id);
            }
            else{
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
                $order = new Order();
                $order->populateOrderByID($results["order_id"]);
                return($order);
                
            }
        }
        else{
            return($stmt->errorInfo());
        }
    }   
   
    public static function addOrder($user_id){
        global $db;

        $stmt = $db->prepare("INSERT INTO orders (user_id, order_datetime, order_status, order_price) VALUES (:user_id, CURDATE(), '0', 0)");
        $binds = array(
            ":user_id" => $user_id
        );
        if($stmt->execute($binds)){
            $order = new Order();
            $order->populateOrderByID($db->lastInsertId());
            return($order);
        }
    }
    public static function getOrdersByDT($selectedTS)
    {
        global $db;
        $results = [];

        //SQL tables got changed around double check names
        $SQL = $db->prepare("SELECT * FROM orders INNER JOIN user ON user.user_id = orders.user_id WHERE order_datetime >= STR_TO_DATE(:datestart, '%Y-%m-%d %H:%i:%s') AND order_datetime < STR_TO_DATE(:dateend, '%Y-%m-%d %H:%i:%s') ORDER BY order_status ASC, order_datetime ASC;");

        $SQLdateStart = new DateTime(date( 'Y-m-d H:i:s', $selectedTS));
        $SQLdateEnd = $SQLdateStart;
        $SQLdateStart = date( 'Y-m-d H:i:s', $SQLdateStart->getTimestamp());
        date_add($SQLdateEnd, new DateInterval('P1D'));
        $SQLdateEnd = date( 'Y-m-d H:i:s', $SQLdateEnd->getTimestamp());
        $binds = array(
            ":datestart" => $SQLdateStart,
            ":dateend" => $SQLdateEnd
        );

        if($SQL->execute($binds))
        {
            $results = $SQL->fetchAll(PDO::FETCH_ASSOC);
        }
        else if($SQL->rowCount() == 0 && $db->errorInfo()[0] == '00')
        {
            $results = "The are no records available for this date.";
        }
        else
        {
            $results = "A SQL error occured on fetching Orders from server.";
            print_r($db->errorInfo());
        }

        return ($results);
    }

    public static function getIncompleteOrderByUserID($user_id)
    {
        global $db;
        $results = [];

        $SQL = $db->prepare("SELECT order_id FROM orders WHERE user_id = :uid AND order_status = 0");

        $SQL->bindValue(":uid", $user_id);

        if($SQL->execute() && $SQL->rowCount() == 1)
        {
            $results = $SQL->fetchAll(PDO::FETCH_ASSOC)[0];
            $order = new Order();
            $order->populateOrderByID($results["order_id"]);
            return($order);
        }
        else
        {
            return false;
        }
    }

    public static function getPaidOrderByUserID($user_id)
    {
        global $db;
        $results = [];

        $SQL = $db->prepare("SELECT order_id FROM orders WHERE user_id = :uid AND order_status = 1");

        $SQL->bindValue(":uid", $user_id);

        if($SQL->execute() && $SQL->rowCount() == 1)
        {
            $results = $SQL->fetchAll(PDO::FETCH_ASSOC)[0];
            $order = new Order();
            $order->populateOrderByID($results["order_id"]);
            return($order);
        }
        else
        {
            return false;
        }
    }
    
    public static function getCompleteOrdersByUserID($user_id)
    {
        global $db;
        $results = [];

        $SQL = $db->prepare("SELECT * FROM orders WHERE user_id = :uid AND order_status = 2");

        $SQL->bindValue(":uid", $user_id);

        if($SQL->execute() && $SQL->rowCount() > 0)
        {
            $results = $SQL->fetchAll(PDO::FETCH_ASSOC);
            $orders = array();
            foreach($results as $result){

                $order = new Order();
                $order->orderID = $result["order_id"];
                $order->user_id = $result["user_id"];
                $order->order_status = $result["order_status"];
                $order->order_price = $result["order_price"];
                $order->order_datetime = $result["order_datetime"];
                array_push($orders, $order);
            }
            return($orders);
        }
        else
        {
            return false;
        }
    }

    public static function getOrderByUserID($user_id)
    {
        global $db;
        $results = [];

        $SQL = $db->prepare("SELECT order_id FROM orders WHERE user_id = :uid");

        $SQL->bindValue(":uid", $user_id);

        if($SQL->execute() && $SQL->rowCount() == 1)
        {
            $results = $SQL->fetchAll(PDO::FETCH_ASSOC)[0];
            $order = new Order();
            $order->populateOrderByID($results["order_id"]);
            return($order);
        }
        else
        {
            return false;
        }
    }

    public function populateOrderByID($orderID)
    {
        global $db;
        $results = [];

        //SQL tables got changed around double check names
        $SQL = $db->prepare("SELECT * FROM orders INNER JOIN user ON user.user_id = orders.user_id WHERE order_id = :oid;");

        $SQL->bindValue(":oid", $orderID);
        
        if($SQL->execute() && $SQL->rowCount() == 1)
        {
            $results = $SQL->fetchAll(PDO::FETCH_ASSOC)[0];
            $this->orderID = $results["order_id"];
            $this->first_name = $results["first_name"];
            $this->last_name = $results["last_name"];
            $this->user_id = $results["user_id"];
            $this->order_status = $results["order_status"];
            $this->order_price = $results["order_price"];
            $oitems = Order_Item::getOrderItemsByOID($results["order_id"]);
            if(gettype($oitems) != 'string')
            {
                foreach($oitems as $OI)
                {
                    $oitem = new Order_Item();
                    $oitem->populateOrderItemByID($OI["order_item_id"]);
                    $item = Menu_Item::getMenuItemByID($OI["menu_item_id"], false);
                    array_push($this->order_items, $oitem);
                    array_push($this->menu_items ,$item);
                }
            }
            
        }
        else
        {
            return "A SQL error occured on fetching Order from server with id $orderID.";
        }
    }

    public static function deleteOrder($oid)
    {
        global $db;

        $SQL = $db->prepare("DELETE FROM orders WHERE order_id = :oid");

        $SQL->bindValue(":oid", $oid, PDO::PARAM_INT);

        if($SQL->execute() && $SQL->rowCount() > 0)
        {
            return "Successfully deleted order.";
        }
        else
        {
            return "A SQL error occured while attempting to delete order with id $oid.";
        }
    }

    public static function updateOrderStatus($oid, $status)
    {
        global $db;

        $SQL = $db->prepare("UPDATE orders SET order_status = :status WHERE order_id = :oid");

        $binds = array(
            ":oid" => $oid,
            ":status" => $status
        );

        if($SQL->execute($binds) && $SQL->rowCount() > 0)
        {
            return "Successfully updated order status.";
        }
        else
        {
            return "A SQL error occured while attempting to update order status.";
        }
    }

    public static function updateOrderPrice($uid, $price)
    {
        global $db;

        $SQL = $db->prepare("UPDATE orders SET order_price = :price WHERE user_id = :uid");

        $binds = array(
            ":uid" => $uid,
            ":price" => $price
        );

        if($SQL->execute($binds) && $SQL->rowCount() > 0)
        {
            return "Successfully updated order status.";
        }
        else
        {
            return "A SQL error occured while attempting to update order status.";
        }
    }

    public static function getOrderStatusByUID($uid){
        global $db;

        $SQL = $db->prepare("SELECT order_status FROM orders WHERE user_id = :uid");

        $SQL->bindValue(":uid", $uid);

        if($SQL->execute() && $SQL->rowCount() == 1){
            return $SQL->fetchAll(PDO::FETCH_ASSOC)[0]['order_status'];
        }
        else
        {
            return "SQL Error occured on fetching order status";
        }

    }

    public function calcTotal()
    {
        $total = 0;
        foreach($this->order_items as $item)
        {
            if($item->price == null)
            {
                $item->calcPrice();
            }
            $total += $item->getPrice();
        }
        return $total + round(($total * 0.07), 2);
    }

    public function getMenuItems()
    {
        return $this->menu_items;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setMenuItems($arr)
    {
        $this->menu_items = $arr;
    }

    public function getOrderItems()
    {
        return $this->order_items;
    }

    public function getOrderID()
    {
        return $this->orderID;
    }
}

