<?php 
    include('db.php');
    class Menu_Item{
        
        public string $menu_item_id;
        public Section $section;
        public string $item_name;
        public string $item_description;
        public float $item_price;
        public string $item_img;
        public Array $itemIngredients;
        
        function __construct(string $_menu_item_id, Section $_section, string $_item_name, string $_item_description, float $_item_price, string $_item_img){
            $this->menu_item_id = $_menu_item_id;
            $this->section = $_section;
            $this->item_name = $_item_name;
            $this->item_description = $_item_description;
            $this->item_price = $_item_price;
            $this->item_img = $_item_img;
            $this->itemIngredients = Array();
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
                                     "".$response['item_img']);   // iem_img
            }
            else{
                echo 'ERROR FINDING MENU_ITEM WITH ID: ' . $_menu_item_id;
            }
        }
        static function deleteMenuItemByID(string $menu_item_id){
            global $db;
            $stmt = $db->prepare("DELETE FROM menu_items WHERE menu_item_id = :menu_item_id");
            $binds = Array(":menu_item_id" => $menu_item_id);
            if($stmt->execute($binds)){
                return $stmt->rowCount();
            }
            else{
                return 0;
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
        static function getMenuItemsBySectionId(string $section_id){
            global $db;
            $menuItemArray = Array();
            $stmt = $db->prepare("SELECT * FROM menu_items WHERE section_id = :section_id");
            $binds = Array(":section_id" => $section_id);
            if($stmt->execute($binds)){
                foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
                    $tempItem = new Menu_Item($row["menu_item_id"],                                                                // menu_item_id
                                                Section::getSectionById($section_id),                                          // section
                                                $row['item_name'],                                                        // item_name
                                                $row['item_description'],                                                 // item_description
                                                floatval($row['item_price']),                                             // item_price
                                                "data:image/jpg;charset=utf8;base64,".base64_encode($row['item_img']));
                    $tempItem->populateIngredientsById();
                    array_push($menuItemArray,  $tempItem);
                }
                return($menuItemArray);
            }
            else{
                echo 'ERROR FINDING MENU ITEMS';
            }
        }
        function populateIngredientsById(){
            $this->itemIngredients = Ingredient::getIngredientsByMenuItemId($this->menu_item_id);
        }
        function addIngredient(Ingredient $ingredient){
            array_push($this->itemIngredients, $ingredient);
        }
        function addToDB(){
            global $db;
            $stmt = $db->prepare("INSERT INTO menu_items (section_id, item_name, item_description, item_price, item_img) VALUES (:section_id, :item_name, :item_description, :item_price, :item_img)");
            $binds = array(
                ":section_id" => $this->getSection()->getSectionId(),
                ":item_name" => $this->getItemName(),
                ":item_description" => $this->getItemDescription(),
                ":item_price" => $this->getItemPrice(),
                ":item_img" => $this->getItemImg()
            );

            if($stmt->execute($binds)){
                $this->menu_item_id = $db->lastInsertId();
                foreach($this->getIngredients() as $ingredient){
                    $ingredient->addToDB($this->getMenuItemId());
                }
            }
        }
        function updateMenuItem(){
            global $db;
            if($this->getMenuItemId() != '-1'){
                $stmt = $db->prepare("UPDATE menu_items SET section_id = :section_id, item_name = :item_name, item_description = :item_description, item_price = :item_price, item_img = :item_img WHERE menu_item_id = :menu_item_id;");
                $binds = array(
                    ":section_id" => $this->getSection()->getSectionId(),
                    ":item_name" => $this->getItemName(),
                    ":item_description" => $this->getItemDescription(),
                    ":item_price" => $this->getItemPrice(),
                    ":item_img" => $this->getItemImg(),
                    ":menu_item_id" => $this->getMenuItemId()
                );
                // var_dump($this);
                if($stmt->execute($binds)){
                    
                    foreach($this->getIngredients() as $ingredient){
                        var_dump($ingredient);
                        $ingredient->updateIngredient($this->getMenuItemId());
                    }
                }
                else{
                    var_dump($stmt->errorInfo());
                }
                
            }
        }

        //GETTERS

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
        public $section_id;
        public $section_name;
        function __construct($_section_id, $_section_name, $section_img){
            $this->section_id = $_section_id;
            $this->section_name = $_section_name;
            $this->section_img = $section_img;
        }  

        static function getSectionById($_section_id){
            global $db;
            
            $stmt = $db->prepare("SELECT section_name, section_img FROM sections WHERE section_id = :section_id");
            $binds = Array(":section_id" => $_section_id);

            if($stmt->execute($binds)){
                $response = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
                return new Section($_section_id, $response['section_name'], $response['section_img']);
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
                    array_push($sections, new Section($row["section_id"], $row["section_name"], $row["section_img"]));
                }
            }
            return $sections;
        }
        
        //GETTERS
        function getSectionId(){
            return $this->section_id;
        }
        function getSectionName(){
            return $this->section_name;
        }
        function getSectionImg(){
            return $this->section_img;
        }
    }
    class Ingredient{
        public string $ingredient_id;
        public string $ingredient_name;
        public float $ingredient_price;
        public bool $is_default;

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
                                      floatval($response['ingredient_price']),
                                      ($response['is_default'] == '1') ? true : false);
            }
            else{
                echo 'ERROR FINDING INGREDIENT WITH ID: ' . $_ingredient_id;
            }
        }
        static function getIngredientsByMenuItemId(string $_menu_item_id){
            global $db;
            $stmt = $db->prepare("SELECT ingredient_id FROM menu_item_ingredients WHERE menu_item_id = :menu_item_id");
            $binds = Array(":menu_item_id" => $_menu_item_id);
            $ingredients = Array();
            if($stmt->execute($binds)){
                foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
                    array_push($ingredients, Ingredient::getIngredientById($row['ingredient_id']));
                }
            }
            else{
                array_push($ingredients, "wtf");
            }
            return $ingredients;
        }
        static function deleteIngredientById(string $ingredient_id, string $menu_item_id){
            global $db;
            $stmt = $db->prepare("DELETE FROM menu_item_ingredients WHERE ingredient_id = :ingredient_id AND menu_item_id = :menu_item_id");
            $binds = Array(":ingredient_id" => $ingredient_id,
                           ":menu_item_id" => $menu_item_id);
            if($stmt->execute($binds)){
                return $stmt->rowCount();
            }
            else{
                return 0;
            }
        }
        function addToDB($menu_item_id){
            global $db;
            $selectStmt = $db->prepare("SELECT ingredient_id FROM ingredients WHERE ingredient_name = :ingredient_name");
            $selectBinds = array(
                ":ingredient_name" => $this->getIngredientName()
            );

            if($selectStmt->execute($selectBinds)){
                if($selectStmt->rowCount() == 0){
                    $insertStmt = $db->prepare("INSERT INTO ingredients (ingredient_name, ingredient_price, is_default) VALUES (:ingredient_name, :ingredient_price, :is_default);");
                    
                    $insertBinds = array(
                        ":ingredient_name" => $this->getIngredientName(),
                        ":ingredient_price" => $this->getIngredientPrice(),
                        ":is_default" => $this->getIsDefault()
                    );

                    if($insertStmt->execute($insertBinds)){
                        $this->ingredient_id = $db->lastInsertId();
                    }
                    
                }
                else{
                    $this->ingredient_id = $selectStmt->fetchAll(PDO::FETCH_ASSOC)[0]['ingredient_id'];
                    $this->updateIngredient($menu_item_id);
                }
                $insertStmt = $db->prepare("INSERT INTO menu_item_ingredients (ingredient_id, menu_item_id) VALUES (:ingredient_id, :menu_item_id);");
                $insertBinds = array(
                    ":ingredient_id" => $this->getIngredientId(),
                    ":menu_item_id" => $menu_item_id
                );
                $insertStmt->execute($insertBinds);
            }
        }
        function updateIngredient($menu_item_id){
            global $db;

            if($this->getIngredientId() != '-1'){
                
                $stmt = $db->prepare("Update ingredients SET ingredient_name = :ingredient_name, ingredient_price = :ingredient_price, is_default = :is_default WHERE ingredient_id = :ingredient_id;");
                
                $binds = array(
                    ":ingredient_id" => $this->getIngredientId(),
                    ":ingredient_name" => $this->getIngredientName(),
                    ":ingredient_price" => $this->getIngredientPrice(),
                    ":is_default" => $this->getIsDefault()
                );
                $stmt->execute($binds); 
                if($stmt->rowCount() > 0){
                    return(true);
                }
                else{
                    return(false);
                }
            }
            else{
                $this->addToDB($menu_item_id);
            }
        }
        
        //GETTERS

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