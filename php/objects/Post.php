<?php
    require_once(dirname(__FILE__, 2)."/class_autoloader.php");

    class Post{
        private $id_p;
        private $src;
        private $comment;
        private $add_date;
        private $user_owner;
        private $user_owner_object;

        function __construct($data)
        {
            $this->id_p = $data["id_p"];
            $this->src = $data["src"];
            $this->comment = $data["comment"];
            $this->add_date = $data["add_date"];
            $this->user_owner = $data["user_owner"];

            $this->user_owner_object = $this->GetUserOwnerObject();
        }

        public function RenderPost($logged_user_id, $show_controls=false){
            //Získá počet liků na tomto příspěvku
            $likes_count = Db::GetResult("SELECT COUNT(user) AS like_count FROM posts_likes WHERE post = ?", [$this->id_p])->fetch();
            //Získá tagy k tomouto příspěvku
            $tags = Db::GetAllRows("SELECT * FROM tag_post TP INNER JOIN tags T ON TP.tag = T.id_t WHERE post = " . $this->id_p);

            //Získá datum k zobrazení na stránce
            $date = (date("j.m.Y") == date("j.m.Y", strtotime($this->add_date)))
            ? date("G:i", strtotime($this->add_date))
            : date("j.m.Y", strtotime($this->add_date));
            
            //Získá ikonu vlastníka příspěvku
            $icon_path = $this->user_owner_object->GetUserIcon();

            //Zjistí zda přihlášený uživatel dal like
            $user_liked = count(Db::GetAllRows("SELECT * FROM posts_likes WHERE post = ? AND user = ?", [$this->id_p, $logged_user_id]));
            require(dirname(__DIR__, 2) . "/phtml/PostStructure.phtml");
        }

        public function LikePost($user_id){
            $user_like = Db::GetResult("SELECT * FROM posts_likes WHERE post = ? AND user = ?", [$this->id_p, $user_id])->fetchAll();
    
            $post_like_query = (count($user_like) > 0)
            ? "DELETE FROM posts_likes WHERE post = ? AND user = ?"
            : "INSERT INTO posts_likes (post, user) VALUES (?, ?)";
    
            Db::ExecuteQuery($post_like_query, [$this->id_p, $user_id]);
        }

        public function DeleteSelf(){
            $success = Db::ExecuteQuery("DELETE FROM posts WHERE id_p = ?", [$this->id_p]);
            $post_path = dirname(__FILE__, 3) ."/". $this->GetSrc();
            if($success){
                if(file_exists($post_path)){
                    unlink($post_path);
                }
            }
        }

        private function GetUserOwnerObject(){
            $user_data = Db::GetOneRow("SELECT * FROM users WHERE id_u = ?", [$this->user_owner]);
            return new User($user_data);
        }

        public function GetId(){ return $this->id_p; }
        public function GetSrc(){ return $this->src; }
        public function GetComment(){ return $this->comment; }
        public function GetAddDate(){ return $this->add_date; }
        public function GetUserOwner(){ return $this->user_owner; }
    }
?>