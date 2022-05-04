<?php
    class User{
        private $id_u;
        private $username;
        private $email;
        private $password;
        private $reg_date;
        private $admin;

        function __construct($data)
        {
            $this->id_u = $data["id_u"];
            $this->username = $data["username"];
            $this->email = $data["email"];
            $this->password = $data["password"];
            $this->reg_date = $data["reg_date"];
            $this->admin = $data["admin"];
        }

        public function GetUserIcon(){
            $user_folder_path = $this->GetUserFolderPath();
            $icon_folder = dirname(__FILE__, 3)."/". $user_folder_path . "/icon";
            $file_path = "";
            if(file_exists($icon_folder)){
                $dir = opendir($icon_folder);
                while(false !== ($file = readdir($dir))){
                    if(!in_array($file, [".", ".."])){
                        $file_path = $user_folder_path . "/icon/" . $file;
                        break;
                    }
                }
            }
            else
                $file_path = "./images/icon.jpg";
            return $file_path;
        }

        public function ChangeUserIcon($icon_data){
            $icon_folder = $this->GetUserFolderPath()."/icon";
            if(!file_exists($icon_folder)){
                mkdir($icon_folder);
            }
            $files = glob($icon_folder.'/*'); 
            foreach($files as $file) {
                if(is_file($file)){
                    unlink($file); 
                }
            }
            move_uploaded_file($icon_data["tmp_name"], $icon_folder . "/" . $icon_data["name"]);
        }

        public function GetUserFolderPath(){
            return UserManager::USERS_FOLDER . $this->id_u;
        }

        //gettery
        public function GetId(){ return $this->id_u; }
        public function GetUserName(){ return $this->username; }
        public function GetEmail(){ return $this->email; }
        public function GetRegistrationDate(){ return $this->reg_date; }
        public function GetPassword(){ return $this->password; }
        public function IsAdmin(){ return ($this->admin)? true : false; }

        //settery
        public function ChangeUserName($new){ 
            if(Db::Exists("users", "username", $new)) return false;
            Db::ChangeData("users", "username", $new, "id_u", $this->id_u);
            $this->username = $new; 
            return true;
        }
        public function ChangeEmail($new){ 
            if(Db::Exists("users", "email", $new)) return false;
            Db::ChangeData("users", "email", $new, "id_u", $this->id_u);
            $this->email = $new; 
            return true;
        }
        public function ChangePassword($new){ 
            Db::ChangeData("users", "password", hash("sha256", $new), "id_u", $this->id_u);
            $this->password = $new; 
        }
    }
?>