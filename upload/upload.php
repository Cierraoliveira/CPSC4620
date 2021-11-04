<?php

$date = date('Y-m-d_H-i-s');
$media_id = uniqid() . $date;

// TO DO: get userid to add to directory 
$target_dir = "uploads/";
// TO DO: validate file type?
$target_file = basename($_FILES['file']['name']);
$path = $target_dir . $media_id . $target_file;

// TO DO: validate title
$title = $_POST['title'];
$description = $_POST['description'];

// TO DO: clean keywords
// TO DO: validate comma separated
$keywords = $_POST['keywords'];
$keywords_split = explode(',', $keywords);

$category = $_POST['category'];
// TO DO: get user id
$user_id = 'user1'; 
$views = 0;
$rating = NULL;

// try to upload file
if(move_uploaded_file($_FILES['file']['tmp_name'], $path)){

    //connect to mysql
    $mysqli = new mysqli("mysql1.cs.clemson.edu",
                        "CPSC4620MTb_8b5n",
                        "cpsc4620-metube",
                        "CPSC4620-MeTube_uk72");

    // sql statement - media table
    $stmt = $mysqli -> prepare("INSERT INTO Media VALUES(?,?,?,?,?,?,?,?,?)") or die("Error: ".$mysqli->error);
    $stmt -> bind_param('sssssssss',$media_id, $path, $title, $description, $date, $views, $category, $rating, $user_id);
    $stmt -> execute();

    // sql statement - keywords
    foreach($keywords_split as $kw){
        $stmt = $mysqli -> prepare("INSERT INTO Keywords VALUES(?,?)") or die("Error: ".$mysqli->error);
        $stmt -> bind_param('ss', $kw, $media_id);
        $stmt -> execute();
    };

    //close connection
    $mysqli->close();

    echo "File Uploaded";
}
else{
    echo "Error";
}

?>
