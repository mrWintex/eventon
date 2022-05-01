<?php
    require_once(__DIR__ . "/class_autoloader.php");
    
    if (session_status() === PHP_SESSION_NONE) session_start();

    $postmanager = new PostManager();

    //Načtení příspěvků
    if(isset($_GET["filter"])){
        $postmanager->LoadPosts((isset($_SESSION["user"])? $_SESSION["user"]->GetId() : 0));
    }

    //Like příspěvků
    if(isset($_GET["postid"]) && isset($_SESSION["user"])){
        $postmanager->LikePost($_SESSION["user"]->GetId(), $_GET["postid"]);
    }
?>