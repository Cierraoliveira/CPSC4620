<?php 
    include("./components/Session.php");
    if (!$signed_in_user_id) {exit;}
    include("./components/MediaThumbnail.php");
    $user_uploads = "";
    $user_subscriptions = "";
    $user_favorites = "";

    $mysqli = new mysqli(
        "mysql1.cs.clemson.edu", 
        "CPSC4620MTb_8b5n", 
        "cpsc4620-metube", 
        "CPSC4620-MeTube_uk72"
    );

    $stmt = $mysqli->prepare("SELECT Media_ID, Path, Title, Description, Media_Type FROM Media WHERE User_ID=?") 
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
            $media_type = $row["Media_Type"];
            $user_uploads = $user_uploads . MediaThumbnail($media_id, $path, $title, $description, $media_type);
        }
    }
    $stmt = $mysqli->prepare("SELECT Sub_ID from Subscriptions WHERE User_ID LIKE ?")
    or die("Error: ".$mysqli->error);
    $stmt -> bind_param('s', $signed_in_user_id);
    $stmt->execute();
    $sub_ids = $stmt->get_result();

    // get media from subscriptions
    if ($sub_ids->num_rows != 0) {
        $subs = array();
        while ($row = $sub_ids->fetch_assoc()) {
            if (!in_array($row["Sub_ID"], $subs)) {
                array_push($subs, $row["Sub_ID"]);
            }
        }
        foreach($subs as $sub) {
            $query = array(
                "id" => $sub
            );
            $linkChannel = "./channelView.php?" . http_build_query($query);
            $user_subscriptions = $user_subscriptions . "<p>
            <a href='$linkChannel' style='color:black !important'><u>$sub</u></a>
            </p>";
        }
    }

    // get favorites
    $stmt = $mysqli->prepare("SELECT Media.Media_ID, Media.Title, Media.User_ID from Favorites 
    INNER JOIN Media ON Favorites.Media_ID=Media.Media_ID 
    WHERE Favorites.User_ID=?")
    or die("Error: ".$mysqli->error);
    $stmt -> bind_param('s', $signed_in_user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows != 0) {
        while ($row = $res->fetch_assoc()) {
            $title = $row["Title"];
            $uploader = $row["User_ID"];
            // channel link
            $linkChannel = "";
            if ($signed_in_user_id != $uploader) {
                $query = array(
                    "id" => $uploader
                );
                $linkChannel = "./channelView.php?" . http_build_query($query);
                $upload_element = "<a style='color:black !important' href='$linkChannel'><u>$uploader</u></a>";
            } else {
                $upload_element = "you";
            }
            // channel link
            $query = array(
                "id" => $row["Media_ID"]
            );
            $linkMedia = "./mediaView.php?" . http_build_query($query);
            $user_favorites = $user_favorites . "
                <p>
                    <a class='font-weight-bold font-underline' style='color:black !important' href='$linkMedia'><u>$title</u></a> uploaded by
                    $upload_element</p>
            ";
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <h3>Your Media</h3>
                <div class="border border-primary p-3" style="max-height:500px; overflow-y:scroll">
                    <?php echo ($user_uploads) ? $user_uploads : "No media found."; ?>
                </div>
            </div>
            <div class="col">
                    <div class="mb-3">
                        <h4>Your Subscriptions</h4>
                        <div class="border border-primary p-3" style="max-height:200px; overflow-y:scroll">
                            <?php echo ($user_subscriptions) ? $user_subscriptions : "No subscriptions found."; ?>
                        </div>
                    </div>
                    <div class="">
                        <h4>Your Favorites</h4>
                        <div class="border border-primary p-3" style="max-height:300px; overflow-y:scroll">
                            <?php echo ($user_favorites) ? $user_favorites : "No favorites found."; ?>
                        </div>
                    </div>
            </div>
        </div>

    </div>
    
	<h3>Profile update</h3>
	<a href="./updateProfile.php" class="btn btn-secondary text-white">ProfileUpdate</a>
	
	<h3>Contacts</h3>
	<a href="./userContacts.php" class="btn btn-secondary text-white">Contacts</a>
</body>
</html>