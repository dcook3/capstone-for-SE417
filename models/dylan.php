<?php
include 'db.php';
// SQL Date -> PHP Date
// $phpdate = strtotime($sqldate);
// PHP Date -> SQL Date
// $mysqldate = date( 'Y-m-d H:i:s', $phpdate );

class order 
{
    private $order_items, $first_name, $last_name, $total_price;
    
    public function getOrdersByDT($selectedTS)
    {
        global $db;
        $results = [];

        $SQLdateStart = new DateTime(date( 'Y-m-d H:i:s', $selectedTS));
        $SQLdateEnd = date( 'Y-m-d H:i:s', $SQLdateStart->add(new DateInterval('P1D'))->getTimestamp());

        //SQL tables got changed around double check names
        $SQL = $db->prepare("SELECT * FROM orders INNER JOIN users ON users.user_id = orders.user_id WHERE order_datetime > {$SQLdateStart} AND order_datetime < {$SQLdateEnd} ORDER BY order_status ASC, order_datetime ASC;");
        
        if($SQL->execute() && $SQL->rowCount() > 0)
        {
            $results = $SQL->fetchAll(PDO::FETCH_ASSOC);
            $first_name = $results['first_name'];
            $last_name = $results['last_name'];
            
        }
        else
        {
            $results = "A SQL error occured on fetching Orders from server.";
        }

        return ($results);
    }
}