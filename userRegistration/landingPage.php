<?php
    session_start();
    $user_id = "";
    if (isset($_SESSION["userSession"])) {
        $user_id = $_SESSION["userSession"];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" /> 
    <title>MeTube Development</title>
</head>
<body>
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
                        <a href='./registration.php' class='nav-item btn btn-outline-light'>Sign Up</a>
                        <a href='#' class='nav-item nav-link'>Sign In</a>
                    ";
                }
            ?>
            
        </div>
    </div>
    <div class="container w-50 p-3 bg-light">
        <h1>Debug Landing Page</h1>
    </div>
</body>
</html>