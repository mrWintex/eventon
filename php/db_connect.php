<?php
    //Připojení do databáze
    $database = mysqli_connect("localhost", "root", "", "eventon_database");
    
    //Nastavení kódování
    mb_internal_encoding("utf-8");
?>