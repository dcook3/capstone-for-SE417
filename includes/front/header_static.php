  <header id="header">
    <div class="container-fluid">
      <div id="logo" class="pull-left">
        <h1><a href="index" class="scrollto">Tiger Eats</a></h1>
        <a href="index"><img src="" alt="" title="" /></a>
      </div>

      <nav id="nav-menu-container">
        <ul class="nav-menu">
            <li class="menu-active"><a href="index">Home</a></li>
            <?php if(!isset($_SESSION['USER'])) { 
                echo '<li class="menu-has-children"><a href="#account">Signup/Login</a>';
            } else {
                echo '<li class="menu-has-children"><a href="#account">My Account</a>';
            }?>
            <ul>
                <?php 
                if(!isset($_SESSION['USER'])) { 
                    echo '<li><a href="login">Login</a></li>';
                    echo '<li><a href="register">Register</a></li>';
                    echo '<li><a href="login?forgotPassword">Reset Password</a></li>';
                } else {
                    echo '<li><a href="" data-toggle="modal" data-target="#exampleProfile">Profile</a></li>';
                    echo '<li><a href="" data-toggle="modal" data-target="#exampleModalOrderHistory">Order History</a></li>';
                    echo '<li><a href="" data-toggle="modal" data-target="#exampleModalScrollable">My Orders</a></li>';
                    echo '<li><a href="logout">Logout</a></li>'; 
                }
                ?>
            </ul>
        </li>
        </ul>
      </nav>
    </div>
  </header>