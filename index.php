<?php
    require("./php/class_autoloader.php");
    session_write_close();
    session_start();
    
    if(isset($_SESSION["loaded_posts"])) unset($_SESSION["loaded_posts"]);
    if(isset($_SESSION["posts"])) unset($_SESSION["posts"]);
    
    require("./phtml/_index.phtml");
?>
