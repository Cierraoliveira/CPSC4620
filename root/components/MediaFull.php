<?php 
    function MediaFull($path, $title, $description, $views, $user_id) {
        return "
            <div class='container'>
                <div class='d-flex justify-content-center'>
                    <div class='border' style='width:640px;height:480px;text-align:center;line-height:480px'>
                        <img class='' style='max-width:640px;max-height:480px;vertical-align:middle' src=$path alt=$description>
                    </div>
                </div>
                <div class='p-3'>
                    <div class='border-bottom mb-2'>
                        <h4>$title</h4>
                        <div class='d-flex align-items-center mb-2'>
                            <p class='my-auto font-weight-light'>Views: $views</p>
                            <button class='ml-auto btn btn-outline-secondary'>Download</button>
                        </div>
                    </div>
                    <p class='font-weight-bold'>$user_id</p>
                    <p>$description</p>
                </div>
            </div>
        ";
    }
?>