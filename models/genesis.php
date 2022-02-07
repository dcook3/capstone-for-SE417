<?php
include (__DIR__ . '/db.php');

function getStudents () {
    global $db;
    
    $results = [];

    $stmt = $db->prepare("SELECT * FROM users ORDER BY user_id"); 
    
    if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
             
     }
     
     return ($results);
}

function addStudent($student_id, $first_name, $middle_name, $last_name, $phone, $email, $password) {
    global $db;
    $results = "Not added";

    $stmt = $db->prepare("INSERT INTO users SET student_id = :student_id, first_name = :first_name, middle_name = :middle_name, last_name = :last_name, phone = :phone, email = :email, password = :password");

    $stmt->bindValue(':student_id', $student_id);
    $stmt->bindValue(':first_name', $first_name);
    $stmt->bindValue(':middle_name', $middle_name);
    $stmt->bindValue(':last_name', $last_name);
    $stmt->bindValue(':phone', $phone);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':password', $password);
    
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        $results = 'Data Added';
    }
   
    $stmt->closeCursor();
   
    return ($results);
}

function getStudent($id){
        global $db;
        
        $results = [];

        $stmt = $db->prepare("SELECT * FROM users WHERE user_id=:user_id"); 

        $stmt->bindValue(':user_id', $id);
        
        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
         }
         
         return ($results);
}

function updateStudent($id, $img, $student_id, $dorm_num, $first_name, $middle_name, $last_name, $phone, $phone2, $email, $email2) {
    global $db;

    $results = "Data NOT Updated";
    
    $stmt = $db->prepare("UPDATE users SET img = :img, student_id = :student_id, dorm_num = :dorm_num, first_name = :first_name,
    middle_name = :middle_name, last_name = :last_name, phone = :phone, phone2 = :phone2, email = :email, email2 = :email2 WHERE user_id=:user_id");
    
    $stmt->bindValue(':user_id', $id);
    $stmt->bindValue(':img', $img);
    $stmt->bindValue(':student_id', $student_id);
    $stmt->bindValue(':dorm_num', $dorm_num);
    $stmt->bindValue(':first_name', $first_name);
    $stmt->bindValue(':middle_name', $middle_name);
    $stmt->bindValue(':last_name', $last_name);
    $stmt->bindValue(':phone', $phone);
    $stmt->bindValue(':phone2', $phone2);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':email2', $email2);

  
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        $results = 'Data Updated';
    }
    
    return ($results);
}

function deleteStudent ($id) {
    global $db;
    
    $results = "Data was not deleted";

    $stmt = $db->prepare("DELETE FROM users WHERE user_id=:user_id");
    
    $stmt->bindValue(':user_id', $id);
        
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        $results = 'Data Deleted';
    }
    
    return ($results);
}
?>

<?php
function isPostRequest() {
    return ( filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST' );
    }

if(isset($_GET['id']))
    {
        $id = filter_input(INPUT_GET, 'id');
        $action = filter_input(INPUT_GET, 'action');

        $row = getStudent($id);

        $img= $row[0]['img'];
        $student_id = $row[0]['student_id'];
        $dorm_num = $row[0]['dorm_num'];
        $first_name = $row[0]['first_name'];
		$middle_name = $row[0]['middle_name'];
        $last_name = $row[0]['last_name'];
        $phone = $row[0]['phone'];
        $phone2 = $row[0]['phone2'];
        $email = $row[0]['email'];
        $email2 = $row[0]['email2'];
        $password = $row[0]['password'];
    }
    else 
    {
        $id = "";
        $action = filter_input(INPUT_GET, 'action');

        $img = "";
        $student_id = "";
        $dorm_num = "";
        $first_name = "";
		$middle_name = "";
        $last_name = "";
        $phone = "";
        $phone2 = "";
        $email = "";
        $email2 = "";
        $password = "";
    }



    if(isset($_POST['action'])) 
    {
        $action = filter_input(INPUT_POST, 'action');

        if(isPostRequest())
        {
            $img = filter_input(INPUT_POST, 'img');
            $student_id = filter_input(INPUT_POST, 'studentID');
            $dorm_num = filter_input(INPUT_POST, 'dorm_num');
            $first_name = filter_input(INPUT_POST, 'firstName');
			$middle_name = filter_input(INPUT_POST, 'middleName');
            $last_name = filter_input(INPUT_POST, 'lastName');
            $phone = filter_input(INPUT_POST, 'phone');
            $phone2 = filter_input(INPUT_POST, 'phone2');
            $email = filter_input(INPUT_POST, 'email');
            $email2 = filter_input(INPUT_POST, 'email2');
            $password = filter_input(INPUT_POST, 'password');
    
            if($action == "add")
            {
                $results = addStudent($student_id, $first_name, $middle_name, $last_name, $phone, $email, $password);
            }

            else if($action == "update")
            {
                $id = filter_input(INPUT_POST, 'id');
    
                if(isset($_POST['btnDelete']))
                {
                    $results = deleteStudent($id);
                }
                
                else if(isset($_POST['btnSubmit']))
                {
                    $results = updateStudent($id, $img, $student_id, $dorm_num, $first_name, $middle_name, $last_name, $phone, $phone2, $email, $email2);
                }
                
            }

        }

}
