<?php
include 'db.php';
// SQL Date -> PHP Date
// $phpdate = strtotime($sqldate);
// PHP Date -> SQL Date
// $mysqldate = date( 'Y-m-d H:i:s', $phpdate );

class Order 
{
    private $orderID, $first_name, $last_name, $total_price;
    private $order_items, $menu_items;

    public function getOrdersByDT($selectedTS)
    {
        global $db;
        $results = [];

        //SQL tables got changed around double check names
        $SQL = $db->prepare("SELECT * FROM orders INNER JOIN users ON users.user_id = orders.user_id WHERE order_datetime > :datestart AND order_datetime < :dateend ORDER BY order_status ASC, order_datetime ASC;");

        $SQLdateStart = new DateTime(date( 'Y-m-d H:i:s', $selectedTS));
        $SQLdateEnd = date( 'Y-m-d H:i:s', $SQLdateStart->add(new DateInterval('P1D'))->getTimestamp());

        $binds = array(
            ":datestart" => date( 'Y-m-d H:i:s', $SQLdateStart->getTimestamp()),
            ":dateend" => $SQLdateEnd
        );

        if($SQL->execute($binds) && $SQL->rowCount() == 1)
        {
            $results = $SQL->fetchAll(PFO::FETCH_ASSOC);
        }
        else
        {
            $results = "A SQL error occured on fetching Orders from server.";
        }

        return ($results);
    }

    public function populateOrderByID($orderID)
    {
        global $db;
        $results = [];

        //SQL tables got changed around double check names
        $SQL = $db->prepare("SELECT * FROM orders WHERE order_id = :oid;");

        $SQL->bindValue(":oid", $order_id);
        

        if($SQL->execute($binds) && $SQL->rowCount() == 1)
        {
            $results = $SQL->fetchAll(PDO::FETCH_ASSOC);
            $this->orderID = $results["order_id"];
            $this->first_name = $results["first_name"];
            $this->last_name = $results["last_name"];
            $this->order_items = getOrderItemsByOID($this->orderID);
            $menuItems = [];
            foreach($this->order_items as $OI)
            {
                $item = new Menu_Item(getMenuItemByID($OI["item_id"]));
                $menuItems->array_push($item);
            }
            return [$results, $menuItems];
        }
        else
        {
            return "A SQL error occured on fetching Order from server with id $orderID.";
        }
    }
}

class Order_Item
{
    private $order_item_id, $order_id, $item_id, $qty;

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
    }
}