<?php 
    include("./components/Session.php");
    include("./components/MediaFull.php");

    $media_id = "";
    $err_media = "";
    $media_comments = "";
    $num_comments = 0;
    $is_favorite = false;

    // rewriting logic for recursing comments
    function fetch_comments(&$client, $r_media_id, $r_reply_id, &$set, $layer=0) {
        $return_html = "";

        $stmt = $client->prepare("SELECT User_ID, Comment, Comment_ID 
        FROM Comments 
        WHERE (Media_ID=? AND Reply_ID=?)");
        $stmt->bind_param("ss", $r_media_id, $r_reply_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows != 0) {
            while ($row = $res->fetch_assoc()) {
                $c_user_id = $row["User_ID"];
                $c_comment = $row["Comment"];
                $c_comment_id = $row["Comment_ID"];
                if (!in_array($c_comment_id, $set)) {
                    $return_html = $return_html . render_comment($layer, $c_user_id, $c_comment, $c_comment_id);
                    array_push($set, $c_comment_id);
                    $return_html = $return_html . fetch_comments($client, $r_media_id, $c_comment_id, $set, $layer+1);
                }
            }
        }
        return $return_html;
    }

    function render_comment($layer, $c_user_id, $c_comment, $c_comment_id) {
        return "
            <li class='list-group-item' style='margin-left:calc($layer*20px)'>
                <div class='d-flex'>
                    <p class='font-weight-bold m-0'>$c_user_id <span class='font-weight-light'>($c_comment_id)</span></p>
                    <a class='ml-auto' id='$c_comment_id' href=''>Reply</a>
                </div>
                <p>$c_comment</p>
            </li>
        ";
    }

    if (isset($_GET)) {
        if (isset($_GET["id"])) {
            $media_id = htmlspecialchars($_GET["id"]);

            $mysqli = new mysqli(
                "mysql1.cs.clemson.edu", 
                "CPSC4620MTb_8b5n", 
                "cpsc4620-metube", 
                "CPSC4620-MeTube_uk72"
            );

            $stmt = $mysqli->prepare("SELECT Path, Title, Description, Views, User_ID, Media_Type FROM Media WHERE Media_ID=?") 
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
                $views = $row["Views"] + 1;
                $user_id = $row["User_ID"];
                $media_type = $row["Media_Type"];
                
                // don't count duplicate views when posting a comment
                if (!isset($_POST["comment"])) {
                    $stmt = $mysqli->prepare("UPDATE Media SET Views=? WHERE Media_ID=?")
                    or die("Error: ".$mysqli->error);
                    $stmt->bind_param("is", $views, $media_id);
                    $stmt->execute();
                }

                // fetch comments
                $comment_set = array();
                $media_comments = fetch_comments($mysqli, $media_id, "", $comment_set);
                $num_comments = count($comment_set);
            }

            // fetch favorites
            $stmt = $mysqli->prepare("SELECT * FROM Favorites WHERE (User_ID=? AND Media_ID=?)") 
                or die("Error: ".$mysqli->error);
            $stmt->bind_param("ss", $signed_in_user_id, $media_id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows != 0) {
                $is_favorite = true;
            }
            
            $mysqli->close();
        }
        if (isset($_POST["comment"])) {
            $comment = htmlspecialchars($_POST["comment"]);
            $comment_id = uniqid();
            $reply_id = NULL;
            if (isset($_POST["target"])) {
                $reply_id = $_POST["target"];
            }

            $mysqli = new mysqli(
                "mysql1.cs.clemson.edu", 
                "CPSC4620MTb_8b5n", 
                "cpsc4620-metube", 
                "CPSC4620-MeTube_uk72"
            );

            $stmt = $mysqli->prepare("INSERT INTO Comments (Media_ID, User_ID, Comment, Comment_ID, Reply_ID) VALUES (?, ?, ?, ?, ?)") or die("Error: ".$mysqli->error);
            $stmt->bind_param("sssss", $media_id, $signed_in_user_id, $comment, $comment_id, $reply_id);
            $stmt->execute();
            $mysqli->close();
            header("Location: ".$_SERVER["PHP_SELF"]."?id=$media_id");
            die();
        }
        if (isset($_POST["favorite"])) {
            $mysqli = new mysqli(
                "mysql1.cs.clemson.edu", 
                "CPSC4620MTb_8b5n", 
                "cpsc4620-metube", 
                "CPSC4620-MeTube_uk72"
            );
            $stmt = $mysqli -> prepare("INSERT INTO Favorites VALUES(?,?)") or die("Error: ".$mysqli->error);
            $stmt -> bind_param('ss',$signed_in_user_id, $media_id);
            $stmt -> execute();
            $mysqli->close();
            header("Location: ".$_SERVER["PHP_SELF"]."?id=$media_id");
            die();
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
    <?php echo ($err_media) ? $err_media : MediaFull($path, $title, $description, $views, $user_id, $media_type, $media_id, $is_favorite) ?>

    <div class="container">
        <div class="container">
            <h5><span class="font-weight-bold"><?php echo $num_comments; ?></span> Comments</h5>
            <ul id="commentsList" class="list-group mb-3" style="max-height:400px;overflow-y:scroll"><?php echo $media_comments; ?></ul>
            <?php if ($signed_in_user_id) {include("./components/PostComment.php");} ?>
        </div>
    </div>
    </body>
</html>

<?php echo "
<script type='text/javascript'>
    const commentsSection = document.getElementById('commentsList');
    const replyLinks = commentsSection.getElementsByTagName('a');
    Array.from(replyLinks).forEach(link => link.addEventListener('click', function(event) {
        let subject = document.getElementById('commentTarget').value;
        if (subject === link.id) {
            document.getElementById('commentTextArea').placeholder = 'Leave a comment.';
            document.getElementById('commentTarget').value = '';
        } else {
            document.getElementById('commentTextArea').placeholder = 'Replying to comment ' + link.id + '.';
            document.getElementById('commentTarget').value = link.id;
        }
        event.preventDefault();
    }));
</script>
    ";
?>