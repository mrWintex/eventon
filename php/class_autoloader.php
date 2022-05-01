<?php
    function LoadClass($class){
        require(__DIR__ . "/objects/$class.php");
    }
    spl_autoload_register("LoadClass");
?>