<?php ?>
<div class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a href="#" class="navbar-brand">MeTube</a>
    <div class="navbar-nav ml-auto">
        <?php 
            if ($user_id) {
                echo "
                    <a href='#' class='nav-item nav-link text-white'>$user_id</a>
                    <a href='./signOut.php' class='nav-item nav-link'>Sign Out</a>
                ";
            } else {
                echo "
                    <a class='nav-item nav-link'>Not Signed In</a>
                    <a href='./registration.php' class='nav-item btn btn-outline-light'>Sign Up</a>
                    <a href='./signIn.php' class='nav-item nav-link'>Sign In</a>
                ";
            }
        ?>
    </div>
</div>