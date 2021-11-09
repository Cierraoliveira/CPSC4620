<?php 
    include ("./components/Session.php");
	include("./components/CleanInput.php");
    // TODO: trim unneeded error variables
    $email = $err_email = "";
    $first_name =  $err_first_name = "";
    $last_name =  $err_last_name = "";
    $password = $err_password = "";
	$Cpassword = $err_Cpassword = "";
    $confirmation =  $err_confirmation = "";
	$update = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = clean_input($_POST["userEmail"]);
        $first_name =  clean_input($_POST["userFirstName"]);
        $last_name =  clean_input($_POST["userLastName"]);
        $password = clean_input($_POST["userPassword"]);
		$Cpassword = clean_input($_POST["userCPassword"]);
        $confirmation = clean_input($_POST["userPasswordConfirm"]);
        // password confirmation validation
        if ($confirmation != $Cpassword) {
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
					if ($first_name != "") {
						$stmt = $mysqli->prepare("UPDATE Users SET First_Name=? WHERE ID=?") or die("Error: ".$mysqli->error);
						$stmt->bind_param("ss", $first_name, $email);
						$stmt->execute();
					}
					if ($last_name != "") {
						$stmt = $mysqli->prepare("UPDATE Users SET Last_Name=? WHERE ID=?") or die("Error: ".$mysqli->error);
						$stmt->bind_param("ss", $last_name, $email);
						$stmt->execute();
					}
					if ($Cpassword != "") {
						$stmt = $mysqli->prepare("UPDATE Users SET Password=? WHERE ID=?") or die("Error: ".$mysqli->error);
						$stmt->bind_param("ss", $Cpassword, $email);
						$stmt->execute();
					}
					$first_name = "";
					$last_name = "";
					$password = "";
					$Cpassword = "";
					$confirmation = "";
					$update = "Profile Updated!";
				}
			}
			$mysqli->close();
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
    <title>Profile Update</title>
</head>
<body>
	<?php include("./components/NavBar.php"); ?>
    <?php if (!$signed_in_user_id) {exit;} ?>
    <div class="container w-25 p-3 bg-light">
        <h3>Profile Update</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" autocomplete="off">
            <!-- email field -->
            <div class="form-group">
                <label for="userEmail">Confirm Email:</label>
                <input type="email" 
                class="form-control" 
                name="userEmail" 
                id="userEmail" 
                value="<?php echo $email;?>"
                required="true">
                <span class="text-danger"><?php echo $err_email; ?></span>
            </div>
			<!-- current password field -->
            <div class="form-group">
                <label for="userEmail">Confirm Password:</label>
                <input type="password" 
                class="form-control" 
                name="userPassword" 
                id="userPassword" 
                value="<?php echo $password;?>" 
                minlength="6"
                required="true">
                <span class="text-danger"><?php echo $err_password; ?></span>
            </div>
            <div class="form-row">
                <!-- first name field -->
                <div class="form-group col">
                    <label for="userFirstName">Change First Name:</label>
                    <input type="text" 
                    class="form-control" 
                    name="userFirstName" 
                    id="userFirstName" 
                    value="<?php echo $first_name;?>">
                    <span class="text-danger"></span>
                </div>
                <!-- last name field -->
                <div class="form-group col">
                    <label for="userLastName">Change Last Name:</label>
                    <input type="text" 
                    class="form-control" 
                    name="userLastName" 
                    id="userLastName" 
                    value="<?php echo $last_name;?>">
                    <span class="text-danger"></span>
                </div>
            </div>
            <!-- Changed password field -->
            <div class="form-group">
                <label for="userEmail">Change Password:</label>
                <input type="password" 
                class="form-control" 
                name="userCPassword" 
                id="userCPassword" 
                value="<?php echo $Cpassword;?>" 
                minlength="6">
                <span class="text-danger"><?php echo $err_Cpassword; ?></span>
            </div>
            <!-- Changed password confirmation field -->
            <div class="form-group">
                <label for="userEmail">Confirm Changed Password:</label>
                <input type="password" 
                class="form-control" 
                name="userPasswordConfirm" 
                id="userPasswordConfirm" 
                value="<?php echo $confirmation;?>" 
                minlength="6">
                <span class="text-danger"><?php echo $err_confirmation; ?></span>
            </div>
			<label for="submit"><?php echo $update ?></label>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>