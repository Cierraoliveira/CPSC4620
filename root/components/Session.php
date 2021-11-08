<?php
    session_start();
    $user_id = "";
    if (isset($_SESSION["session_user_id"])) {
        $user_id = $_SESSION["session_user_id"];
    }
?>