<?php
    class PostManager{
        private $post_num = 4;
        private $filter_id, $searched_data, $searched_value, $logged_user_id, $showcontrols;

        public function LoadPosts($logged_user_id, $filter_id, $searched_value, $searched_data, $showcontrols){
            $this->filter_id = $filter_id;
            $this->searched_data = $searched_data;
            $this->searched_value = $searched_value;
            $this->logged_user_id = $logged_user_id;
            $this->showcontrols = $showcontrols;

            $this->InitializeStorageData();
            $this->RenderPosts();
        }

        public function LikePost($logged_user_id, $post_id){
            $post_data = Db::GetOneRow("SELECT * FROM posts WHERE id_p = ?", [$post_id]);
            $post_object = new Post($post_data);
            $post_object->LikePost($logged_user_id);
        }
        
        public function DeletePost($logged_user_id, $post_id){
            $post_data = Db::GetOneRow("SELECT * FROM posts WHERE id_p = ?", [$post_id]);
            $post_object = new Post($post_data);
            if($post_object->GetUserOwner() === $logged_user_id){
                $post_object->DeleteSelf();
                unset($_SESSION["posts"]);
            }
        }
        
        private function RenderPosts(){
            for($i = 0; $i < $this->post_num; $i++){
                if(count($_SESSION["posts"]) === 0) return;
                $post_obj = new Post($_SESSION["posts"][$i]);
                $post_obj->RenderPost($this->logged_user_id, $this->showcontrols);
                unset($_SESSION["posts"][$i]);
            }
            $_SESSION["posts"] = array_values($_SESSION["posts"]);
        }


        private function InitializeStorageData(){
            if(!isset($_SESSION["search_value"])){
                $_SESSION["search_value"] = $this->searched_value;
            }
            else if($_SESSION["search_value"] != $this->searched_value){
                $_SESSION["search_value"] = $this->searched_value;
                unset($_SESSION["posts"]);
            }
            
            
            //ulo??en?? pr??v?? pou????van??ho filtru
            if(!isset($_SESSION["filter_id"])){
                $_SESSION["filter_id"] = (int)$this->filter_id;
            }
            //Vynulov??n?? po??tu na??ten??ch p????sp??vk?? pokud byl filter zm??n??n
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
                    "SELECT DISTINCT P.* FROM posts P LEFT JOIN tag_post TP ON P.id_p = TP.post ". $this->Where() ." ORDER BY add_date desc",
                    "SELECT DISTINCT P.* FROM posts P LEFT JOIN tag_post TP ON P.id_p = TP.post ". $this->Where() ." ORDER BY add_date asc",
                    "SELECT P.id_p, P.src, P.comment, P.add_date, P.user_owner, count(PL.post) from posts_likes PL right join posts P on P.id_p = PL.post LEFT JOIN tag_post TP on P.id_p = TP.post ". $this->Where() ." GROUP BY P.id_p, P.src, P.comment, P.add_date, P.user_owner ORDER BY COUNT(PL.post) desc"
                ],
                [
                    "SELECT * FROM posts WHERE user_owner = " . $this->logged_user_id." ORDER BY add_date desc",
                    "SELECT * FROM posts P INNER JOIN posts_likes PL ON P.id_p = PL.post WHERE PL.user = " . $this->logged_user_id." ORDER BY P.add_date desc",
                ]
            ];
            return $posts_queries[$this->searched_data][$this->filter_id];
        }

        private function Where(){
            if($this->searched_value == -1) return "";
            $data = ["user_owner", "TP.tag"];
            return "WHERE ". $data[$this->searched_data] . " = " . $this->searched_value;
        }
    }
?>