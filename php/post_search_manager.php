<?php
    require("./class_autoloader.php");
    $tables = [
        ["users", "username"],
        ["tags", "name"]
    ];
    $max_items_in_list = 7;
    if(isset($_GET["search_value"]) && $_GET["search_value"]){
        $data = Db::GetAllRows("SELECT * FROM {$tables[$_GET["search_data"]][0]} WHERE {$tables[$_GET["search_data"]][1]} LIKE '".$_GET["search_value"]."%' LIMIT $max_items_in_list");
        if($_GET["search_data"] == 0 && $data){
            foreach($data as $item){
                $user_obj = new User($item);
                ?>
                <li class="posts-selector-search-results-list-item" data-id="<?= $user_obj->GetId(); ?>" data-usrnm="<?= $user_obj->GetUserName(); ?>"><img class="icon" src="<?= $user_obj->GetUserIcon(); ?>" alt="user-icon-<?= $user_obj->GetId() ?>"><span class="username"><?= $user_obj->GetUserName(); ?></span></li>
                <?php
            }
        }
        else if($_GET["search_data"] == 1 && $data){
            foreach($data as $item){
                ?>
                <li class="posts-selector-search-results-list-item" data-id="<?= $item["id_t"]; ?>" data-usrnm="<?= $item["name"]; ?>"><span class="username">#<?= $item["name"]; ?></span></li>
                <?php
            }
        }
    }

    if(isset($_GET["tag_search"])){
        $tags = Db::GetAllRows("SELECT * FROM tags WHERE name LIKE '{$_GET["tag_search"]}%' LIMIT $max_items_in_list");
        if($tags){
            foreach($tags as $tag){
                ?>
                <div class="tag-autocomplete-item"><?=$tag["name"]?></div>
                <?php
            }
        }
        else
            echo("<div class='tag-autocomplete-item'>vytvořit tag</div>");
    }
?>