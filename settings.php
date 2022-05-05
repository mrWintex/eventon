<?php
    require("./php/class_autoloader.php");
    session_write_close();
    session_start();
    require("./php/login_check.php");

    $errors_form_1 = [];
    $errors_form_2 = [];

    if(isset($_POST["save_profile"])){
        //Změna uživatelského jména
        if($_POST["new_username"] !== $_SESSION["user"]->GetUserName() && $_POST["new_username"]){
            if(!$_SESSION["user"]->ChangeUserName($_POST["new_username"])) array_push($errors_form_1, "Uživatelské jméno již existuje!");
        }
        //Změna emailu
        if($_POST["user_email"] !== $_SESSION["user"]->GetEmail() && $_POST["user_email"]){
            if(!$_SESSION["user"]->ChangeEmail($_POST["user_email"])) array_push($errors_form_1, "Email je již použit!");
        }
        //Změna profilové ikony
        if($_FILES["icon"]["error"] !== 4){
            //kontrola

            if(count($errors_form_1) == 0){
                $_SESSION["user"]->ChangeUserIcon($_FILES["icon"]);
            }
        }
    }

    //Změna hesla
    if(isset($_POST["change_passwd"])){
        //kontrola
        if(hash("sha256", $_POST["current_passwd"]) !== $_SESSION["user"]->GetPassword()) array_push($errors_form_2, "Heslo není správné!");
        else if($_POST["new_passwd"] !== $_POST["confirm_new_passwd"]) array_push($errors_form_2, "Hesla se neshodují!");
        
        if(count($errors_form_2) == 0){
            $_SESSION["user"]->ChangePassword($_POST["new_passwd"]);
        }
    }
    
    require("./phtml/_settings.phtml");
?>