<?php
    session_start();
    require("./php/login_check.php");
    if($_SESSION["user"]["admin"] == 0)
        header("location: index.php");

    require("./phtml/_admin.phtml");
?>