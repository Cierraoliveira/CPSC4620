<?php 
    include("./components/Session.php");

    $title = $err_title = "";
    $keywords = $err_keywords = "";
    $err_category = "";
    $description = $err_description ="";
    $err_file = "";
    $err_upload = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $date = date('Y-m-d_H-i-s');
        $media_id = uniqid() . $date;
        $target_dir = "./uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir);
        }
        $target_file = basename($_FILES['file']['name']);
        $path = $target_dir . $media_id . $target_file;

        $file_mime_type = $_FILES['file']['type'];
        $media_type = "IMG";
        if (strstr($file_mime_type, "video/")) {
            $media_type = "VID";
        } else if (strstr($file_mime_type, "audio/")) {
            $media_type = "AUD";
        }

        $title = $_POST['title'];
        $description = $_POST['description'];
        $keywords = $_POST['keywords'];
        $keywords = strtolower($keywords);
        $keywords_split = str_replace(' ', '', $keywords);
        $keywords_split = explode(',', $keywords);

        $category = $_POST['category'];

        $views = 0;
        $rating = NULL;

        if(move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
            // connect to mysql
            $mysqli = new mysqli("mysql1.cs.clemson.edu",
                                "CPSC4620MTb_8b5n",
                                "cpsc4620-metube",
                                "CPSC4620-MeTube_uk72");
            // sql statement - media table
            $stmt = $mysqli -> prepare("INSERT INTO Media VALUES(?,?,?,?,?,?,?,?,?,?)") or die("Error: ".$mysqli->error);
            $stmt -> bind_param('ssssssssss',$media_id, $path, $title, $description, $date, $views, $category, $rating, $signed_in_user_id, $media_type);
            $stmt -> execute();
            // sql statement - keywords
            foreach($keywords_split as $kw){
                $stmt = $mysqli -> prepare("INSERT INTO Keywords VALUES(?,?)") or die("Error: ".$mysqli->error);
                $stmt -> bind_param('ss', $kw, $media_id);
                $stmt -> execute();
            };
            // close connection
            $mysqli->close();
            echo $media_type;
            header("Location: "."./userView.php");
            die();
        } else {
            $err_upload = "Error uploading file:" . $_FILES['file']['error'];
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
    <title>Upload</title>
</head>
<body>
    <?php include("./components/NavBar.php"); ?>
    <?php if (!$signed_in_user_id) {exit;} ?>
    <div class="container w-25 p-3 bg-light">
        <h3>Upload</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <!-- title field -->
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" 
                class="form-control" 
                name="title" 
                id="title" 
                value="<?php echo $title;?>"
                required="true">
                <span class="text-danger"><?php echo $err_title; ?></span>
            </div>
            <!-- keywords field -->
            <div class="form-group">
                <label for="title">Keywords:</label>
                <textarea type="text" 
                class="form-control" 
                name="keywords"
                id="keywords" 
                value="<?php echo $keywords;?>"
                required="false"></textarea>
                <span class="text-danger"><?php echo $err_keywords; ?></span>
            </div>
            <!-- categories field -->
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="category" id="music">
                    <label class="form-check-label" for="music">Music</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="category" id="gaming">
                    <label class="form-check-label" for="gaming">Gaming</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="category" id="animals">
                    <label class="form-check-label" for="animals">Animals</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="category" id="sports">
                    <label class="form-check-label" for="sports">Sports</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="category" id="comedy">
                    <label class="form-check-label" for="comedy">Comedy</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="category" id="news">
                    <label class="form-check-label" for="news">News</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="category" id="other" checked>
                    <label class="form-check-label" for="other">Other</label>
                </div>
            </div>
            <!-- description field -->
            <div class="form-group">
                <label for="title">Description:</label>
                <textarea type="text" 
                class="form-control" 
                name="description"
                id="description" 
                value="<?php echo $description;?>"
                required="true"></textarea>
                <span class="text-danger"><?php echo $err_description; ?></span>
            </div>
            <!-- file field -->
            <div class="form-group">
                <label for="file">File:</label>
                <input type="file" 
                class="form-control" 
                name="file"
                id="file" 
                required="true">
                <span class="text-danger"><?php echo $err_file; ?></span>
            </div>
            <label for="submit"><?php echo $err_upload ?></label>
            <button type="submit" class="btn btn-primary" id="submit">Upload</button>
        </form>
    </div>
</body>
</html>