<?php
function buildPath($l, $f)
{
    $path = "as_capstone/";
    for($i = 0; $i <= $l; $i++)
    {
        $path .= "../";
    }
    echo $path .= $f;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiger Eats</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="<?= buildPath($levels, "include/bootstrap.css"); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= buildpath($levels, "admin\assets\css\cropper.css") ?>" />
    <script src="<?= buildpath($levels, "admin\assets\js\cropper.js") ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="https://use.fontawesome.com/releases/v5.0.4/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
</head>
<body>
    <div class="container-fluid">
    <nav class="navbar navbar-expand-md text-white navbar-primary bg-primary card-header">
        <a class="navbar-brand text-white" href="index"><i class="fas fa-home mr-2"></i></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link text-white" href="users"><i class="fas fa-users mr-2"></i>Users</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="index">Orders</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="menu_view">Menu</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="profile.php"><i class="fab fa-500px mr-2"></i>Profile <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="logout"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
            </li>


          </ul>

        </div>
      </nav>