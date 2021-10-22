<?php 
    // TODO: trim unneeded error variables
    $email = $err_email = "";
    $first_name =  $err_first_name = "";
    $last_name =  $err_last_name = "";
    $password = $err_password = "";
    $confirmation =  $err_confirmation = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = clean_input($_POST["userEmail"]);
        $first_name =  clean_input($_POST["userFirstName"]);
        $last_name =  clean_input($_POST["userLastName"]);
        $password = clean_input($_POST["userPassword"]);
        $confirmation = clean_input($_POST["userPasswordConfirm"]);
        // password confirmation validation
        if ($confirmation != $password) {
            $err_confirmation = "Passwords do not match.";
        }

        if (empty($err_confirmation) && empty($err_email)) {
            // post user information upon valid registration
            $mysqli = new mysqli(
                "mysql1.cs.clemson.edu", 
                "CPSC4620MTb_8b5n", 
                "cpsc4620-metube", 
                "CPSC4620-MeTube_uk72"
            );

            // duplicate account validation
            $stmt = $mysqli->prepare("SELECT ID from Users WHERE ID=?") or die("Error: ".$mysqli->error);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows != 0) {
                $err_email = "This email is associated with an existing account.";
            } else {
                // insert validated records
                $stmt = $mysqli->prepare("INSERT INTO Users VALUES (?, ?, ?, ?, 0)") or die("Error: ".$mysqli->error);
                $stmt->bind_param("ssss", $email, $first_name, $last_name, $password);
                $stmt->execute();

                $mysqli->close();
                // TODO: wrap this in a session so we know which user is logged in
                header("Location: "."./index.html");
                die();
            }
            $mysqli->close();
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
            <!-- email field -->
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
            <div class="form-row">
                <!-- first name field -->
                <div class="form-group col">
                    <label for="userFirstName">First Name:</label>
                    <input type="text" 
                    class="form-control" 
                    name="userFirstName" 
                    id="userFirstName" 
                    value="<?php echo $first_name;?>"
                    required="true">
                    <span class="text-danger"></span>
                </div>
                <!-- last name field -->
                <div class="form-group col">
                    <label for="userLastName">Last Name:</label>
                    <input type="text" 
                    class="form-control" 
                    name="userLastName" 
                    id="userLastName" 
                    value="<?php echo $last_name;?>"
                    required="true">
                    <span class="text-danger"></span>
                </div>
            </div>
            <!-- password field -->
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
            <!-- confirmation field -->
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