<?php ?>
<div class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a href="./homepage.php" class="navbar-brand">MeTube</a>
    <form class="form-inline my-2 my-lg-0" action="./search.php" method="post">
      <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search" aria-label="Search">
      <button class="nav-item btn btn-outline-light" type="submit">Search</button>
    </form>
    <div class="navbar-nav ml-auto">
        <?php 
            if ($signed_in_user_id) {
                echo "
                    <a href='./userView.php' class='nav-item nav-link text-white'>$signed_in_user_id</a>
                    <a href='./signOut.php' class='nav-item nav-link'>Sign Out</a>
                ";
            } 
            else {
                echo "
                    <a class='nav-item nav-link'>Not Signed In</a>
                    <a href='./registration.php' class='nav-item btn btn-outline-light'>Sign Up</a>
                    <a href='./signIn.php' class='nav-item nav-link'>Sign In</a>
                ";
            } 
        ?>
    </div>
</div>