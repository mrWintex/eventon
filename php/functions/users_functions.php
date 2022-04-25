<?php 
    function Login($database, $email, $password){
            $user_check_query = " SELECT * FROM users WHERE email = '$email' AND password = '". hash("sha256",$password)."'";
            $user_check_qresult = mysqli_query($database, $user_check_query);
            $user = mysqli_fetch_assoc($user_check_qresult);
            if ($user) {
                $_SESSION["user"] = $user;
                header("location: index.php");
            }
    }

    function Logout(){
        if(isset($_SESSION["user"])){
            session_destroy();
            unset($_SESSION["user"]);
            header("location: index.php");
        }
    }

    function Register($database, $password, $username, $email, $users_folder){
        //Vytvoření složky
        $current_user_folder = $users_folder . "/" . $username;
        if(!file_exists($current_user_folder))
            mkdir($current_user_folder);
        //Přidání záznamu do databáze
        $register_query = "INSERT INTO users (username, email, password, folder_path) VALUES ('$username', '$email', '".hash("sha256", $password)."', '$current_user_folder')";
        mysqli_query($database, $register_query);
        
        //Získání právě vytvořeného uživatele
        $user = GetArrayFromQuery($database, "SELECT * FROM users WHERE username = '$username' LIMIT 1");
        
        //Uložení uživatelského jména do paměti stránky
        $_SESSION["user"] = $user[0];

        //Přesměrování na hlavní stránku
        header("location: index.php");
    }

    function AddPost($database, $user_data, $file_data, $post_data){
        $directory = $user_data["folder_path"] . "/" . $file_data["name"];
        $addpost_query = "INSERT INTO posts (src, comment, user_owner) VALUES ('$directory', '{$post_data["comment"]}', '{$user_data["id_u"]}')";
        $query_result = mysqli_query($database, $addpost_query);
        if ($query_result) {
            move_uploaded_file($file_data["tmp_name"], $directory);
            header("location: index.php");
        }
    }
?>