<?php
    require_once(__DIR__ . "/db_connect.php");
    require_once(__DIR__ . "/functions/post_functions.php");
    
    if (session_status() === PHP_SESSION_NONE) session_start();

    if(isset($_GET["query"])){
        LoadPosts($database, $_GET["query"],(isset($_SESSION["user"])? $_SESSION["user"]["id_u"] : 0));
    }

    if(isset($_GET["postid"]) && isset($_SESSION["user"]))
        LikePost($_GET["postid"], $_SESSION["user"]["id_u"], $database);
?>