<?php
    class PostManager{
        private $post_num = 2;
        private $filter_id, $searched_data, $searched_value;

        public function LoadPosts($logged_user_id, $filter_id, $searched_value, $searched_data){
            $this->filter_id = $filter_id;
            $this->searched_data = $searched_data;
            $this->searched_value = $searched_value;

            $this->InitializeStorageData();
            
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

        private function InitializeStorageData(){
            if(!isset($_SESSION["search_value"])){
                $_SESSION["search_value"] = $this->searched_value;
            }
            else if($_SESSION["search_value"] != $this->searched_value){
                $_SESSION["search_value"] = $this->searched_value;
                unset($_SESSION["posts"]);
            }
            
            
            //uložení právě používaného filtru
            if(!isset($_SESSION["filter_id"])){
                $_SESSION["filter_id"] = (int)$this->filter_id;
            }
            //Vynulování počtu načtených příspěvků pokud byl filter změněn
            else if($_SESSION["filter_id"] != $this->filter_id){
                $_SESSION["filter_id"] = (int)$this->filter_id;
                unset($_SESSION["posts"]);
            }
            if(!isset($_SESSION["posts"])){
                $_SESSION["posts"] = Db::GetAllRows($this->GetQuery());
            }
        }

        private function GetQuery(){
            $posts_queries = [
                [
                    "SELECT * FROM posts ".$this->Where()." ORDER BY add_date desc",
                    "SELECT * FROM posts ".$this->Where()." ORDER BY add_date asc",
                    "SELECT b.id_p, b.src, b.comment, b.add_date, b.user_owner, count(post) from posts_likes a right join posts b on b.id_p=a.post ".$this->Where()." GROUP BY b.id_p, b.src, b.comment, b.add_date, b.user_owner ORDER BY COUNT(post) desc",
                ],
                [
                    "SELECT * FROM posts P LEFT JOIN tag_post TP ON P.id_p = TP.post ". $this->Where() ." ORDER BY add_date desc",
                    "SELECT * FROM posts P LEFT JOIN tag_post TP ON P.id_p = TP.post ". $this->Where() ." ORDER BY add_date asc",
                    "SELECT b.id_p, b.src, b.comment, b.add_date, b.user_owner, count(post) FROM posts P LEFT JOIN tag_post TP ON P.id_p = TP.post ". $this->Where() ." GROUP BY b.id_p, b.src, b.comment, b.add_date, b.user_owner ORDER BY COUNT(post) desc",
                ]
            ];
            return $posts_queries[$this->searched_data][$this->filter_id];
        }

        private function Where(){
            if($this->searched_value == -1) return "";
            $data = ["user_owner", "tag"];
            return "WHERE ". $data[$this->searched_data] . " = " . $this->searched_value;
        }
    }
?>