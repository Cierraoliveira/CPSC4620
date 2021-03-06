<?php 
    include("./components/Session.php");
    if (!$signed_in_user_id) {
        die();
    }
    include("./components/MediaListItem.php");
    $err_playlist = "Playlist not found.";
    $playlist = "";

    if ($signed_in_user_id) {
        if (isset($_GET)) {
            if (isset($_GET["id"])) {
                $playlist_id = htmlspecialchars($_GET["id"]);

                $mysqli = new mysqli(
                    "mysql1.cs.clemson.edu", 
                    "CPSC4620MTb_8b5n", 
                    "cpsc4620-metube", 
                    "CPSC4620-MeTube_uk72"
                );

                $stmt = $mysqli->prepare("SELECT Playlist_ID, User_ID, Name FROM Playlists
                WHERE (Playlist_ID=? AND User_ID=?)") or die("Error: ".$mysqli->error);

                $stmt->bind_param("ss", $playlist_id, $signed_in_user_id);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->num_rows != 0) {
                    $err_playlist = "";

                    $row = $res->fetch_assoc();
                    $name = $row["Name"];

                    $stmt = $mysqli->prepare("SELECT Media.Media_ID, Media.Title, Media.User_ID 
                    FROM PlaylistMedia 
                    INNER JOIN Media 
                    ON PlaylistMedia.Media_ID=Media.Media_ID 
                    WHERE Playlist_ID=?") or die("Error: ".$mysqli->error);

                    $stmt->bind_param("s", $playlist_id);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    if ($res->num_rows != 0) {
                        while ($row = $res->fetch_assoc()) {
                            $title = $row["Title"];
                            $uploader = $row["User_ID"];
                            $media_id = $row["Media_ID"];
                            $playlist = $playlist . MediaListItem($signed_in_user_id, $title, $uploader, $media_id, $playlist_id);
                        }
                    } else {
                        $playlist = "No items found.";
                    }
                }

                $mysqli->close();
            }
            
            if (isset($_POST["remove"])) {
                $mysqli = new mysqli(
                    "mysql1.cs.clemson.edu", 
                    "CPSC4620MTb_8b5n", 
                    "cpsc4620-metube", 
                    "CPSC4620-MeTube_uk72"
                );
                $stmt = $mysqli -> prepare("DELETE FROM PlaylistMedia 
                WHERE (Playlist_ID=? AND Media_ID=?)") or die("Error: ".$mysqli->error);
                $stmt -> bind_param('ss',$playlist_id, $_POST["remove"]);
                $stmt -> execute();
                $mysqli->close();
                header("Location: ".$_SERVER["PHP_SELF"]."?id=$playlist_id");
                die();
            }

            if (isset($_POST["editPlaylistName"])) {
                $mysqli = new mysqli(
                    "mysql1.cs.clemson.edu", 
                    "CPSC4620MTb_8b5n", 
                    "cpsc4620-metube", 
                    "CPSC4620-MeTube_uk72"
                );
                $stmt = $mysqli -> prepare("UPDATE Playlists SET Name=? 
                WHERE Playlist_ID=?") or die("Error: ".$mysqli->error);
                $stmt -> bind_param('ss',$_POST["editPlaylistName"], $playlist_id);
                $stmt -> execute();
                $mysqli->close();
                header("Location: ".$_SERVER["PHP_SELF"]."?id=$playlist_id");
                die();
            }

            if (isset($_POST["delete"])) {
                $mysqli = new mysqli(
                    "mysql1.cs.clemson.edu", 
                    "CPSC4620MTb_8b5n", 
                    "cpsc4620-metube", 
                    "CPSC4620-MeTube_uk72"
                );
                $stmt = $mysqli -> prepare("DELETE FROM Playlists WHERE Playlist_ID=?") 
                or die("Error: ".$mysqli->error);
                $stmt -> bind_param('s', $playlist_id);
                $stmt -> execute();
                $mysqli->close();
                header("Location: "."./userView.php");
                die();
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
    <title><?php echo ($err_playlist) ? "Error" : $name ?></title>
    <body>
        <?php include("./components/NavBar.php"); ?>
        <?php if ($err_playlist) {
            echo "$err_playlist</body></head></html>";
            die();
        } ?>
        <div class="p-3">
            <div class="d-flex">
                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF'] . '?id=$playlist_id'); ?>" method="post">
                    <input class="" name='editPlaylistName' type="text" value="<?php echo $name ?>" required=true placeholder="Playlist Name" autocomplete=false/>
                    <button class="btn btn-outline-primary" type="submit">Save Name</button>
                </form>
                <form class="ml-1" action="<?php htmlspecialchars($_SERVER['PHP_SELF'] . '?id=$playlist_id'); ?>" method="post">
                    <button class="btn btn-outline-danger" type="submit" name="delete">Delete Playlist</button>
                </form>
            </div>
            <?php echo $playlist ?>
            <div>
                <a href="./userView.php">Back</a>
            </div>
        </div>
    </body>
</head>
</html>