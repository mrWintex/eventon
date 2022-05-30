<?php
    require("./php/class_autoloader.php");
    session_write_close();
    session_start();
    if(isset($_SESSION["posts"])) unset($_SESSION["posts"]); 
    require("./php/login_check.php");
    require("./phtml/_profile.phtml");
?>