<?php
    class UserManager{
        public const MAX_USERNAME_LENGTH = 18;
        public const MIN_PASSWORD_LENGTH = 6;
        public const USERS_FOLDER = "users/";
        public const SUPPORTED_FILES = [ "image/jpeg", "image/png", "image/gif" ];
        public const MAX_FILE_SIZE = 5 * 1024 * 1024;
        public const MAX_ICON_SIZE = 1 * 1024 * 1024;
        public const MAX_FILE_NAME_SIZE = 20;
        private $errors = [];

        function __construct(){
            if(!file_exists(self::USERS_FOLDER))
                mkdir(self::USERS_FOLDER);
        }

        
        function Register($username, $email, $password_1, $password_2){
            if(!$this->ValidateRegistration($username, $email, $password_1, $password_2)) return;
            
            //Přidání záznamu do databáze
            Db::ExecuteQuery("INSERT INTO users (username, email, password) VALUES (?, ?, ?)", [$username, $email, hash("sha256", $password_1)]);
            
            //Získání právě vytvořeného uživatele
            $user = Db::GetOneRow("SELECT * FROM users WHERE username = ? LIMIT 1", [$username]);
            
            //Vytvoření složky
            $current_user_folder = self::USERS_FOLDER . "/" . $user["id_u"];
            if(!file_exists($current_user_folder))
            mkdir($current_user_folder);
            
            //Uložení uživatelského jména do paměti stránky
            $_SESSION["user"] = new User($user);
            
            //Přesměrování na hlavní stránku
            header("location: index.php");
            exit;
        }
        
        
        function Login($email, $password){
            if(!$this->ValidateLogin($email, $password)) return;
            
            $user = Db::GetOneRow("SELECT * FROM users WHERE email = ? AND password = ?", [$email, hash("sha256",$password)]);
            if ($user) {
                $_SESSION["user"] = new User($user);
                header("location: index.php");
                exit;
            }
            else{
                array_push($this->errors, "Uživatelské jméno nebo heslo není správné!");
            }
        }
        
        
        function AddPost($user, $file, $post_data){
            if(!$this->ValidateFile($file)) return;
            $directory = $user->GetUserFolderPath() . "/" . $this->ShortFile($file["name"]);
            if (Db::ExecuteQuery("INSERT INTO posts (src, comment, user_owner) VALUES (?, ?, ?)", [$directory, htmlspecialchars($post_data["comment"]), $user->GetId()])) {
                $this->AddTags($post_data, $directory);
                move_uploaded_file($file["tmp_name"], $directory);
                header("location: index.php");
                exit;
            }
        }

        function AddTags($post_data, $directory){
            if(!isset($post_data["selected-tag"])) return;

            $post_query = "SELECT * FROM posts WHERE src = '$directory'";
            echo($post_query);
            $post = new Post(Db::GetOneRow($post_query));
            foreach($post_data["selected-tag"] as $tag){
                echo("INSERT INTO tag_post (post, tag) VALUES ({$post->GetId()},$tag)");
                Db::ExecuteQuery("INSERT INTO tag_post (post, tag) VALUES (?,?)", [$post->GetId(), $tag]);
            }
        }
        
        // === VALIDATION FUNCTIONS === 
        private function ValidateRegistration($username, $email, $password_1, $password_2){
            //Kontrola vstupů, zda splňují všechny požadavky
            if (empty($username)) array_push($this->errors, "Nebylo zadáno uživatelské jméno!");
            if (empty($email)) array_push($this->errors, "Nebyl zadán email!");
            if (empty($password_1)) array_push($this->errors, "Nebylo zadáno heslo!");
            if(mb_strlen($username) > self::MAX_USERNAME_LENGTH) array_push($this->errors, "Uživatelské jméno je příliš dlouhé! (max: ".self::MAX_USERNAME_LENGTH.")");
            if ($password_1 !== $password_2) array_push($this->errors, "Hesla se neshodují!");
            if (mb_strlen($password_1) < self::MIN_PASSWORD_LENGTH) array_push($this->errors, "Heslo je příliš krátké! (min: ".SELF::MIN_PASSWORD_LENGTH.")");
    
            //Kontrola zda takový uživatel již není v databázi
            if (Db::Exists("users","username", $username)) array_push($this->errors, "Uživatelské jméno už existuje!");
            if (Db::Exists("users","email", $email)) array_push($this->errors, "Email je již použitý!");
    
            return (count($this->errors) == 0)? true : false;
        }

        private function ValidateLogin($email, $password){
            //kontrola správného vyplnění
            if (empty($email)) array_push($this->errors, "Nebyl zadán email!");
            if (empty($password)) array_push($this->errors, "Nebylo zadáno heslo!");

            return (count($this->errors) == 0)? true : false;
        }
        
        private function ValidateFile($file){
            $verifyimg = getimagesize($file['tmp_name']);
            //Kontrola požadavků pro nahrátí na server
            if (!in_array($verifyimg['mime'], self::SUPPORTED_FILES)) array_push($this->errors, "Nepodporovaný typ souboru!");
            if (file_exists($_SESSION["user"]->GetUserFolderPath() . "/" . $this->ShortFile($file["name"]))) array_push($this->errors, "Tento soubor byl již nahrán!");
            if ($file["error"]) array_push($this->errors, "Kód chyby: {$file['error']}");
            if ($file["size"] > self:: MAX_FILE_SIZE) array_push($this->errors, "Soubor je příliš velký!");
            return (count($this->errors) == 0)? true : false;
        }
        // ======

        function PrintErrors(){
            if(count($this->errors) > 0){
                echo($this->errors[0]);
            }
        }
        function ShortFile($file_name){
            return $file_name;
        }
        function GetErrorCount() { return count($this->errors); }
    }
?>