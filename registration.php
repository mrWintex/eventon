<?php 
    session_start();
    require("./php/db_connect.php");
    require("./php/functions/database_functions.php");
    require("./php/functions/users_functions.php");

    $min_password_length = 8;
    $max_username_length = 18;

    $users_folder = "users";
    $errors = [];
    
    if(!file_exists($users_folder))
        mkdir($users_folder);

    //Proces Registrace
    if (isset($_POST["reg_user"])) {
        //Uložení proměnných a připravení pro uložení do databáze
        $username = mysqli_real_escape_string($database, $_POST["username"]);
        $email = mysqli_real_escape_string($database, $_POST["email"]);
        $password_1 = mysqli_real_escape_string($database, $_POST["password_1"]);
        $password_2 = mysqli_real_escape_string($database, $_POST["password_2"]);

        //Kontrola vstupů, zda splňují všechny požadavky
        if (empty($username)) array_push($errors, "Nebylo zadáno uživatelské jméno!");
        if (empty($email)) array_push($errors, "Nebyl zadán email!");
        if (empty($password_1)) array_push($errors, "Nebylo zadáno heslo!");
        if(mb_strlen($username) > $max_username_length) array_push($errors, "Uživatelské jméno je příliš dlouhé! (max: {$max_username_length})");
        if ($password_1 !== $password_2) array_push($errors, "Hesla se neshodují!");
        if (mb_strlen($password_1) < $min_password_length) array_push($errors, "Heslo je příliš krátké! (min: {$min_password_length})");

        //Kontrola zda takový uživatel již není v databázi
        if (Exists($database, "users", "username", $username)) array_push($errors, "Uživatelské jméno už existuje!");
        if (Exists($database, "users", "email", $email)) array_push($errors, "Email je již použitý!");

        //Pokud vše proběhlo bez chyby, proběhne proces ukládání do databáze
        if (count($errors) == 0) {
            Register($database, $password_1, $username, $email, $users_folder);
        }
    }
    require("./phtml/_registration.phtml");
?>
