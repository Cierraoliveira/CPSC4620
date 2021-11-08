<?php 
    function MediaThumbnail($path, $title, $description) {
        return "
            <div class=''>
                <div class='d-flex'>
                    <img class='' style='width:160px;height:160px;object-fit:cover' src=$path alt=$description>
                    <div class='p-2'>
                        <h4>$title</h4>
                        <p>$description</p>
                    </div>
                </div>
            </div>
        ";
    }
?>