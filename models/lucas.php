<?php 
    include('db.php');
    function getMenuItems(){
        global $db;
        $stmt = $db->prepare("SELECT * FROM Menu_Items");
        if($stmt->execute()){
            return($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        
    }
    function getSections(){
        global $db;
        $stmt = $db->prepare("SELECT * FROM Sections");
        if($stmt->execute()){
            return($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        
    }
    function getMenuItemByID($id){
        global $db;

        $stmt = $db->prepare("SELECT * FROM Menu_Items WHERE item_id = :item_id");
        $binds = Array(":item_id" => $id);

        if($stmt->execute($binds)){
            return($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        else{
            return false;
        }
    }

    
?>