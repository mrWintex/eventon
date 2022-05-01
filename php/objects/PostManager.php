<?php
    class PostManager{
        private $post_num = 2;

        function __construct()
        {
            if(!isset($_SESSION["loaded_posts"])){
                $_SESSION["loaded_posts"] = 0;
            }
        }

        public function LoadPosts($logged_user_id){
            $get_query = "SELECT * FROM posts ORDER BY id_p desc LIMIT {$_SESSION["loaded_posts"]}, $this->post_num";

            $posts = Db::GetAllRows($get_query);
            foreach($posts as $post){
                $post_obj = new Post($post);
                $post_obj->CreatePost($logged_user_id);
            }
            $_SESSION["loaded_posts"]+=$this->post_num;
        }

        public function LikePost($logged_user_id, $post_id){
            $post_data = Db::GetOneRow("SELECT * FROM posts WHERE id_p = ?", [$post_id]);
            $post_object = new Post($post_data);
            $post_object->LikePost($logged_user_id);
        }
    }
?>