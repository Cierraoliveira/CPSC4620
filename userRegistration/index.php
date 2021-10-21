<?php 
    $email = $err_email = "";
    $password = $err_password = "";
    $confirmation =  $err_confirmation = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = clean_input($_POST["userEmail"]);
        $password = clean_input($_POST["userPassword"]);
        $confirmation = clean_input($_POST["userPasswordConfirm"]);
        if ($confirmation != $password) {
            $err_confirmation = "Passwords do not match.";
        }
        if (empty($err_email) && empty($err_password) && empty($err_confirmation)) {
            // TODO: post user data to mysql tables
            header("Location: "."./registered.html");
            die();
        }
    }

    function clean_input($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
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
    <title>User Registration</title>
</head>
<body>
    <div class="container w-25 p-3 bg-light">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" autocomplete="off">
            <div class="form-group">
                <label for="userEmail">Email:</label>
                <input type="email" 
                class="form-control" 
                name="userEmail" 
                id="userEmail" 
                value="<?php echo $email;?>"
                required="true">
                <span class="text-danger"><?php echo $err_email; ?></span>
            </div>
            <div class="form-group">
                <label for="userEmail">Password:</label>
                <input type="password" 
                class="form-control" 
                name="userPassword" 
                id="userPassword" 
                value="<?php echo $password;?>" 
                minlength="6"
                required="true">
                <span class="text-danger"><?php echo $err_password; ?></span>
            </div>
            <div class="form-group">
                <label for="userEmail">Confirm Password:</label>
                <input type="password" 
                class="form-control" 
                name="userPasswordConfirm" 
                id="userPasswordConfirm" 
                value="<?php echo $confirmation;?>" 
                minlength="6"
                required="true">
                <span class="text-danger"><?php echo $err_confirmation; ?></span>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</body>
</html>