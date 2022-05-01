<?php 
    session_start();
    require("./php/class_autoloader.php");
    
    $user_manager = new UserManager();

    if (isset($_POST["reg_user"])) {
        $user_manager->Register($_POST["username"], $_POST["email"], $_POST["password_1"], $_POST["password_2"]);
    }
    require("./phtml/_registration.phtml");
?>
