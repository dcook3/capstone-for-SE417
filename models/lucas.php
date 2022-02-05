<?php 
    include('db.php');

    class Menu_Item{
        
        private string $menu_item_id;
        private Section $section;
        private string $item_name;
        private string $item_description;
        private float $item_price;
        private string $item_img;
        private Array $itemIngredients;

        function __construct(string $_menu_item_id, Section $_section, string $_item_name, string $_item_description, float $_item_price, string $_item_img){
            $this->menu_item_id = $_menu_item_id;
            $this->section = $_section;
            $this->item_name = $_item_name;
            $this->item_description = $_item_description;
            $this->item_price = $_item_price;
            $this->item_img = $_item_img;
            $this->itemIngredients = Ingredient::getIngredientsByMenuItemId($this->menu_item_id);
        }

        static function getMenuItemByID(string $_menu_item_id){
            global $db;
            $stmt = $db->prepare("SELECT * FROM menu_items WHERE menu_item_id = :menu_item_id");
            $binds = Array(":menu_item_id" => $_menu_item_id);

            if($stmt->execute($binds)){
                $response = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
                return new Menu_Item($_menu_item_id,                                                                // menu_item_id
                                     Section::getSectionById($response['section_id']),                                          // section
                                     $response['item_name'],                                                        // item_name
                                     $response['item_description'],                                                 // item_description
                                     floatval($response['item_price']),                                             // item_price
                                     "data:image/jpg;charset=utf8;base64,".base64_encode($response['item_img']));   // iem_img
            }
            else{
                echo 'ERROR FINDING MENU_ITEM WITH ID: ' . $_menu_item_id;
            }
        }
        static function getMenuItems(){
            global $db;
            $menuItemArray = Array();
            $stmt = $db->prepare("SELECT * FROM menu_items");
            if($stmt->execute()){
                foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
                    array_push($menuItemArray,  new Menu_Item($row["menu_item_id"],                                                                // menu_item_id
                                                              Section::getSectionById($row['section_id']),                                          // section
                                                              $row['item_name'],                                                        // item_name
                                                              $row['item_description'],                                                 // item_description
                                                              floatval($row['item_price']),                                             // item_price
                                                              "data:image/jpg;charset=utf8;base64,".base64_encode($row['item_img'])));
                }
                return($menuItemArray);
            }
            else{
                echo 'ERROR FINDING MENU ITEMS';
            }
        }

       
        function getMenuItemId(){
            return $this->menu_item_id;
        }
        function getSection(){
            return $this->section;
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
        function getIngredients(){
            return $this->itemIngredients;
        }
    }
    class Section {
        private $section_id;
        private $section_name;
        function __construct($_section_id, $_section_name){
            $this->section_id = $_section_id;
            $this->section_name = $_section_name;
        }  

        static function getSectionById($_section_id){
            global $db;
            
            $stmt = $db->prepare("SELECT section_name FROM sections WHERE section_id = :section_id");
            $binds = Array(":section_id" => $_section_id);

            if($stmt->execute($binds)){
                $response = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
                return new Section($_section_id, $response['section_name']);
            }
            else{
                echo 'ERROR FINDING SECTION WITH ID: ' . $_section_id;
            }
        }
        static function getSections(){
            global $db;

            $stmt = $db->prepare("SELECT * FROM sections");
            $sections = Array();
            if($stmt->execute()){
                foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
                    array_push($sections, new Section($row["section_id"], $row["section_name"]));
                }
            }
            return $sections;
        }
        function getSectionId(){
            return $this->section_id;
        }
        function getSectionName(){
            return $this->section_name;
        }
    }
    class Ingredient{
        private string $ingredient_id;
        private string $ingredient_name;
        private float $ingredient_price;
        private bool $is_default;

        function __construct(string $_ingredient_id, string $_ingredient_name, float $_ingredient_price, bool $_is_default){
            $this->ingredient_id = $_ingredient_id;
            $this->ingredient_name = $_ingredient_name;
            $this->ingredient_price = $_ingredient_price;
            $this->is_default = $_is_default;

            
        }
        static function getIngredientById(string $_ingredient_id){
            global $db;

            $stmt = $db->prepare("SELECT * FROM ingredients WHERE ingredient_id = :ingredient_id");
            $binds = Array(":ingredient_id" => $_ingredient_id);

            if($stmt->execute($binds)){
                $response = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
                return new Ingredient($response['ingredient_id'], 
                                      $response['ingredient_name'], 
                                      floatval($response['item_price']),
                                      ($response['is_default'] == '1') ? true : false);
            }
            else{
                echo 'ERROR FINDING INGREDIENT WITH ID: ' . $_ingredient_id;
            }
        }
        static function getIngredientsByMenuItemId(string $_menu_item_id){
            global $db;
            $stmt = $db->prepare("SELECT * FROM menu_item_ingredients WHERE menu_item_id = :menu_item_id");
            $binds = Array(":menu_item_id" => $_menu_item_id);
            $ingredients = Array();
            if($stmt->execute($binds)){
                foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
                    array_push($ingredients, new Ingredient ($row["ingredient_id"], 
                                                             $row["ingredient_name"], 
                                                             floatval($row['item_price']), 
                                                             ($row['is_default'] == '1') ? true : false));
                }
            }
            return $ingredients;
        }
        function getIngredientId(){
            return $this->ingredient_id;
        }
        function getIngredientName(){
            return $this->ingredient_name;
        }
        function getIngredientPrice(){
            return $this->ingredient_price;
        }
        function getIsDefault(){
            return $this->is_default;
        }
    }
    
?>