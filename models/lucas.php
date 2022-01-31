<?php 
    include('db.php');
    function getMenuItems(){
        global $db;
        $stmt = $db->prepare("SELECT * FROM Menu_Items");
        if($stmt->execute()){
            return($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        else{
            return($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    }

    
?>