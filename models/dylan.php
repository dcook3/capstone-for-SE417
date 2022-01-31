<?php
include 'db.php';
// SQL Date -> PHP Date
// $phpdate = strtotime($sqldate);
// PHP Date -> SQL Date
// $mysqldate = date( 'Y-m-d H:i:s', $phpdate );

function getOrdersByDT($selectedDate)
{
    global $db;
    $results = [];

    $SQLdateStart = date( 'Y-m-d H:i:s', $selectedDate->getTimestamp() );
    $SQLdateEnd = date( 'Y-m-d H:i:s', ($selectedDate->add(new DateInterval('P1D')))->getTimestamp() );

    $SQL = $db->prepare("SELECT * FROM Orders WHERE order_datetime > {$SQLdateStart} AND order_datetime < {$SQLdateEnd} ORDER BY order_status ASC, order_datetime ASC;");
    
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