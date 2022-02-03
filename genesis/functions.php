<?php
include (__DIR__ . '/database/model_capstone.php');


function isPostRequest() {
    return ( filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST' );
    }

if(isset($_GET['id']))
    {
        $id = filter_input(INPUT_GET, 'id');
        $action = filter_input(INPUT_GET, 'action');

        $row = getStudent($id);
        $stud_img = $row[0]['img'];
        $stud_id = $row[0]['student_id'];
        $dorm_no = $row[0]['dorm_num'];
        $firstName = $row[0]['first_name'];
		$middleName = $row[0]['middle_name'];
        $lastName = $row[0]['last_name'];
        $phoneNumber = $row[0]['phone'];
        $phoneNumber2 = $row[0]['phone2'];
        $stud_email = $row[0]['email'];
    }
    else 
    {
        $id = "";
        $action = filter_input(INPUT_GET, 'action');

        $stud_img = "";
        $stud_id = "";
        $dorm_no = "";
        $firstName = "";
		$middleName = "";
        $lastName = "";
        $phoneNumber = "";
        $phoneNumber2 = "";
        $stud_email = "";
    }



    if(isset($_POST['action'])) 
    {
        $action = filter_input(INPUT_POST, 'action');

        if(isPostRequest())
        {
            $stud_img = filter_input(INPUT_POST, 'img');
            $stud_id = filter_input(INPUT_POST, 'stud_id');
            $dorm_no = filter_input(INPUT_POST, 'dorm_no');
            $firstName = filter_input(INPUT_POST, 'firstName');
			$middleName = filter_input(INPUT_POST, 'middleName');
            $lastName = filter_input(INPUT_POST, 'lastName');
            $phoneNumber = filter_input(INPUT_POST, 'phoneNumber');
            $phoneNumber2 = filter_input(INPUT_POST, 'phoneNumber2');
            $stud_email = filter_input(INPUT_POST, 'stud_email');
    
            if($action == "add")
            {
                $results = addStudent($stud_id, $dorm_no, $firstName, $lastName, $phoneNumber, $phoneNumber2, $stud_email);
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
                    $results = updateStudent($id, $img, $student_id, $dorm_num, $first_name, $middle_name, $last_name, $phone, $phone2, $email);
                }
                
            }

        }

}
