<?php 
    function MediaFull($path, $title, $description) {
        return "
            <div class='container'>
                <div class='d-flex justify-content-center'>
                    <div class='border' style='width:640px;height:480px;text-align:center;line-height:480px'>
                        <img class='' style='max-width:640px;max-height:480px;vertical-align:middle' src=$path alt=$description>
                    </div>
                </div>
                <div class='p-3'>
                    <h4>$title</h4>
                    <p>$description</p>
                </div>
            </div>
        ";
    }
?>