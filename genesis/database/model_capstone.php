<?php
include (__DIR__ . '/db.php');

function getStudents () {
    global $db;
    
    $results = [];

    $stmt = $db->prepare("SELECT id, student_id, dorm_num, first_name, last_name, phone, phone2, email FROM User_Info ORDER BY id"); 
    
    if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
             
     }
     
     return ($results);
}

function addStudent($student_id, $dorm_num, $first_name, $last_name, $phone, $email, $password) {
    global $db;
    $results = "Not added";

    $stmt = $db->prepare("INSERT INTO User_Info SET student_id = :student_id, dorm_num = :dorm_num, first_name = :first_name, last_name = :last_name, phone = :phone, 
    email = :email, password = :password");

    $stmt->bindValue(':student_id', $student_id);
    $stmt->bindValue(':dorm_num', $dorm_num);
    $stmt->bindValue(':first_name', $first_name);
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

        $stmt = $db->prepare("SELECT id, img, student_id, dorm_num, first_name, last_name, phone, phone2, email 
        FROM User_Login WHERE id=:id"); 

        $stmt->bindValue(':id', $id);
        
        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
         }
         
         return ($results);
}

function updateStudent($id, $img, $student_id, $dorm_num, $first_name, $last_name, $phone, $phone2, $email) {
    global $db;

    $results = "Data NOT Updated";
    
    $stmt = $db->prepare("UPDATE User_Login SET img = :img, student_id = :student_id, dorm_num = :dorm_num, first_name = :first_name,
    last_name = :last_name, phone = :phone, phone2 = :phone2, email = :email WHERE id=:id");
    
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':img', $img);
    $stmt->bindValue(':student_id', $student_id);
    $stmt->bindValue(':dorm_num', $dorm_num);
    $stmt->bindValue(':first_name', $first_name);
    $stmt->bindValue(':last_name', $last_name);
    $stmt->bindValue(':phone', $phone);
    $stmt->bindValue(':phone2', $phone2);
    $stmt->bindValue(':email', $email);

  
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        $results = 'Data Updated';
    }
    
    return ($results);
}

function deleteStudent ($id) {
    global $db;
    
    $results = "Data was not deleted";

    $stmt = $db->prepare("DELETE FROM User_Login WHERE id=:id");
    
    $stmt->bindValue(':id', $id);
        
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        $results = 'Data Deleted';
    }
    
    return ($results);
}