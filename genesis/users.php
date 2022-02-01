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
<?php include __DIR__ . '/functions.php'; ?>
<body>
<a href="user.php?action=add" id="addBtn">Add Student</a>

    <table>
		<thead>
            <tr>
                <th colspan="8"><center>Users</center></th>
            </tr>
			<tr>
                <th><center>id</center></th>

				<th><center>Student Name</center></th>
				<th><center>Student ID</center></th>
				<th><center>Email</center></th>
				<th><center>Phone #</center></th>
				<th><center>Phone 2</center></th>
				<th><center>Dorm</center></th>
                <th colspan="2"><center>***</center></th>
			</tr>
		</thead>
		<tbody>
            <?php $users = getStudents(); ?>
            <?php foreach ($users as $row): ?>
            <tr>
                <td><?= $row['id'];?></td>

                <td><?= $row['student_id'];?></td>
                <td><?= $row['first_name'];?> <?= $row['last_name'];?></td>
                <td><?= $row['phone'];?></td>
                <td><?= $row['phone2'];?></td>
                <td><?= $row['email'];?></td>
                <td><?= $row['dorm_num'];?></td>

                <td><a href="user.php?id=<?=$row['id']?>&action=update"></a></td>
                <td></td>
            </tr>
            <?php endforeach; ?>

		</tbody>
    </table>
</body>
</html>