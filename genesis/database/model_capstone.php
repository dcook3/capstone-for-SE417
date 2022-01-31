<?php
include (__DIR__ . '/db.php');

function getStudents() {
    global $db;

    $results = [];

    $stmt = $db->prepare("SELECT user_id, student_id, first_name, last_name, phone, phone2, email, dorm_num FROM User_Info ORDER BY user_id"); 
    
    if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);   
     }
     
     return ($results);
}