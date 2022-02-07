<?php include (__DIR__ . '/db.php'); ?>
<?php
class Users {
    
    private integer $user_id,
    private string $img,
    private integer $student_id
    private string $dorm_num,
    private string $first_name,
    private string $middle_name,
    private string $last_name,
    private string $phone,
    private string $phone2,
    private string $email, 
    private string $email2
    private string $password,
    private integer $is_admin,
    private string $created_at;
    private string $upated_at;
  

    function __construct(integer $user_id,string $img,integer $student_id,string $dorm_num,string $first_name,string $middle_name,string $last_name,string $phone,string $phone2,string $email, string $email2,string $password,integer $is_admin,string $created_at,string $updated_at) {
        $this->user_id =  $user_id;
        $this->img = $img;
        $this->student_id = $student_id;
        $this->dorm_num = $dorm_num;
        $this->first_name = $first_name;
        $this->middle_name = $middle;
        $this->last_name = $last_name;
        $this->phone = $phone;
        $this->phone2 = $phone2;
        $this->email = $email;
        $this->email2 = $email2;
        $this->password = $password;
        $this->is_admin = $is_admin;
        $this->created_at = $created_at;
        $this->updated_at = $upated_at;
    }

    function getUsers () {
        global $db;
        $results = [];
        $query = $db->prepare("SELECT * FROM users ORDER BY user_id");
        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return ($results);
    }

    function addUser () {
        global $db;
        $query = $db->prepare("INSERT INTO users SET student_id = :student_id, first_name = :first_name, middle_name = :middle_name, last_name = :last_name, phone = :phone, email = :email, password = :password, created_at = :created_at");

        $stmt->bindValue(':student_id', getStudentID());
        $stmt->bindValue(':first_name', getFirstName());
        $stmt->bindValue(':middle_name', getMiddeName());
        $stmt->bindValue(':last_name', getLastName());
        $stmt->bindValue(':phone', getPhone());
        $stmt->bindValue(':email', getEmail());
        $stmt->bindValue(':password', getPassword());
        $stmt->bindValue(':created_at', getCreatedAt());

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $results = 'Data Added';
        }
        $stmt->closeCursor();
        return ($results);
    }

    function getUser() {
        global $db;
        $results = [];

        $stmt = $db->prepare("SELECT * FROM users WHERE user_id=:id"); 

        $stmt->bindValue(':id', getUserID());
        
        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
         }
         
         return ($results);
    }

    function updateUser() {
        global $db;
    
        $results = "Data NOT Updated";
        
        $query = $db->prepare("UPDATE users SET img = :img, student_id = :student_id, dorm_num = :dorm_num, first_name = :first_name,
        middle_name = :middle_name, last_name = :last_name, phone = :phone, phone2 = :phone2, email = :email, email2 = :email2, updated_at = :updated_at WHERE user_id=:id");
        
        $query->bindValue(':id', getUserID());
        $query->bindValue(':img', getImg());
        $query->bindValue(':student_id', getStudentID());
        $query->bindValue(':dorm_num', getDormNum());
        $query->bindValue(':first_name', getFirstName());
        $query->bindValue(':middle_name', getMiddleName());
        $query->bindValue(':last_name', getLastName());
        $query->bindValue(':phone', getPhone());
        $query->bindValue(':phone2', getPhone2());
        $query->bindValue(':email', getEmail());
        $query->bindValue(':email2', getEmail2());
        $query->bindValue(':updated_at', getUpdatedAt());
    
      
        if ($query->execute() && $query->rowCount() > 0) {
            $results = 'Data Updated';
        }
        
        return ($results);
    }

    function deleteUser () {
        global $db;
        
        $results = "Data was not deleted";
    
        $query = $db->prepare("DELETE FROM users WHERE user_id=:id");
        
        $query->bindValue(':id', getUserID());
            
        if ($query->execute() && $query->rowCount() > 0) {
            $results = 'Data Deleted';
        }
        
        return ($results);
    }

    function getUserID(){
        return $this->user_id;
    }
    function getImg(){
        return $this->img;
    }
    function getStudentID(){
        return $this->student_id;
    }
    function getDormNum(){
        return $this->dorm_num;
    }
    function getFirstName(){
        return $this->first_name;
    }
    function getMiddleName(){
        return $this->middle_name;
    }
    function getLastName(){
        return $this->last_name;
    }
    function getPhone(){
        return $this->phone;
    }
    function getPhone2(){
        return $this->phone2;
    }
    function getEmail(){
        return $this->email;
    }
    function getEmail2(){
        return $this->email2;
    }
    function getPassword(){
        return $this->password;
    }
    function getisAdmin(){
        return $this->is_admin;
    }
    function getCreatedAt(){
        return $this->created_at;
    }
    function getUpdatedAt(){
        return $this->updated_at;
    }
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
?>