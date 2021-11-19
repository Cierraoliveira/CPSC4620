<?php
    function MediaListItem($signed_in_user, $title, $uploader, $media_id) {
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
        return "
            <p id=$media_id>
                <a class='font-weight-bold font-underline' style='color:black !important' href='$linkMedia'><u>$title</u></a> uploaded by
                $upload_element
            </p>
        ";
    }
?>