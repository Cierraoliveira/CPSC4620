<?php
    session_start();
    $user_id = "";
    if (isset($_SESSION["userSession"])) {
        $user_id = $_SESSION["userSession"];
    }

    function component_media() {
        return "
            <div class='row m-2 p-2 border'>
                <div class='col'>
                    <div class='bg-dark' style='width: 320px; height: 240px'></div>
                </div>
                <div class='col'>
                    <h4>Media Title</h4>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Porro saepe reiciendis eius nisi sit. Esse natus, explicabo, accusamus dolorum qui facere fugiat ratione aperiam voluptatibus ipsa praesentium et placeat. Est?</p>
                </div>
            </div>
        ";
    }

    function component_msg() {
        return "
            <div class='text-left'>
                <p class='mb-0 text-primary'>User:</p>
                <p class='border-bottom'>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo architecto iste temporibus hic, sint consequatur ipsam porro quam labore suscipit. Labore incidunt ducimus recusandae asperiores perspiciatis accusamus, sint rerum iusto?</p>
            </div>
        ";
    }

    function component_msg_reply() {
        return "
            <div class='text-right'>
                <p class='mb-0 text-warning'>User:</p>
                <p class='border-bottom'>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo architecto iste temporibus hic, sint consequatur ipsam porro quam labore suscipit. Labore incidunt ducimus recusandae asperiores perspiciatis accusamus, sint rerum iusto?</p>
            </div>
        ";
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
    <div class="container-fluid">
        <div class="row g-2">
            <div class="col-8">
                <div class="p-3 bg-light">
                    <h1>Channel User ID</h1>
                    <div class="container" style="height: 500px; overflow-y: scroll">
                        <?php echo component_media(); ?>
                        <?php echo component_media(); ?>
                        <?php echo component_media(); ?>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="p-3 bg-light">
                    <div class="mb-3">
                        <a href="#" class="btn btn-danger">Subscribe</a>
                        <a href="#" class="btn btn-warning">Add Contact</a>
                    </div>
                    <h4>Messaging</h4>
                    <div class="container bg-white mb-3" style="height: 500px; overflow-y: scroll">
                        <?php echo component_msg(); ?>
                        <?php echo component_msg_reply(); ?>
                        <?php echo component_msg(); ?>
                    </div>
                    <form action="" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3" placeholder="Message" name="message">
                            <input type="submit" value="Send" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>