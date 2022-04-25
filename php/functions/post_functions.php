<?php
    require(__DIR__ . "/database_functions.php");
    require(__DIR__ . "/icon_functions.php");

    function LoadPosts($database, $query, $user_id){
        $all_posts = GetArrayFromQuery($database, $query);
        if ($all_posts) {
            foreach ($all_posts as $post) {
                CreatePost($database, $post, $user_id);
            }
        }
    }

    function CreatePost($database, $post_data, $user_id)
    {
        $likes_count = GetArrayFromQuery($database, "SELECT COUNT(user) AS like_count FROM posts_likes WHERE post = '{$post_data["id_p"]}'");

        $date = (date("j.m.Y") == date("j.m.Y", strtotime($post_data["add_date"])))
        ? date("G:i", strtotime($post_data["add_date"]))
        : date("j.m.Y", strtotime($post_data["add_date"]));

        $user_liked = count(GetArrayFromQuery($database, "SELECT * FROM posts_likes WHERE post = '{$post_data["id_p"]}' AND user = '$user_id'"));
        $icon_path = GetUserIconPath($post_data["folder_path"]);
        require(dirname(__FILE__, 3) . "\phtml\PostStructure.phtml");
    }

    function LikePost($post_id, $user_id, $database){
        $user_like = GetArrayFromQuery($database, "SELECT * FROM posts_likes WHERE post = '{$post_id}' AND user = '{$user_id}'");

        $post_like_query = "";
        if(count($user_like) > 0)
        {
            $post_like_query = "DELETE FROM posts_likes WHERE post = '{$post_id}' AND user = '{$user_id}'";
        }
        else{
            $post_like_query = "INSERT INTO posts_likes (post, user) VALUES ('{$post_id}', '{$user_id}')";
        }

        mysqli_query($database, $post_like_query);
    }
?>