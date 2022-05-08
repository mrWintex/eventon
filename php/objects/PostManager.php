<?php
    class PostManager{
        private $post_num = 2;
        public $posts_queries;

        public function LoadPosts($logged_user_id, $filter_id){
            $this->SetQueries($filter_id);
            $this->SavePosts();
            for($i = 0; $i < $this->post_num; $i++){
                if(count($_SESSION["posts"]) === 0) return;
                $post_obj = new Post($_SESSION["posts"][$i]);
                $post_obj->CreatePost($logged_user_id);
                unset($_SESSION["posts"][$i]);
            }
            $_SESSION["posts"] = array_values($_SESSION["posts"]);
        }

        public function LikePost($logged_user_id, $post_id){
            $post_data = Db::GetOneRow("SELECT * FROM posts WHERE id_p = ?", [$post_id]);
            $post_object = new Post($post_data);
            $post_object->LikePost($logged_user_id);
        }

        private function SetQueries($filter_id){
            //uložení právě používaného filtru
            if(!isset($_SESSION["filter_id"])){
                $_SESSION["filter_id"] = (int)$filter_id;
            }
            //Vynulování počtu načtených příspěvků pokud byl filter změněn
            else if($_SESSION["filter_id"] != $filter_id){
                $_SESSION["filter_id"] = (int)$filter_id;
                unset($_SESSION["posts"]);
            }
            $this->GenerateQueries();
        }

        private function GenerateQueries(){
            $this->posts_queries = [
                "SELECT * FROM posts ORDER BY add_date desc",
                "SELECT * FROM posts ORDER BY add_date asc",
                "SELECT b.id_p, b.src, b.comment, b.add_date, b.user_owner, count(post) from posts_likes a right join posts b on b.id_p=a.post  GROUP BY b.id_p, b.src, b.comment, b.add_date, b.user_owner ORDER BY COUNT(post) desc",
            ];
        }

        private function SavePosts(){
            if(!isset($_SESSION["posts"])){
                $_SESSION["posts"] = Db::GetAllRows($this->posts_queries[$_SESSION["filter_id"]]);
            }
        }
    }
?>