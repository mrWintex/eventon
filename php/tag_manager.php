<?php
    require(__DIR__ . "/class_autoloader.php");
    if(isset($_POST["new_tag"])){
        $new_tag = UserManager::ClearStr($_POST["new_tag"]);
        echo($new_tag); 
        if($new_tag){
        Db::ExecuteQuery("INSERT INTO tags (name) VALUES (?)", [$new_tag]);
        $tag = Db::GetOneRow("SELECT * FROM tags WHERE name LIKE '$new_tag'");
        ?><input type="hidden" name="selected-tag[<?=$tag["id_t"]?>]" value='<?=$tag["id_t"]?>'>
        <div class="tag"><span class="text"><?=$tag["name"]?></span><button onclick="RemoveTag(this)"class="tag-delete-button" type="button"><i class="fa-solid fa-xmark"></i></button></div>
        <?php
        }
    }
?>