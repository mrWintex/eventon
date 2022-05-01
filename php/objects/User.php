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

        public function GetUserFolderPath(){
            return UserManager::USERS_FOLDER . $this->id_u;
        }

        public function IsAdmin(){
            return ($this->admin)? true : false;
        }

        public function GetId(){ return $this->id_u; }
        public function GetUserName(){ return $this->username; }
        public function GetEmail(){ return $this->email; }
        public function GetRegistrationDate(){ return $this->reg_date; }
        public function GetPassword(){ return $this->password; }

        public function ChangeUserName($new){ $this->username = $new; }
        public function ChangeEmail($new){ $this->email = $new; }
        public function ChangePassword($new){ $this->password = $new; }
    }
?>