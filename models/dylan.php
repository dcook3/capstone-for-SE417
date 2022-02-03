<?php
include 'db.php';
// SQL Date -> PHP Date
// $phpdate = strtotime($sqldate);
// PHP Date -> SQL Date
// $mysqldate = date( 'Y-m-d H:i:s', $phpdate );

function getOrdersByDT($selectedTS)
{
    global $db;
    $results = [];

    $SQLdateStart = date( 'Y-m-d H:i:s', $selectedTS);
    $SQLdateEnd = date( 'Y-m-d H:i:s', ($SQLdateStart->sub(new DateInterval('P1D'))) );


    //SQL tables got changed around double check names

    $SQL = $db->prepare("SELECT * FROM orders INNER JOIN users ON User_Info.user_id = Orders.user_id WHERE order_datetime > {$SQLdateStart} AND order_datetime < {$SQLdateEnd} ORDER BY order_status ASC, order_datetime ASC;");
    
    if($SQL->execute() && $SQL->rowCount() > 0)
    {
        $results = $SQL->fetchAll(PDO::FETCH_ASSOC);
    }
    else
    {
        $results = "A SQL error occured on fetching Orders from server.";
    }

    return ($results);
}

function getOrders()
{
    global $db;
    $results = [];

    $SQL = $db->prepare("SELECT * FROM orders INNER JOIN User_info ON User_Info.user_id = Orders.user_id ORDER BY order_status ASC, order_datetime ASC;");
    
    if($SQL->execute() && $SQL->rowCount() > 0)
    {
        $results = $SQL->fetchAll(PDO::FETCH_ASSOC);
    }
    else
    {
        $results = "A SQL error occured on fetching Orders from server.";
    }

    return ($results);
}

?>