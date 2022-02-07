<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    table, td, th{
        border: 1px solid black;
    }
    </style>
</head>
<?php include '../models/sql_functions.php'; ?>
<body>

    <table>
		<thead>
            <tr>
                <th colspan="12"><center>Users</center></th>
            </tr>
			<tr>
                <th><center>ID</center></th>

				<th><center>Student ID</center></th>
				<th><center>Dorm</center></th>
				<th colspan="3"><center>Name</center></th>
				<th colspan="2"><center>Phone</center></th>
				
				<th colspan="2"><center>Email</center></th>
                <th colspan="2"><a href="user.php?action=add">Add</a></th>
			</tr>
		</thead>
		<tbody>
            <?php $users = getStudents(); ?>
            <?php foreach ($users as $row): ?>
            <tr>
                <td><?= $row['user_id'];?></td>
                <td><?= $row['student_id'];?></td>
                <td><?= $row['dorm_num'];?></td>
                <td><?= $row['first_name'];?></td>
				<td><?= $row['middle_name'];?></td>
				<td><?= $row['last_name'];?></td>
                <td><?= $row['phone'];?></td>
                <td><?= $row['phone2'];?></td>
                <td><?= $row['email'];?></td>
				<td><?= $row['email2'];?></td>
       
                <td><a href="user.php?id=<?=$row['user_id']?>&action=update">Edit</a></td>
                <td>Delete</td>
            </tr>
            <?php endforeach; ?>

		</tbody>
    </table>
</body>
</html>