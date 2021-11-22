<?php 
    include("./components/Session.php");
    if (!$signed_in_user_id) {exit;}
    include("./components/ContactList.php");
	include("./components/CleanInput.php");
    $user_contacts = "";
	$contact_email = $err_email = "";
	$remove_email = $err_remove_email = "";

    $mysqli = new mysqli(
        "mysql1.cs.clemson.edu", 
        "CPSC4620MTb_8b5n", 
        "cpsc4620-metube", 
        "CPSC4620-MeTube_uk72"
    );

	//Adds and/or removes contact id from list from form data
	//Removal of this method is temporary
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$contact_email = clean_input($_POST["contactEmail"]);
		$remove_email = clean_input($_POST["removedContactEmail"]);
		
		//Checks for non-empty string
		if ($contact_email != "") {
			$stmt = $mysqli->prepare("SELECT Contact_ID FROM Contacts WHERE User_ID=? AND Contact_ID=?") or die("Error: ".$mysqli->error);
			$stmt->bind_param("ss", $signed_in_user_id, $contact_email);
			$stmt->execute();
			$res = $stmt->get_result();
			//Checks for if contact is already on list
			if ($res->num_rows > 0) {
				$err_email = "$contact_email is already in your contact list.";
			} else {
				$stmt = $mysqli->prepare("SELECT ID FROM Users WHERE ID=?") or die("Error: ".$mysqli->error);
				$stmt->bind_param("s", $contact_email);
				$stmt->execute();
				$res = $stmt->get_result();
				//Checks if contact has an account 
				if ($res->num_rows == 0) {
					$err_email = "$contact_email is not associated with any accounts.";
				} else {
					//Add contact to list
					$stmt = $mysqli->prepare("INSERT INTO Contacts VALUES (?, ?)") or die("Error: ".$mysqli->error);
					$stmt->bind_param("ss", $signed_in_user_id, $contact_email);
					$stmt->execute();
					$err_email = "$contact_email added to contact list.";
					
				}
			}
		}
		//Checks for non-empty string before removing id from list
		if ($remove_email != "") {
			// check if user is in contact list
			$stmt = $mysqli->prepare("SELECT Contact_ID FROM Contacts WHERE User_ID=? AND Contact_ID=?") or die("Error: ".$mysqli->error);
			$stmt->bind_param("ss", $signed_in_user_id, $remove_email);
			$stmt->execute();
			$res = $stmt->get_result();
			if ($res->num_rows == 0) {
				$err_remove_email = "$remove_email is not in your contact list.";
			}
			else{
				$stmt = $mysqli->prepare("DELETE FROM Contacts WHERE User_ID=? AND Contact_ID=?") or die("Error: ".$mysqli->error);
				$stmt->bind_param("ss", $signed_in_user_id, $remove_email);
				$stmt->execute();
				$err_remove_email = "$remove_email removed from contact list.";
			}
			
		}
		$contact_email = "";
		$remove_email = "";
	}


	//collects contact list into user_contacts
    $stmt = $mysqli->prepare("SELECT Contact_ID FROM Contacts WHERE User_ID=?") 
    or die("Error: ".$mysqli->error);
    $stmt->bind_param("s", $signed_in_user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows != 0) {
        while ($row = $res->fetch_assoc()) {
            $contact_id = $row["Contact_ID"];
            $user_contacts = $user_contacts . ContactList($contact_id);
        }
    }
    $mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" /> 
    <title>ContactList Development</title>
</head>
<body>
    <?php include("./components/NavBar.php"); ?>
    <?php echo "<h3>$signed_in_user_id</h3>" ?>
	
	<div class="container w-25 p-3 bg-light">
		<form action"<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" autocomplete="off">
			<!-- Add contact field -->
			<div class="form-group">
				<label for="userEmail">Add Contact ID:</label>
				<input type="email" 
				class="form-control" 
				name="contactEmail" 
				id="contactEmail" 
				value="<?php echo $contact_email;?>">
				<span class="text-danger"><?php echo $err_email; ?></span>
			</div>
			<!-- Remove contact field -->
			<div class="form-group">
				<label for="userEmail">Remove Contact ID:</label>
				<input type="email" 
				class="form-control" 
				name="removedContactEmail" 
				id="removedContactEmail" 
				value="<?php echo $remove_email;?>">
				<span class="text-danger"><?php echo $err_remove_email; ?></span>
			</div>
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
	</div>
	<!-- Contact List -->
    <h3>Your Contacts:</h3>
    <?php echo ($user_contacts) ? $user_contacts : "No contacts found."; ?>
</body>
</html>