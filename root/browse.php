<?php
    include("./components/Session.php");
    include("./components/MediaThumbnail.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $category = $_POST['browse'];

        $category_media = "";

        $mysqli = new mysqli(
            "mysql1.cs.clemson.edu", 
            "CPSC4620MTb_8b5n", 
            "cpsc4620-metube", 
            "CPSC4620-MeTube_uk72"
        );

        $stmt = $mysqli->prepare("SELECT Media_ID, Path, Title, Description, Media_Type, User_ID FROM Media WHERE Category LIKE ?") 
        or die("Error: ".$mysqli->error);
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows != 0) {
            while ($row = $res->fetch_assoc()) {
                $media_id = $row["Media_ID"];
                $path = $row["Path"];
                $title = $row["Title"];
                $description = $row["Description"];
                $media_type = $row["Media_Type"];
                $user_id = $row["User_ID"];
                $category_media = $category_media . MediaThumbnail($media_id, $path, $title, $description, $media_type, $user_id);
            }
        }

        $mysqli->close();
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
            <div class="row">
                <?php echo "<h3>$category</h3>" ?>
            </div>
            <div>
                <?php echo ($category_media) ? $category_media : "No media found."; ?>
            </div>

        </div>
    </div>
</body>