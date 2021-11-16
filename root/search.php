<?php

    include("./components/Session.php");
    include("./components/MediaThumbnail.php");

    $search_string = "";
    $search_results = "";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $search_string = $_POST['search'];

        $mysqli = new mysqli(
            "mysql1.cs.clemson.edu", 
            "CPSC4620MTb_8b5n", 
            "cpsc4620-metube", 
            "CPSC4620-MeTube_uk72"
        );

        //get relevant media
        $stmt = $mysqli->prepare("SELECT Media.Media_ID, Path, Title, Description, Media_Type, User_ID, Keyword FROM Media 
                                JOIN Keywords ON Media.Media_ID=Keywords.Media_ID WHERE Keyword LIKE ?") 
                                or die("Error: ".$mysqli->error);
        $stmt -> bind_param('s', $search_string);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows != 0) {
            while ($row = $res->fetch_assoc()) {
                //echo $row["Keyword"];
                $media_id = $row["Media_ID"];
                $path = $row["Path"];
                $title = $row["Title"];
                $description = $row["Description"];
                $media_type = $row["Media_Type"];
                $user_id = $row["User_ID"];
                $search_results = $search_results . MediaThumbnail($media_id, $path, $title, $description, $media_type, $user_id);
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
    <h4>Search results for '<?php echo "$search_string" ?>':</h4>
    <?php echo ($search_results) ? $search_results : "No media found."; ?> 
</body>

