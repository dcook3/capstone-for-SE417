<?php 
    include('db.php');
    class Menu_Item{
        
        public string $menu_item_id;
        public Section $section;
        public string $item_name;
        public string $item_description;
        public float $item_price;
        public string $item_img;
        public bool $is_special;
        public Array $itemIngredients;
        
        function __construct(string $_menu_item_id, Section $_section, string $_item_name, string $_item_description, float $_item_price, string $_item_img, bool $_is_special){
            $this->menu_item_id = $_menu_item_id;
            $this->section = $_section;
            $this->item_name = $_item_name;
            $this->item_description = $_item_description;
            $this->item_price = $_item_price;
            $this->item_img = $_item_img;
            $this->is_special = $_is_special;
            $this->itemIngredients = Array();
        }
        /**
         * 
         * Populates a menu item with it's ingredients and returns the object
         */
        
        static function getMenuItemById(string $_menu_item_id, bool $wthImg){ 
            try{
                global $db;
                if($wthImg){
                    $sql = "SELECT a.menu_item_id, d.section_id, d.section_name, a.item_name, a.item_description, a.item_price, a.item_img, a.is_special, c.ingredient_id, c.ingredient_name, c.ingredient_price, c.is_default FROM menu_items a LEFT JOIN menu_item_ingredients b ON a.menu_item_id = b.menu_item_id LEFT JOIN ingredients c ON b.ingredient_id = c.ingredient_id LEFT JOIN sections d ON a.section_id = d.section_id WHERE a.menu_item_id = :menu_item_id;";
                }
                else{
                    $sql = "SELECT a.menu_item_id, d.section_id, d.section_name, a.item_name, a.item_description, a.item_price, a.is_special, c.ingredient_id, c.ingredient_name, c.ingredient_price, c.is_default FROM menu_items a LEFT JOIN menu_item_ingredients b ON a.menu_item_id = b.menu_item_id LEFT JOIN ingredients c ON b.ingredient_id = c.ingredient_id LEFT JOIN sections d ON a.section_id = d.section_id WHERE a.menu_item_id = :menu_item_id;";
                }
                $stmt = $db->prepare($sql);
                $binds = Array(":menu_item_id" => $_menu_item_id);
                
                if($stmt->execute($binds) && $stmt->rowCount() > 0){
                    $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $firstItem = $response[0];
                    $menuItem = new Menu_Item($_menu_item_id,                                                                // menu_item_id
                                        new Section($firstItem["section_id"], $firstItem["section_name"], ""),                                          // section
                                        $firstItem['item_name'],                                                        // item_name
                                        $firstItem['item_description'],                                                 // item_description
                                        floatval($firstItem['item_price']),                                             // item_price
                                        ($wthImg) ? $firstItem['item_img'] : "",
                                        ($firstItem['is_special'] == '1') ? true : false);      //img
                    foreach($response as $row){
                        if($row["ingredient_id"] != NULL){
                            $menuItem->addIngredient(new Ingredient($row["ingredient_id"],
                                                                    $row["ingredient_name"],
                                                                    floatval($row["ingredient_price"]), 
                                                                    ($row['is_default'] == '1') ? true : false));
                        }
                    }
                    return($menuItem);
                }
                else{
                    echo 'ERROR FINDING MENU_ITEM WITH ID: ' . $_menu_item_id;
                }
            }
            catch(Exception $e){
                echo 'A server error occured please contact the systems administrator.';
            }
        }
        /**
         * Deletes menu item from database
        */
        static function deleteMenuItemByID(string $menu_item_id){
            try{
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
            catch(Exception $e){
                echo 'A server error occured please contact the systems administrator.';
            }
        }
        /**
         * Returns an array of all the menu items and their ingredients
         */
        static function getMenuItems(){
            try{
                global $db;
                $menuItemArray = Array();
                $stmt = $db->prepare("SELECT a.menu_item_id, d.section_id, d.section_name, a.item_name, a.item_description, a.item_price, a.is_special, c.ingredient_id, c.ingredient_name, c.ingredient_price, c.is_default FROM menu_items a LEFT JOIN menu_item_ingredients b ON a.menu_item_id = b.menu_item_id LEFT JOIN ingredients c ON b.ingredient_id = c.ingredient_id LEFT JOIN sections d ON a.section_id = d.section_id ORDER BY menu_item_id;");
                if($stmt->execute() && $stmt->rowCount() > 0){
                    $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $menuItem = new Menu_Item("", new Section("", "", ""), "", "", 0, "", false);
                    foreach($response as $row){
                        if($row["menu_item_id"] != $menuItem->getMenuItemId()){
                            array_push($menuItemArray, new Menu_Item($row["menu_item_id"],                                                                // menu_item_id
                                                        new Section($row["section_id"], $row["section_name"], ""),                                          // section
                                                        $row['item_name'],                                                        // item_name
                                                        $row['item_description'],                                                 // item_description
                                                        floatval($row['item_price']),                                             // item_price
                                                        "",
                                                        ($row['is_special'] == '1') ? true : false)); 
                            
                            $menuItem = $menuItemArray[count($menuItemArray)-1];
                        }
                        if($row["ingredient_id"] != NULL){
                            $menuItem->addIngredient(new Ingredient($row["ingredient_id"],
                                                                    $row["ingredient_name"],
                                                                    floatval($row["ingredient_price"]), 
                                                                    ($row['is_default'] == '1') ? true : false));
                        }
                        
                    }
                    return($menuItemArray);
                }
                catch(Exception $e){
                    echo 'A server error occured please contact the systems administrator.';
                }
        }
        static function getSpecialItems(){
            try{
                global $db;
                $menuItemArray = Array();
                $stmt = $db->prepare("SELECT a.menu_item_id, d.section_id, d.section_name, a.item_name, a.item_description, a.item_price, a.is_special, c.ingredient_id, c.ingredient_name, c.ingredient_price, c.is_default FROM menu_items a LEFT JOIN menu_item_ingredients b ON a.menu_item_id = b.menu_item_id LEFT JOIN ingredients c ON b.ingredient_id = c.ingredient_id LEFT JOIN sections d ON a.section_id = d.section_id WHERE a.is_special = 1 ORDER BY menu_item_id;");
                if($stmt->execute() && $stmt->rowCount() > 0){
                    $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $menuItem = new Menu_Item("", new Section("", "", ""), "", "", 0, "", false);
                    foreach($response as $row){
                        if($row["menu_item_id"] != $menuItem->getMenuItemId()){
                            array_push($menuItemArray, new Menu_Item($row["menu_item_id"],                                                                // menu_item_id
                                                        new Section($row["section_id"], $row["section_name"], ""),                                          // section
                                                        $row['item_name'],                                                        // item_name
                                                        $row['item_description'],                                                 // item_description
                                                        floatval($row['item_price']),                                             // item_price
                                                        "",
                                                        ($row['is_special'] == '1') ? true : false)); 
                            
                            $menuItem = $menuItemArray[count($menuItemArray)-1];
                        }
                        if($row["ingredient_id"] != NULL){
                            $menuItem->addIngredient(new Ingredient($row["ingredient_id"],
                                                                    $row["ingredient_name"],
                                                                    floatval($row["ingredient_price"]), 
                                                                    ($row['is_default'] == '1') ? true : false));
                        }
                        
                    }
                    return($menuItemArray);
                }
            }
            catch(Exception $e){
                echo 'A server error occured please contact the systems administrator.';
            }
        }
        /**
         * Returns an array of menu items selected by the section_id.
         */
        static function getMenuItemsBySectionId(string $section_id, bool $wthImg){
            try{
                global $db;
                $menuItemArray = Array();
                if($wthImg){
                    $sql = "SELECT a.menu_item_id, d.section_id, d.section_name,  a.item_name, a.item_description, a.item_price, a.item_img, a.is_special, c.ingredient_id, c.ingredient_name, c.ingredient_price, c.is_default FROM menu_items a LEFT JOIN menu_item_ingredients b ON a.menu_item_id = b.menu_item_id LEFT JOIN ingredients c ON b.ingredient_id = c.ingredient_id LEFT JOIN sections d ON a.section_id = d.section_id WHERE d.section_id = :section_id ORDER BY menu_item_id;";
                }
                else{
                    $sql = "SELECT a.menu_item_id, d.section_id, d.section_name, a.item_name, a.item_description, a.item_price, a.is_special, c.ingredient_id, c.ingredient_name, c.ingredient_price, c.is_default FROM menu_items a LEFT JOIN menu_item_ingredients b ON a.menu_item_id = b.menu_item_id LEFT JOIN ingredients c ON b.ingredient_id = c.ingredient_id LEFT JOIN sections d ON a.section_id = d.section_id WHERE d.section_id = :section_id ORDER BY menu_item_id;";
                }
                $stmt = $db->prepare($sql);
                $binds = Array(":section_id" => $section_id);
                if($stmt->execute($binds) && $stmt->rowCount() > 0){
                    $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $menuItem = new Menu_Item("", new Section("", "", ""), "", "", 0, "", false);
                    foreach($response as $row){
                        if($row["menu_item_id"] != $menuItem->getMenuItemId()){
                            array_push($menuItemArray, new Menu_Item($row["menu_item_id"],                                                                // menu_item_id
                                                        new Section($row["section_id"], $row["section_name"], ""),                                          // section
                                                        $row['item_name'],                                                        // item_name
                                                        $row['item_description'],                                                 // item_description
                                                        floatval($row['item_price']),                                             // item_price
                                                        ($wthImg) ? $row['item_img'] : "",
                                                        ($row['is_special'] == '1') ? true : false)); 
                            
                            $menuItem = $menuItemArray[count($menuItemArray)-1];
                        }
                        if($row["ingredient_id"] != NULL){
                            $menuItem->addIngredient(new Ingredient($row["ingredient_id"],
                                                                    $row["ingredient_name"],
                                                                    floatval($row["ingredient_price"]), 
                                                                    ($row['is_default'] == '1') ? true : false));
                        }
                        
                    }
                    return($menuItemArray);
                }
                else if($stmt->rowCount() == 0){
                    return(Array());
                }
            }
            catch(Exception $e){
                echo 'A server error occured please contact the systems administrator.';
            }
        }
        /**
         * Adds ingredient to ingredient array
         */
        function addIngredient(Ingredient $ingredient){
            array_push($this->itemIngredients, $ingredient);
        }
        /**
         * Pushes instance of Menu Item to the database.
         */
        function addToDB(){
            try{
                global $db;
                $stmt = $db->prepare("INSERT INTO menu_items (section_id, item_name, item_description, item_price, item_img, is_special) VALUES (:section_id, :item_name, :item_description, :item_price, :item_img, :is_special)");
                $binds = array(
                    ":section_id" => $this->getSection()->getSectionId(),
                    ":item_name" => $this->getItemName(),
                    ":item_description" => $this->getItemDescription(),
                    ":item_price" => $this->getItemPrice(),
                    ":item_img" => $this->getItemImg(),
                    ":is_special" => $this->getIsSpecial()
                );

                if($stmt->execute($binds)){
                    $this->menu_item_id = $db->lastInsertId();
                    foreach($this->getIngredients() as $ingredient){
                        $ingredient->addToDB($this->getMenuItemId());
                    }
                }
            }
            catch(Exception $e){
                echo 'A server error occured please contact the systems administrator.';
            }
        }
        /**
         * Updates database menu item with the parameters of the instance of the Menu Item object
         */
        function updateMenuItem(){
            try{
                global $db;
                if($this->getMenuItemId() != '-1'){
                    $stmt = $db->prepare("UPDATE menu_items SET section_id = :section_id, item_name = :item_name, item_description = :item_description, item_price = :item_price, item_img = :item_img, is_special = :is_special WHERE menu_item_id = :menu_item_id;");
                    $binds = array(
                        ":section_id" => $this->getSection()->getSectionId(),
                        ":item_name" => $this->getItemName(),
                        ":item_description" => $this->getItemDescription(),
                        ":item_price" => $this->getItemPrice(),
                        ":item_img" => $this->getItemImg(),
                        ":is_special" => $this->getIsSpecial(),
                        ":menu_item_id" => $this->getMenuItemId(),
                    );
                    //var_dump($this);
                    if($stmt->execute($binds)){
                        
                        foreach($this->getIngredients() as $ingredient){
                            //var_dump($ingredient);
                            $ingredient->updateIngredient($this->getMenuItemId());
                        }
                    }
                    else{
                        //var_dump($stmt->errorInfo());
                    }
                    
                }
            }
            catch(Exception $e){
                echo 'A server error occured please contact the systems administrator.';
            }
        }

        function populateImage(){
            try{
                global $db;
                $stmt = $db->prepare("SELECT item_img FROM menu_items WHERE menu_item_id = :menu_item_id");
                $binds = array(
                    ":menu_item_id" => $this->getMenuItemId()
                );
                if($stmt->execute($binds)){
                    $this->item_img = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["item_img"];
                }
            }
            catch(Exception $e){
                echo 'A server error occured please contact the systems administrator.';
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
        function getIsSpecial(){
            return $this->is_special;
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
            try{
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
            catch(Exception $e){
                echo 'A server error occured please contact the systems administrator.';
            }
        }
        
        static function getSections(){
            try{
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
            catch(Exception $e){
                echo 'A server error occured please contact the systems administrator.';
            }
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
            try{
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
            catch(Exception $e){
                echo 'A server error occured please contact the systems administrator.';
            }
        }
        static function getIngredientsByMenuItemId(string $_menu_item_id){
            try{
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
            catch(Exception $e){
                echo 'A server error occured please contact the systems administrator.';
            }
        }
        static function deleteIngredientById(string $ingredient_id, string $menu_item_id){
            try{
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
            catch(Exception $e){
                echo 'A server error occured please contact the systems administrator.';
            }
        }
        function addToDB($menu_item_id){
            try{
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
            catch(Exception $e){
                echo 'A server error occured please contact the systems administrator.';
            }
        }
        function updateIngredient($menu_item_id){
            try{
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
            catch(Exception $e){
                echo 'A server error occured please contact the systems administrator.';
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