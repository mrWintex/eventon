<?php
    require("./php/class_autoloader.php");
    require("./php/login_check.php");
    if(!$_SESSION["user"]->IsAdmin())
        header("location: index.php");
    session_write_close();
    session_start();

    require("./phtml/_admin.phtml");
?>