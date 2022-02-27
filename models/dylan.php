<?php
include 'db.php';
// SQL Date -> PHP Date
// $phpdate = strtotime($sqldate);
// PHP Date -> SQL Date
// $mysqldate = date( 'Y-m-d H:i:s', $phpdate );

class Order_Item
{
    public $order_item_id, $order_id, $item_id, $qty;
    public $ingredients = [];
    
    public function addItemToOrder(){
        global $db;
        if($this->order_item_id == "-1"){
            $stmt = $db->prepare("INSERT INTO order_items (order_id, menu_item_id, qty) VALUES (:order_id, :menu_item_id, :qty)");
            $binds = array(
                ":order_id" => $this->order_id,
                ":menu_item_id" => $this->item_id,
                ":qty" => $this->qty
            );
            if($stmt->execute($binds)){
                $this->order_item_id = $db->lastInsertId();
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

        $SQL = $db->prepare("SELECT * FROM order_items WHERE order_item_id = :oid;");

        $SQL->bindValue(":oid", $oiid, PDO::PARAM_INT);

        if($SQL->execute() && $SQL->rowCount() == 1)
        {
            $results = $SQL->fetchAll(PDO::FETCH_ASSOC)[0];
            $this->order_item_id = $results['order_item_id'];
            $this->order_id = $results['order_id'];
            $this->item_id = $results['menu_item_id'];
            $this->qty = $results['qty'];
            $this->populateIngredientsByOID();
        }
        else
        {
            $results = "A SQL error occured on fetching Orders Items from server.";
        }
        return $results;
    }

    protected function populateIngredientsByOID()
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

    public function getItemID()
    {
        return $this->item_id;
    }

    public function getIngredients()
    {
        return $this->ingredients;
    }

    public function getQuantity()
    {
        return $this->qty;
    }
}

class Order 
{
    public $orderID, $user_id, $first_name, $last_name, $total_price, $order_status;
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
                if($results["order_status"] == "1"){
                    return("1");
                } 
                else{
                    $order = new Order();
                    $order->populateOrderByID($results["order_id"]);
                    return($order);
                }
            }
        }
        else{
            return($stmt->errorInfo());
        }
    }   
    public function addOrderItem(){

    }
    public static function addOrder($user_id){
        global $db;

        $stmt = $db->prepare("INSERT INTO orders (user_id, order_datetime, order_status) VALUES (:user_id, CURDATE(), '0')");
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
        $SQL = $db->prepare("SELECT * FROM orders INNER JOIN users ON users.user_id = orders.user_id WHERE order_datetime >= STR_TO_DATE(:datestart, '%Y-%m-%d %H:%i:%s') AND order_datetime <= STR_TO_DATE(:dateend, '%Y-%m-%d %H:%i:%s') ORDER BY order_status ASC, order_datetime ASC;");

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

    public function populateOrderByID($orderID)
    {
        global $db;
        $results = [];

        //SQL tables got changed around double check names
        $SQL = $db->prepare("SELECT * FROM orders INNER JOIN users ON users.user_id = orders.user_id WHERE order_id = :oid;");

        $SQL->bindValue(":oid", $orderID);
        
        if($SQL->execute() && $SQL->rowCount() == 1)
        {
            $results = $SQL->fetchAll(PDO::FETCH_ASSOC)[0];
            $this->orderID = $results["order_id"];
            $this->first_name = $results["first_name"];
            $this->last_name = $results["last_name"];
            $this->user_id = $results["user_id"];
            $this->order_status = $results["order_status"];
            $oitems = Order_Item::getOrderItemsByOID($results["order_id"]);
            if(gettype($oitems) != 'string')
            {
                foreach($oitems as $OI)
                {
                    $oitem = new Order_Item();
                    $oitem->populateOrderItemByID($OI["order_item_id"]);
                    $item = Menu_Item::getMenuItemByID($OI["menu_item_id"]);
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

    public static function updateOrderStatus($oid, bool $status)
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

    public function getMenuItems()
    {
        return $this->menu_items;
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

