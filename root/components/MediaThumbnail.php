<?php 
    function MediaThumbnail($media_id, $path, $title, $description, $media_type, $display_user="") {
        $query = array(
            "id" => $media_id
        );
        $link = "./mediaView.php?" . http_build_query($query);
        if ($media_type == "VID") {
            $path = "./assets/video.svg";
        }
        $render = "";
        if ($display_user) {
            $query = array(
                "id" => $display_user
            );
            $linkChannel = "./channelView.php?" . http_build_query($query);
            $render = "&middot; <a class='d-inline' style='color:black !important;' href=$linkChannel>$display_user</a>";
        }
        return "
            <div class='border-bottom'>
                <div class='d-flex'>
                    <a href=$link>
                        <img style='width:100px;height:100px;object-fit:cover;' src=$path alt=$description>
                    </a>
                    <div class='p-2'>
                        <div class='d-inline'>
                            <h6 class='d-inline font-weight-bold'>$title</h6>
                            $render
                        </div>
                        <p>$description</p>
                    </div>
                </div>
            </div>
        ";
    }
?>