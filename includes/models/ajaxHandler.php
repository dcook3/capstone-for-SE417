<?php 
    include('lucas.php');
    include('dylan.php');
    include('../functions.php');
    include('../connectDB.php');
    require '../../PHPMailer/PHPMailerAutoload.php';

    function send_mail($receiver_email, $receiver_name, $message, $subject) {
		$mail = new PHPMailer;
		$mail->isSMTP();
		//$mail->SMTPDebug = 2;
		$config = parse_ini_file('../dbconfig.ini', true);
		$mail->Host = 'smtp.gmail.com'; // Which SMTP server to use.
		$mail->Port = 587; // Which port to use, 587 is the default port for TLS security.
		$mail->SMTPSecure = 'tls'; // Which security method to use. TLS is most secure.
		$mail->SMTPAuth = true; // Whether you need to login. This is almost always required.
		$mail->Username = $config['user']; // Your Gmail address.
		$mail->Password = $config['pass']; // Your Gmail login password or App Specific Password.
		$mail->setFrom($mail->Username, 'NEIT Dinning Center'); // Set the sender of the message.
		$mail->addAddress($receiver_email, $receiver_name); // Set the recipient of the message.
		$mail->Subject = $subject; // The subject of the message.
		$mail->Body = $message; // Set a plain text body.
		if ($mail->send()) {
			return 1;
		} else {
			return 0;
		}
	}

    function GetUserByUID($uid){
		global $db;

		$SQL = $db->prepare("SELECT user_id, first_name, last_name, email, phone, username FROM user WHERE user_id = :uid");

		$SQL->bindValue(":uid", $uid);

		if($SQL->execute() && $SQL->rowCount() == 1)
		{
			return $SQL->fetchAll(PDO::FETCH_ASSOC)[0];
		}
		else
		{
			return 'A SQL error occured when grabbing a user.';
		}
	}

    function UpdateUser($uid, $fName, $lName, $email, $studentID, $phone)
    {
        global $db;

        $SQL = $db->prepare("UPDATE user SET first_name=:fName, last_name=:lName, email=:email, username=:studentID, phone=:phone WHERE user_id = :uid");

        $binds = array(
            ":fName" => $fName,
            ":lName" => $lName,
            ":email" => $email,
            ":studentID" => $studentID,
            ":phone" => $phone,
            ":uid" => $uid
        );

        if($SQL->execute($binds))
        {
            return $SQL->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            return 'A SQL error occured while updating users.';
        }
    }

    function DeleteUser($uid)
    {
        global $db;

        $SQL = $db->prepare("DELETE FROM user WHERE user_id = :uid");

        $SQL->bindValue(":uid", $uid);

        if($SQL->execute())
        {
            return $SQL->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    if($_SERVER['REQUEST_METHOD']==='POST'){
        switch($_POST['action'])
        {
            case 'addToDB':
                $postItem = $_POST['item'];
                $item = postToMenuItem($postItem);
                echo "hello". $item->addToDB();
                break;
            case 'updateItem':
                $postItem = $_POST['item'];
                $item = postToMenuItem($postItem);
                echo $item->updateMenuItem();
                break;
            case 'deleteIngredient':
                if(isset($_POST["ingredient_id"]) && isset($_POST["menu_item_id"])){
                    echo Ingredient::deleteIngredientById($_POST["ingredient_id"], $_POST["menu_item_id"]);
                }
                else{
                    echo false;
                }
                break;
            case 'deleteItem':
                if(isset($_POST["menu_item_id"])){
                    echo Menu_Item::deleteMenuItemByID($_POST["menu_item_id"]);
                }
                else{
                    echo false;
                }
                break;
            case 'getMenuItemsBySectionId':
                $wthImg = (isset($_POST["wthImg"])) ? (($_POST["wthImg"] == "true") ? true : false) : false;
                if(isset($_POST["section_id"])){
                    echo json_encode(Menu_Item::getMenuItemsBySectionId($_POST["section_id"], $wthImg));
                }
                else{
                    echo false;
                }
                break;
            case 'getMenuItemByID':
                $wthImg = (isset($_POST["wthImg"])) ? (($_POST["wthImg"] == "true") ? true : false) : false;
                if(isset($_POST["menu_item_id"])){
                    echo json_encode(Menu_Item::getMenuItemById($_POST["menu_item_id"], $wthImg));
                }
                else{
                    echo false;
                }
                break;
            case 'createOrderIfNoneExists':
                if(isset($_POST["user_id"])){
                    $result = Order::createOrderIfNoneExists($_POST["user_id"]);
                    
                    echo json_encode($result);
                    
                }
                else {
                    echo "user_id not set";
                }
                break;
            case 'addOrderItem':
                if(isset($_POST["order_item"]) && isset($_POST["order_id"])){
                    $order_item = postToOrderItem($_POST["order_item"]);
                    echo $order_item->addItemToOrder();
                }
                break;
            case 'detailsUpdate':
                $tempOrder = new Order();
                $tempOrder->populateOrderByID($_POST['detOrderID']);
                $out = "<ul>";
                $count = 0;
                foreach($tempOrder->getMenuItems() as $item)
                {
                    $orderItems = $tempOrder->getOrderItems();
                    $out .= "<li><b>{$orderItems[$count]->getQuantity()}x</b> {$item->getItemName()}";
                    $out .= "<ul>";
                    $ingredients = $orderItems[$count]->getIngredients();
                    foreach($ingredients as $ingredient)
                    {
                        $out .= "<li>{$ingredient->getIngredientName()}</li>";
                    }
                    $out .= "<li><b>Notes:</b> {$orderItems[$count]->getNotes()}</li>";
                    $out .= "</ul>";
                    $count++;
                }
                $out .= "</ul>";
                echo $out;
                break;

            case 'trackerStatus':
                $isComplete = false;
                $temp = "fail";
                while($isComplete == false){
                    if(Order::getOrderStatusByUID($_POST['userID']) == 1)
                    {
                        $isComplete = true;
                    }
                }
                echo 'success';
                break;  
            case 'updateUser':
                if(isset($_POST["user"])){
                    $user = new User();
                    session_start();
                    

                    $postUser = $_POST["user"];
                    $user->email = $postUser["email"];
                    $user->fname = $postUser["fname"];
                    $user->lname = $postUser["lname"];
                    $user->phone = $postUser["phone"];
                    $user->student_id = $postUser["student_id"];
                    $user->user_id = $postUser["user_id"];
                    if($user->updateUser()){
                        $_SESSION['USER'] = $user;
                    }

                }
                break;
            
            case "deleteOrderItem":
                if(Order_Item::deleteItem($_POST['orderID'], $_POST['orderItemID']) == false)
                {
                    setMessage("An error occured whilst deleting an item. Please contact administrator or try again later.");
                }
                break;

            case "updateStatus":
                Order::updateOrderStatus($_POST['orderID'], 1);
                Order::updateOrderPrice($_POST['orderID'], $_POST['orderTotal']);
                send_mail($_SESSION['USER']->email, $_SESSION['USER']->fname, "Your order is now in progress.", "Tiger Eats Order Update");
                echo 'tracker.php';
                break;
            
            case 'updateUserBackend':
                UpdateUser($_POST["uid"], $_POST["fName"], $_POST['lName'], $_POST['email'], $_POST['studentID'], $_POST['phone']);
                break;

            case 'deleteUser':
                DeleteUser($_POST['uid']);
                break;

            case 'getUser':
                $result = GetUserByUID($_POST['uid']);
                
                if(gettype($result) != 'string')
                {
                    echo json_encode($result);
                }

                break;
            
            default:
                echo 'action not set';
                break;
        }
    }
    /**
     * Converts a javascript Order Item object sent through post to a php object
     */
    function postToOrderItem($postItem){
        $order_item = new Order_Item();
        $order_item->order_item_id = $postItem["order_item_id"];
        $order_item->order_id = $_POST["order_id"];
        $order_item->item_id = $postItem["menu_item_id"];
        $order_item->qty = $postItem["qty"];
        $order_item->price = $postItem["item_price"];
        if(isset($postItem["ingredients"])){
            foreach($postItem["ingredients"] as $ingredient){
                array_push($order_item->ingredients, postToIngredient($ingredient));
            }
        }
            return($order_item);
    }
    /**
     * Converts a javascript Menu Item object sent through post to a php object
     */
    function postToMenuItem($postItem){
        $item = new Menu_Item($postItem['menu_item_id'],
                                new Section($postItem["section"]["section_id"], 
                                $postItem["section"]["section_name"], $postItem["section"]["section_img"]), 
                                $postItem["item_name"], 
                                $postItem["item_description"], 
                                floatval($postItem["item_price"]), 
                                $postItem["item_img"],
                                ($postItem["is_special"] == "true") ? true : false);
        if(isset($postItem["ingredients"])){
            foreach($postItem["ingredients"] as $postIngredient){
                $item->addIngredient(postToIngredient($postIngredient));
            }
        }
        return($item);
    }
    /**
     * Converts a javascript Ingredient object sent through post to a php object
     */
    function postToIngredient($ingredient){
        $checked = ($ingredient["is_default"] == "true") ? true : false;
        return new Ingredient($ingredient["ingredient_id"], 
                                $ingredient["ingredient_name"], 
                                floatval($ingredient["ingredient_price"]), 
                                $checked);
    }
?>