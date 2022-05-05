<?php
    require_once(__DIR__ . "/class_autoloader.php");
    
    if (session_status() === PHP_SESSION_NONE) session_start();

    $postmanager = new PostManager();

    //Načtení příspěvků
    if(isset($_GET["filter"])){
        $logged_user_id = (isset($_SESSION["user"]))? $_SESSION["user"]->GetId() : 0;
        $postmanager->LoadPosts($logged_user_id, $_GET["filter"]);
    }

    //Like příspěvků
    if(isset($_GET["postid"]) && isset($_SESSION["user"])){
        $postmanager->LikePost($_SESSION["user"]->GetId(), $_GET["postid"]);
    }
?>