<?php 
    include("./components/Session.php");
    include("./components/MediaThumbnail.php");
    
    $user_id = "";
    $user_uploads = "";
    $is_subscribed = false;

    if (isset($_GET)) {
        if (isset($_GET["id"])) {
            $user_id = htmlspecialchars($_GET["id"]);

            $mysqli = new mysqli(
                "mysql1.cs.clemson.edu", 
                "CPSC4620MTb_8b5n", 
                "cpsc4620-metube", 
                "CPSC4620-MeTube_uk72"
            );

            $stmt = $mysqli->prepare("SELECT ID FROM Users WHERE ID=?") 
            or die("Error: ".$mysqli->error);
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows != 0) {

                // redirect to UserView
                if ($user_id == $signed_in_user_id) {
                    header("Location: "."./userView.php");
                    die();
                }

                $stmt = $mysqli->prepare("SELECT Media_ID, Path, Title, Description, Media_Type FROM Media WHERE User_ID=?") 
                or die("Error: ".$mysqli->error);
                $stmt->bind_param("s", $user_id);
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
            } else {
                $user_id = "";
            }
            $stmt = $mysqli->prepare("SELECT Sub_ID FROM Subscriptions WHERE User_ID=?") 
                or die("Error: ".$mysqli->error);
            $stmt->bind_param("s", $signed_in_user_id);
            $stmt->execute();
            $res = $stmt->get_result();
            $subs = $res->fetch_all(MYSQLI_ASSOC);
            foreach ($subs as $id) {
                if ($id["Sub_ID"] == $user_id) {
                    $is_subscribed = true;
                    break;
                }
            }
            $mysqli->close();
        }
        if (isset($_POST["subscribe"])){ 
            if ($_POST["subscribe"] == false) {
                $mysqli = new mysqli(
                    "mysql1.cs.clemson.edu", 
                    "CPSC4620MTb_8b5n", 
                    "cpsc4620-metube", 
                    "CPSC4620-MeTube_uk72"
                );
                $stmt = $mysqli -> prepare("INSERT INTO Subscriptions VALUES(?,?)") or die("Error: ".$mysqli->error);
                $stmt -> bind_param('ss',$signed_in_user_id, $user_id);
                $stmt -> execute();
                header("Location: ".$_SERVER["PHP_SELF"]."?id=$user_id");
                die();
                $mysqli->close();
            }
            else {
                $mysqli = new mysqli(
                    "mysql1.cs.clemson.edu", 
                    "CPSC4620MTb_8b5n", 
                    "cpsc4620-metube", 
                    "CPSC4620-MeTube_uk72"
                );
                $stmt = $mysqli -> prepare("DELETE FROM Subscriptions WHERE User_ID=? AND Sub_ID=?") or die("Error: ".$mysqli->error);
                $stmt -> bind_param('ss',$signed_in_user_id, $user_id);
                $stmt -> execute();
                header("Location: ".$_SERVER["PHP_SELF"]."?id=$user_id");
                die();
                $mysqli->close();
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
    <title>MeTube Development</title>
</head>
<body>
    <?php include("./components/NavBar.php"); ?>
    <h3><?php echo ($user_id) ? $user_id : "Channel not found." ?></h3>
    <?php
        if (!$user_id) {
            echo "</body></html>";
            exit();
        }
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <h4>Uploads</h4>
                <div style='max-height:500px; overflow-y:scroll'>
                    <?php echo ($user_uploads) ? $user_uploads : "No media found."; ?>
                </div>
            </div>
            <?php
                if ($signed_in_user_id) {
                    include("./components/ChannelSidePanel.php");
                }
            ?>
        </div>
    </div>
</body>
</html>