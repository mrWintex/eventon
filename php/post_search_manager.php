<?php
    require("./class_autoloader.php");
    $tables = [
        ["users", "username"],
        ["tags", "name"]
    ];
    $max_items_in_list = 7;
    if(isset($_GET["search_value"]) && $_GET["search_value"]){
        $searched_value = UserManager::ClearStr($_GET["search_value"]);
        if($searched_value){
            $data = Db::GetAllRows("SELECT * FROM {$tables[$_GET["search_data"]][0]} WHERE {$tables[$_GET["search_data"]][1]} LIKE '".$searched_value."%' LIMIT $max_items_in_list");
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
    }
    if(isset($_POST["tag_search"])){
        $used_tags = json_decode(stripcslashes($_POST["used_tags"]));
        $searched_tag = UserManager::ClearStr($_POST["tag_search"]);
        if($searched_tag){
            $tags = Db::GetAllRows("SELECT * FROM tags WHERE name LIKE '{$searched_tag}%' ".FilterTags($used_tags)." LIMIT $max_items_in_list");
        if($tags){
            foreach($tags as $tag){
                ?>
                <div class="tag-autocomplete-item" id="<?=$tag["id_t"]?>"><?=$tag["name"]?></div>
                <?php
            }
        }
        else
            echo("<div class='tag-autocomplete-item create-item'>vytvořit tag</div>");
        }
    }
    function FilterTags($used_tags){
        if(count($used_tags) === 0) return;
        
        $sql = "AND name NOT IN (";
        for($i = 0; $i < count($used_tags); $i++){
            $sql .= "'" . $used_tags[$i] . "'";
            if(($i + 1) !== count($used_tags)) $sql .= ",";
            else $sql .= ")";
        }
        return $sql;
    }
?>