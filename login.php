<?php 
    session_start();
    require("./php/db_connect.php");
    require("./php/functions/users_functions.php");

    $errors = [];

    //Proces Přihlášení
    if (isset($_POST["log_user"])) {
        //načtení a upravení záznamů do databáze
        $email = mysqli_real_escape_string($database, $_POST["email"]);
        $password = mysqli_real_escape_string($database, $_POST["password"]);

        //kontrola správného vyplnění
        if (empty($email)) array_push($errors, "Nebyl zadán email!");
        if (empty($password)) array_push($errors, "Nebylo zadáno heslo!");

        //Pokud vše proběhlo správně, uživatel se přihlásí
        if (count($errors) == 0) {
            Login($database, $email, $password);
        }
    }
    require("./phtml/_login.phtml"); 
?>