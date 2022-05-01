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
            if(!Db::Exists("users", "username", $_POST["new_username"])){
                Db::ChangeData("users", "username", $_POST["new_username"], "id_u", $_SESSION["user"]->GetId());
                $_SESSION["user"]->ChangeUserName($_POST["new_username"]);
            }
            else{
                array_push($errors_form_1, "Uživatelské jméno již existuje!");
            }
        }
        //Změna emailu
        if($_POST["user_email"] !== $_SESSION["user"]->GetEmail() && $_POST["user_email"]){
            if(!Db::Exists("users", "email", $_POST["user_email"])){
                Db::ChangeData("users", "email", $_POST["user_email"], "id_u", $_SESSION["user"]->GetId());
                $_SESSION["user"]->ChangeEmail($_POST["user_email"]);
            }
            else{
                array_push($errors_form_1, "Email je již použit!");
            }
        }
        //Změna profilové ikony
        if(isset($_FILES["icon"])){
            if(!in_array($_FILES["icon"]["error"], [0, 4])) array_push($errors_form_1, "Chyba při uploadu ikony!");

            if(count($errors_form_1) == 0 && $_FILES["icon"]["error"] !== 4){
                $icon_folder = $_SESSION["user"]->GetUserFolderPath()."/icon";
                if(!file_exists($icon_folder)){
                    mkdir($icon_folder);
                }
                $files = glob($icon_folder.'/*'); 
                foreach($files as $file) {
                    if(is_file($file)){
                        unlink($file); 
                    }
                }
                move_uploaded_file($_FILES["icon"]["tmp_name"], $icon_folder . "/" . $_FILES["icon"]["name"]);
            }
        }
    }

    //Změna hesla
    if(isset($_POST["change_passwd"])){
        if(hash("sha256", $_POST["current_passwd"]) !== $_SESSION["user"]->GetPassword()) array_push($errors_form_2, "Heslo není správné!");
        else if($_POST["new_passwd"] !== $_POST["confirm_new_passwd"]) array_push($errors_form_2, "Hesla se neshodují!");
        

        if(count($errors_form_2) == 0){
            Db::ChangeData("users", "password", hash("sha256", $_POST["new_passwd"]), "id_u", $_SESSION["user"]->GetId());
            $_SESSION["user"]->ChangePassword(hash("sha256", $_POST["new_passwd"]));
        }
    }

    function PrintErrors($errors){
        if (count($errors) > 0) {
            echo ("<p class='error'>");
                echo ("{$errors[0]}");
            echo ("</p>");
        }
    }

    require("./phtml/_settings.phtml");
?>