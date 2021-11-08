<?php 
    include ("./components/Session.php");
    include ("./components/CleanInput.php");

    $email = $err_email = "";
    $password = $err_password = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = clean_input($_POST["userEmail"]);
        $password = clean_input($_POST["userPassword"]);

        if (empty($err_password) && empty($err_email)) {
            $mysqli = new mysqli(
                "mysql1.cs.clemson.edu", 
                "CPSC4620MTb_8b5n", 
                "cpsc4620-metube", 
                "CPSC4620-MeTube_uk72"
            );

            // account validation
            $stmt = $mysqli->prepare("SELECT ID, Password FROM Users WHERE ID=?") or die("Error: ".$mysqli->error);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows == 0) {
                $err_email = "This email is not associated with any accounts.";
            } else {
                $row = $res->fetch_assoc();
                if ($row["Password"] != $password) {
                    $err_password = "Incorrect password.";
                } else {
                    $mysqli->close();
                    $_SESSION["session_user_id"] = $email;
                    header("Location: "."./userView.php");
                    die();
                }
            }
        }
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
    <title>Sign-In</title>
</head>
<body>
    <div class="container w-25 p-3 bg-light">
        <h3>Sign In</h3>
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
            <button type="submit" class="btn btn-primary">Sign In</button>
        </form>
    </div>
</body>
</html>