<?php 
    session_start();
    require("./php/class_autoloader.php");
    //----
    $user_manager = new UserManager();

    if (isset($_POST["log_user"])) {
        $user_manager->Login($_POST["email"], $_POST["password"]);
    }
    require("./phtml/_login.phtml"); 
?>