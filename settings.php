<?php
    session_start();
    require("./php/db_connect.php");
    require("./php/login_check.php");
    require("./php/functions/users_functions.php");
    require("./php/functions/database_functions.php");
    require("./php/functions/icon_functions.php");

    $errors = [];
    $form = 0;
    if(isset($_POST["save_profile"])){
        $form = 1;
        if($_POST["new_username"] !== $_SESSION["user"]["username"] && $_POST["new_username"]){
            if(!Exists($database, "users", "username", $_POST["new_username"])){
                ChangeData($database, "users", "username", $_POST["new_username"], "id_u", $_SESSION["user"]["id_u"]);
                $_SESSION["user"]["username"] = $_POST["new_username"];
            }
            else{
                array_push($errors, "Uživatelské jméno již existuje!");
            }
        }
        if($_POST["user_email"] !== $_SESSION["user"]["email"] && $_POST["user_email"]){
            if(!Exists($database, "users", "email", $_POST["user_email"])){
                ChangeData($database, "users", "email", $_POST["user_email"], "id_u", $_SESSION["user"]["id_u"]);
                $_SESSION["user"]["email"] = $_POST["user_email"];
            }
            else{
                array_push($errors, "Email je již použit!");
            }
        }
        if(isset($_FILES["icon"])){
            $icon_folder = $_SESSION["user"]["folder_path"]."/icon";
            if(!file_exists($icon_folder)){
                mkdir($icon_folder);
            }

            if(!in_array($_FILES["icon"]["error"], [0, 4])) array_push($errors, "Chyba při uploadu ikony!");
            
            if(count($errors) == 0 && $_FILES["icon"]["error"] !== 4){
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

    if(isset($_POST["change_passwd"])){
        $form = 2;
        if(hash("sha256", $_POST["current_passwd"]) !== $_SESSION["user"]["password"]) array_push($errors, "Heslo není správné!");
        else if($_POST["new_passwd"] !== $_POST["confirm_new_passwd"]) array_push($errors, "Hesla se neshodují!");

        if(count($errors) == 0){
            ChangeData($database, "users", "password", hash("sha256", $_POST["new_passwd"]), "id_u", $_SESSION["user"]["id_u"]);
            $_SESSION["user"]["password"] = hash("sha256", $_POST["new_passwd"]);
        }
    }

    require("./phtml/_settings.phtml");
?>