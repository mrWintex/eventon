<?php
    class PostManager{
        private $post_num = 2;
        public $posts_queries;
        
        function __construct()
        {
            if(!isset($_SESSION["loaded_posts"])){
                $_SESSION["loaded_posts"] = 0;
            }
        }

        public function LoadPosts($logged_user_id){
            $posts = Db::GetAllRows($this->posts_queries[$_SESSION["filter_id"]]);
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

        public function SetQueries($filter_id){
            if(!isset($_SESSION["filter_id"])){
                $_SESSION["filter_id"] = (int)$filter_id;
            }
            else if($_SESSION["filter_id"] != $filter_id){
                $_SESSION["loaded_posts"] = 0;
                $_SESSION["filter_id"] = (int)$filter_id;
            }
            $this->posts_queries = [
                "SELECT * FROM posts ORDER BY add_date desc LIMIT {$_SESSION["loaded_posts"]}, $this->post_num",
                "SELECT * FROM posts ORDER BY add_date asc LIMIT {$_SESSION["loaded_posts"]}, $this->post_num",
                "SELECT b.id_p, b.src, b.comment, b.add_date, b.user_owner, count(post) from posts_likes a right join posts b on b.id_p=a.post  GROUP BY b.id_p, b.src, b.comment, b.add_date, b.user_owner ORDER BY COUNT(post) desc LIMIT {$_SESSION["loaded_posts"]}, $this->post_num",
            ];
        }
    }
?>