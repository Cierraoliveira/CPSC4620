<?php 
    function MediaThumbnail($media_id, $path, $title, $description, $media_type) {
        $query = array(
            "id" => $media_id
        );
        $link = "./mediaView.php?" . http_build_query($query);
        if ($media_type == "VID") {
            $path = "./assets/video.svg";
        }
        return "
            <div class=''>
                <div class='d-flex'>
                    <a href=$link>
                        <img class='' style='width:100px;height:100px;object-fit:cover' src=$path alt=$description>
                    </a>
                    <div class='p-2'>
                        <h6 class='font-weight-bold'>$title</h6>
                        <p>$description</p>
                    </div>
                </div>
            </div>
        ";
    }
?>