<?php
    session_start();
    unset($_SESSION["user"]);
    header("Location: /ImageUploader/index.php");
    exit;
?>