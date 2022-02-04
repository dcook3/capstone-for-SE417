<?php 
    include('db.php');

    class Menu_Item{
        private $item_id;
        private $section_id;
        private $item_name;
        private $item_description;
        private $item_price;
        private $item_img;
        private $itemIngredients;
        function __construct($_item_id){
            global $db;
            $this->item_id = $_item_id;

            

            $stmt = $db->prepare("SELECT * FROM menu_items WHERE item_id = :item_id");
            $binds = Array(":item_id" => $this->item_id);

            if($stmt->execute($binds)){
                $response = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
                $this->section_id = $response['section_id'];
                $this->item_name = $response['item_name'];
                $this->item_description = $response['item_description'];
                $this->item_price = floatval($response['item_price']);
                $this->item_img = "data:image/jpg;charset=utf8;base64,".base64_encode($response['image']);
                $this->itemIngredients = $this->populateItemIngredients();
            }
            else{
                echo 'ERROR FINDING MENU_ITEM WITH ID: ' . $this->item_id;
            }
        }
       function populateItemIngredients(){

        }
        function getItemId(){
            return $this->item_id;
        }
        function getSectionId(){
            return $this->section_id;
        }
        function getItemName(){
            return $this->item_name;
        }
        function getItemDescription(){
            return $this->item_description;
        }
        function getItemPrice(){
            return $this->item_price;
        }
        function getItemImg(){
            return $this->item_img;
        }

        static function getMenuItems(){
            global $db;
            $menuItemArray = Array();
            $stmt = $db->prepare("SELECT item_id FROM menu_items");
            if($stmt->execute()){
                foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
                    array_push($menuItemArray, new Menu_Item($row["item_id"]));
                }
                return($menuItemArray);
            }
            else{
                echo 'ERROR FINDING MENU ITEMS';
            }
        }


    }

    class Ingredient{
        private $ingredient_id;
        private $ingredient_name;
        private $ingredient_price;
        private $is_default;
        function __construct($_ingredient_id){
            global $db;
            $this->ingredient_id = $_ingredient_id;

            

            $stmt = $db->prepare("SELECT * FROM ingredients WHERE ingredient_id = :ingredient_id");
            $binds = Array(":ingredient_id" => $this->ingredient_id);

            if($stmt->execute($binds)){
                $response = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
                $this->ingredient_id = $response['ingredient_id'];
                $this->ingredient_name = $response['ingredient_id'];
                $this->item_price = $response['item_price'];
                $this->is_default = ($response['is_default'] == '1') ? true : false;
            }
            else{
                echo 'ERROR FINDING MENU_ITEM WITH ID: ' . $this->item_id;
            }
        }
       function populateItemIngredients(){

        }
        function getItemId(){
            return $this->item_id;
        }
        function getSectionId(){
            return $this->section_id;
        }
        function getItemName(){
            return $this->item_name;
        }
        function getItemDescription(){
            return $this->item_description;
        }
        function getItemPrice(){
            return $this->item_price;
        }
        function getItemImg(){
            return $this->item_img;
        }

        static function getMenuItems(){
            global $db;
            $menuItemArray = Array();
            $stmt = $db->prepare("SELECT item_id FROM menu_items");
            if($stmt->execute()){
                foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
                    array_push($menuItemArray, new Menu_Item($row["item_id"]));
                }
                return($menuItemArray);
            }
            else{
                echo 'ERROR FINDING MENU ITEMS';
            }
        }


    }
    

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