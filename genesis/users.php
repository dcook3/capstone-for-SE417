<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php include __DIR__ . '/database/model_capstone.php'; ?>
<body>

    <table>
		<thead>
            <tr>
                <th colspan="9"><center>Users</center></th>
            </tr>
			<tr>
                <th><center>ID</center></th>
				<th><center>Student Name</center></th>
				<th><center>Student ID</center></th>
				<th><center>Email</center></th>
				<th><center>Phone #</center></th>
				<th><center>Phone 2</center></th>
				<th><center>Dorm</center></th>
				<th><center>Edit</center></th>
				<th><center>Delete</center></th>
			</tr>
		</thead>
		<tbody>
            <?php $users = getStudents(); ?>
            <?php foreach ($users as $row): ?>
            <tr>
                <td><?= $row['user_id'];?></td>
                <td><?= $row['student_id'];?></td>
                <td><?= $row['first_name'];?></td>
                <td><?= $row['last_name'];?></td>
                <td><?= $row['phone'];?></td>
                <td><?= $row['phone2'];?></td>
                <td><?= $row['email'];?></td>
                <td><?= $row['dorm_num'];?></td>

                <td>Edit Btn
                <a href="users.php?id=<?=$row['user_id']?>&action=update"></a>
                </td>
                <td>Delete Btn</td>
            </tr>
            <?php endforeach; ?>
		</tbody>
    </table>
</body>
</html>