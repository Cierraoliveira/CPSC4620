<?php 
    include("./components/Session.php");
    if (!$signed_in_user_id) {exit;}
    include("./components/MediaThumbnail.php");
    $user_uploads = "";

    $mysqli = new mysqli(
        "mysql1.cs.clemson.edu", 
        "CPSC4620MTb_8b5n", 
        "cpsc4620-metube", 
        "CPSC4620-MeTube_uk72"
    );

    $stmt = $mysqli->prepare("SELECT Media_ID, Path, Title, Description FROM Media WHERE User_ID=?") 
    or die("Error: ".$mysqli->error);
    $stmt->bind_param("s", $signed_in_user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows != 0) {
        while ($row = $res->fetch_assoc()) {
            $media_id = $row["Media_ID"];
            $path = $row["Path"];
            $title = $row["Title"];
            $description = $row["Description"];
            $user_uploads = $user_uploads . MediaThumbnail($media_id, $path, $title, $description);
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
    <title>MeTube Development</title>
</head>
<body>
    <?php include("./components/NavBar.php"); ?>
    <?php echo "<h3>$signed_in_user_id</h3>" ?>
    <a href="./upload.php" class="btn btn-secondary text-white">Upload</a>
    <h3>Your Media</h3>
    <?php echo ($user_uploads) ? $user_uploads : "No media found."; ?>
    
	<h3>Profile update</h3>
	<a href="./updateProfile.php" class="btn btn-secondary text-white">ProfileUpdate</a>
	
	<h3>Contacts</h3>
	<a href="./userContacts.php" class="btn btn-secondary text-white">Contacts</a>
</body>
</html>