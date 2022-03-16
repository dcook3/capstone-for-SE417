<?php require '../admin/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="vendor/fonts/fonts.css">
    <title>Tiger Eats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="https://use.fontawesome.com/releases/v5.0.4/css/all.css" rel="stylesheet">
</head>
<body class="container">
<h2 class="my-2">Team Members and Responsibilities</h2>
<div class="accordion" id="teamAccordion">
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Dylan Cook
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#teamAccordion">
      <div class="accordion-body">
        <h3>Responsibilities:</h3> 
        <p>Responsible for all orders table related pages and DB tables. Worked with the team to help debug and ensure time contraints were not ignored, and supported the team to keep on when spirits were low.</p>
            <h4>Constructed the following pages:</h4>
            <ul>
                <li>admin\orders.php (admin\index.php)</li>
                <li>cart.php</li>
                <li>includes\models\dylan.php</li>
                <li>Parts of includes\models\ajaxHandler.php that deal with order functionality and some user functionality.</li>
                <li>Parts of this page. (And this super cool accordion component that you are looking at :D )</li>
            </ul>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingTwo">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        Genesis Oliva
      </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#teamAccordion">
      <div class="accordion-body">
        <h3>Responsiblities:</h3> 
        <p>Responsible for all users related pages and DB table. Also worked on a majority of header and footer sections and overall design of the website, which is one of her strongest skills. She pushed in new features out of original scope that improved the website's overall abilities and matching competing app's features, building interesting functionality including the PHPMailer connection, registration functionality, and account verification.</p>
        <h4>Constructed the following pages:</h4>
        <ul>
            <li>All footers and headers located in includes/front on both admin and user levels.</li>
            <li>All config.php files.</li>
            <li>admin\users.php</li>
            <li>admin\logout.php</li>
            <li>admin\login.php</li>
            <li>admin\includes\functions.php</li>
            <li>register.php</li>
            <li>login.php</li>
            <li>loginAccount.php</li>
            <li>forgotPassword.php</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        Lucas Johnson
      </button>
    </h2>
    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#teamAccordion">
      <div class="accordion-body">
        <h3>Responsibilities:</h3>
         <p>Lucas was responsible for menu item and ingredient functionality. He constructed the DB tables and connection code that supports all new menu items and ingredients that get added by the kitchen staff. Made really sleek and modern looking menu interface that has strong backend connectivity, appealing to Lucas' strengths. Also pushed the team to try new methods of programming to make code more efficient. Lucas is the figurehead of the site's AJAX functionality, providing the foundation on which a sizable portion of the website's functionality relies upon.</p>

        <h4>Constructed the following pages:</h4>
        <ul>
            <li>index.php (our menu)</li>
            <li>profile.php</li>
            <li>admin\profile.php</li>
            <li>admin\menu_add.php</li>
            <li>admin\menu_view.php</li>
            <li>includes\models\lucas.js</li>
            <li>includes\models\lucas.php</li>
            <li>The majority of includes\models\ajaxHandler.php</li>
            <li>Parts of this page.</li>
        </ul>
        <i class="mt-2">All pages not listed are either libraries or support code like stylesheets of small JS files. Some are also worked on by multiple people.</i>
      </div>
    </div>
  </div>
</div>

<h2>PowerPoint Presentation</h2>
<iframe src="https://docs.google.com/presentation/d/e/2PACX-1vQe-bJWOk594RGMoFdUJMP4E-nOK88l1ASKaY7LtRmqUzvQNGN__ilPx-6NwP7pMQ/embed?start=false&loop=false&delayms=3000" frameborder="0" width="1280" height="749" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>

<h2>Customer User Pages and Screenshots</h2>
<div class="d-flex flex-wrap justify-content-center">
    <div class="card" style="width: 18rem;">
        <div class="card-title my-2">
            <h5 class="card-text text-center">Menu Page 1</h5>
        </div>
        <img src="assets\menu_1.png" class="card-img-top border border-dark" alt="menu page sections picture">
    </div>
    <div class="card" style="width: 18rem;">
        <div class="card-title my-2">
            <h5 class="card-text text-center">Menu Page 2</h5>
        </div>
        <img src="assets\menu_2.png" class="card-img-top border border-dark" alt="menu page menu items picture">
    </div>
    <div class="card" style="width: 18rem;">
        <div class="card-title my-2">
            <h5 class="card-text text-center">Menu Page 2 - List View</h5>
        </div>
        <img src="assets\menu_2_list.png" class="card-img-top border border-dark" alt="menu page picture with list view enabled picture">
    </div>
    <div class="card" style="width: 18rem;">
        <div class="card-title my-2">
            <h5 class="card-text text-center">Menu Page - Item View</h5>
        </div>
        <img src="assets\menu_item_view.png" class="card-img-top border border-dark" alt="menu page, specific item picture">
    </div>
    <div class="card" style="width: 18rem;">
        <div class="card-title my-2">
            <h5 class="card-text text-center">Cart Page</h5>
        </div>
        <img src="assets\cart.png" class="card-img-top border border-dark" alt="cart page picture">
    </div>
</div>

<h2>Kitchen Faculty User Pages and Screenshots</h2>
<div class="d-flex flex-wrap justify-content-center">
    <div class="card" style="width: 50rem;">
        <div class="card-title my-2">
            <h5 class="card-text text-center">Menu Items View</h5>
        </div>
        <img src="assets\menu_view.png" class="card-img-top border border-dark" alt="menu item view page picture">
    </div>
</div>
<div class="d-flex flex-wrap justify-content-center">
    <div class="card" style="width: 50rem;">
        <div class="card-title my-2">
            <h5 class="card-text text-center">Menu Item Add</h5>
        </div>
        <img src="assets\menu_add.png" class="card-img-top border border-dark" alt="menu item add page picture">
    </div>
</div>
<div class="d-flex flex-wrap justify-content-center">
    <div class="card" style="width: 50rem;">
        <div class="card-title my-2">
            <h5 class="card-text text-center">Menu Item Add - With Ingredient</h5>
        </div>
        <img src="assets\menu_add_2.png" class="card-img-top border border-dark" alt="menu item add page with ingredient picture">
    </div>
</div>
<div class="d-flex flex-wrap justify-content-center">
    <div class="card" style="width: 50rem;">
        <div class="card-title my-2">
            <h5 class="card-text text-center">Orders</h5>
        </div>
        <img src="assets\orders.png" class="card-img-top border border-dark" alt="orders page picture">
    </div>
</div>
<div class="d-flex flex-wrap justify-content-center">
    <div class="card" style="width: 50rem;">
        <div class="card-title my-2">
            <h5 class="card-text text-center">Users View</h5>
        </div>
        <img src="assets\users.png" class="card-img-top border border-dark" alt="users page picture">
    </div>
</div>

<h2>Links</h2>
<a href="">Source code download</a>
<a href="https://github.com/JohnsonL104/as_capstone">Github Repository</a>


<?php include '../admin/includes/front/footer.php'; ?>