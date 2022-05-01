<?php
    require("./php/class_autoloader.php");
    require("./php/login_check.php");
    session_write_close();
    session_start();

    $user_manager = new UserManager();

    //Proces přidání příspěvku
    if (isset($_POST["addpost"])) {
        $user_manager->AddPost($_SESSION["user"], $_FILES["image"], $_POST);
    }

    require("./phtml/_addpost.phtml");
?>