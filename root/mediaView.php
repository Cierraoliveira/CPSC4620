<?php 
    include("./components/Session.php");
    if (!$user_id) {exit;}
    include("./components/MediaFull.php");

    $media_id = "";
    $err_media = "";

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET["id"])) {
            $media_id = htmlspecialchars($_GET["id"]);

            $mysqli = new mysqli(
                "mysql1.cs.clemson.edu", 
                "CPSC4620MTb_8b5n", 
                "cpsc4620-metube", 
                "CPSC4620-MeTube_uk72"
            );

            $stmt = $mysqli->prepare("SELECT Path, Title, Description FROM Media WHERE Media_ID=?") 
            or die("Error: ".$mysqli->error);
            $stmt->bind_param("s", $media_id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows == 0) {
                $err_media = "Media not found.";
            } else {
                $row = $res->fetch_assoc();
                $path = $row["Path"];
                $title = $row["Title"];
                $description = $row["Description"];
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
    <title><?php echo ($err_media) ? "Error" : $title ?></title>
</head>
<body>
    <?php include("./components/NavBar.php"); ?>
    <?php echo ($err_media) ? $err_media : MediaFull($path, $title, $description) ?>
</body>
</html>