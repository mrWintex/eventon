<?php
    session_start();
    require("./php/db_connect.php");
    require("./php/functions/users_functions.php");
    require("./php/login_check.php");

    $supportedFileTypes = [
        "image/jpeg",
        "image/png",
        "image/gif"
    ];
    $errors = [];

    //Proces přidání příspěvku
    if (isset($_POST["addpost"])) {
        //Kontrola požadavků pro nahrátí na server
        if (!in_array($_FILES["image"]["type"], $supportedFileTypes)) array_push($errors, "Nepodporovaný typ souboru!");
        if (file_exists($_SESSION["user"]["folder_path"] . "/" . $_FILES["image"]["name"])) array_push($errors, "Tento soubor byl již nahrán!");
        if ($_FILES["image"]["error"]) array_push($errors, "Kód chyby: {$_FILES['image']['error']}");

        if (count($errors) == 0) {
            AddPost($database, $_SESSION["user"], $_FILES["image"], $_POST);
        }
    }

    require("./phtml/_addpost.phtml");
?>