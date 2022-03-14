  <header id="header">
    <div class="container-fluid">
      <a id = "backBtn" class = "btn-hidden pull-left">
          <i class="fa fas fa-arrow-left"></i>
      </a>
      <div id="logo" class="pull-left">
      
        <h1><a href="index" class="scrollto text-decoration-none" style = "border-left: 4px solid #ecae4f;">Tiger Eats</a></h1>
        <a href="index"><img src="" alt="" title="" /></a>
      </div>
      

      <nav id="nav-menu-container">
        <ul class="nav-menu">
            <li class="menu-active"><a href="index">Home</a></li>
            
              <?php 
              if(!isset($_SESSION['USER'])) { 
                  echo '<li><a href="login">Login</a></li>';
                  echo '<li><a href="register">Register</a></li>';
                  echo '<li><a href="login?forgotPassword">Reset Password</a></li>';
              } else {
                  echo '<li><a href="profile.php">Profile</a></li>';
                  echo '<li><a href="" data-toggle="modal" data-target="#exampleModalOrderHistory">Order History</a></li>';
                  echo '<li><a href="logout">Logout</a></li>'; 
              }
              ?>
            </ul>
        </li>
        </ul>
      </nav>
    </div>
  </header>