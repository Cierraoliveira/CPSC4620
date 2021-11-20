<?php
    function MediaListItem($signed_in_user, $title, $uploader, $media_id, $playlist_id) {
        // channel link
        $linkChannel = "";
        if ($signed_in_user != $uploader) {
            $query = array(
                "id" => $uploader
            );
            $linkChannel = "./channelView.php?" . http_build_query($query);
            $upload_element = "<a style='color:black !important' href='$linkChannel'><u>$uploader</u></a>";
        } else {
            $upload_element = "you";
        }
        // media link
        $query = array(
            "id" => $media_id
        );
        $linkMedia = "./mediaView.php?" . http_build_query($query);

        $action = htmlspecialchars($_SERVER["PHP_SELF"] . "?id=$playlist_id");

        return "
            <div class='d-flex align-items-center'>
                <div class='align-middle'>
                    <a class='font-weight-bold font-underline' style='color:black !important' href='$linkMedia'><u>$title</u></a> uploaded by
                    $upload_element
                </div>
                <form action='$action' method='post'>
                    <button class='btn btn-link' type='submit' name='remove' value='$media_id'>Remove</button>
                </form>
            </div>
        ";
    }
?>