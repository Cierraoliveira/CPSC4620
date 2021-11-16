<?php
    include("./components/Session.php");
    include("./components/MediaThumbnail.php");
    
    $recent_uploads = "";
    $topRated_uploads ="";
    $sub_ids ="";
    $sub_uploads="";

    $mysqli = new mysqli(
        "mysql1.cs.clemson.edu", 
        "CPSC4620MTb_8b5n", 
        "cpsc4620-metube", 
        "CPSC4620-MeTube_uk72"
    );

    // include user specific media
    if ($signed_in_user_id) {

        // get subscription user ids
        $stmt = $mysqli->prepare("SELECT Sub_ID from Subscriptions WHERE User_ID LIKE ?")
                                or die("Error: ".$mysqli->error);
        $stmt -> bind_param('s', $signed_in_user_id);
        $stmt->execute();
        $sub_ids = $stmt->get_result();

        // get media from subscriptions
        if ($sub_ids->num_rows != 0) {
            while ($row = $sub_ids->fetch_assoc()) {
                $sub_array[] = $row["Sub_ID"];
            }

            $sub_ids = implode("','",$sub_array);

            $stmt = $mysqli->prepare("SELECT Media_ID, Path, Title, Description, Media_Type FROM Media 
                                    WHERE User_ID IN ('".$sub_ids."')") 
                                    or die("Error: ".$mysqli->error);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows != 0) {
                while ($row = $res->fetch_assoc()) {
                    $media_id = $row["Media_ID"];
                    $path = $row["Path"];
                    $title = $row["Title"];
                    $description = $row["Description"];
                    $media_type = $row["Media_Type"];
                    $sub_uploads = $sub_uploads . MediaThumbnail($media_id, $path, $title, $description, $media_type);
                }
            }
        }
        
    }

    // most recent data
    $stmt = $mysqli->prepare("SELECT Media_ID, Path, Title, Description, Media_Type FROM Media ORDER BY Date_Uploaded DESC LIMIT 2") 
    or die("Error: ".$mysqli->error);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows != 0) {
        while ($row = $res->fetch_assoc()) {
            $media_id = $row["Media_ID"];
            $path = $row["Path"];
            $title = $row["Title"];
            $description = $row["Description"];
            $media_type = $row["Media_Type"];
            $recent_uploads = $recent_uploads . MediaThumbnail($media_id, $path, $title, $description, $media_type);
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
    <div class="row">
        <div class="col-md-2" style="background-color:#DCDCDC;">
            <h6 style="text-align:center">Browse by Category</h6>
            <form action="./browse.php" method="post" autocomplete="off">
            <div style="text-align: center">
                <button class="btn btn-secondary text-white" name="browse" type="submit" value="music">Music</button><BR><BR>
                <button class="btn btn-secondary text-white" name="browse" type="submit" value="gaming">Gaming</button><BR><BR>
                <button class="btn btn-secondary text-white" name="browse" type="submit" value="animals">Animals</button><BR><BR>
                <button class="btn btn-secondary text-white" name="browse" type="submit" value="sports">Sports</button><BR><BR>
                <button class="btn btn-secondary text-white" name="browse" type="submit" value="comedy">Comedy</button><BR><BR>
                <button class="btn btn-secondary text-white" name="browse" type="submit" value="news">News</button><BR><BR>
                <button class="btn btn-secondary text-white" name="browse" type="submit" value="other">Other</button>
            </div>
            </form>
        </div>

        <div class="col-md-8">
            
            <!--TODO: Fix with sessions; create seperate homepages?-->
            <div class="row"
                <?php if(!$signed_in_user_id) echo " style='display:none';"?>>
                <h4>Subscriptions</h4><BR><BR>
                <?php echo ($sub_uploads) ? $sub_uploads : "No media found."; ?>
            </div><BR><BR>
            
            <!-- <div class="row" style="background-color:#DCDCDC;">
                <form action="#">
                <input type="text" placeholder="Search..." name="search">
                <button type="submit"></button>
                </form>
            </div> -->
            <div class="row">
                <h4>Most recently uploaded media</h4>
            </div>
            <div>
                <?php echo ($recent_uploads) ? $recent_uploads : "No media found."; ?>
            </div><BR><BR>
            
            <div class="row">
                <h4>Highest rated media</h4>
            </div>

        </div>
    </div>
    

    
    
</body>
</html>
