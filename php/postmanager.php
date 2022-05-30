<?php
    require_once(__DIR__ . "/class_autoloader.php");
    
    if (session_status() === PHP_SESSION_NONE) session_start();

    $postmanager = new PostManager();

    $logged_user_id = (isset($_SESSION["user"]))? $_SESSION["user"]->GetId() : 0;

    //Načtení příspěvků
    if(isset($_POST["filter"])){
        $postmanager->LoadPosts($logged_user_id, $_POST["filter"], $_POST["search_value"], $_POST["search_data"], $_POST["showcontrols"]);
    }

    //Like příspěvků
    if(isset($_GET["postid"]) && isset($_SESSION["user"])){
        $postmanager->LikePost($_SESSION["user"]->GetId(), $_GET["postid"]);
    }
?>