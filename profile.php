<?php
    require("./php/class_autoloader.php");
    session_write_close();
    session_start();
    require("./php/login_check.php");
    require("./phtml/_profile.phtml");
?>