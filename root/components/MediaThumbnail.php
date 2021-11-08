<?php 
    function MediaThumbnail($media_id, $path, $title, $description) {
        $query = array(
            "id" => $media_id
        );
        $link = "./mediaView.php?" . http_build_query($query);
        return "
            <div class=''>
                <div class='d-flex'>
                    <a href=$link>
                        <img class='' style='width:160px;height:160px;object-fit:cover' src=$path alt=$description>
                    </a>
                    <div class='p-2'>
                        <h4>$title</h4>
                        <p>$description</p>
                    </div>
                </div>
            </div>
        ";
    }
?>