<?php
    session_start();
    $signed_in_user_id = "";
    if (isset($_SESSION["session_user_id"])) {
        $signed_in_user_id = $_SESSION["session_user_id"];
    }
?>