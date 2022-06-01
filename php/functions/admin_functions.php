<?php
    //Table visuals functions
    function GetTableData($data){
        ?>
        <tr id="<?=$data[array_key_first($data)]?>">
            <?php foreach($data as $key=>$value) : ?>
                <td title="<?=$value?>"><input class="datachange-input" type="text" name="<?=$key?>" value="<?=$value?>"></td>
            <?php endforeach ?>
            <td class="buttons"><button class="delete-button" type="button" onclick="Delete(this)">DELETE</button></td>
        </tr>
        <?php
    }

    function GetTableHeader($data){
        ?>
        <tr>
            <?php foreach($data as $key=>$value) : ?>
            <th><?=$key?></th>
            <?php endforeach ?>
        </tr>
        <?php
    }

    //Admin functions
    function DeleteUser($item_query, $delete_query){
        $user = new User(Db::GetResult($item_query)->fetch());
        $success = Db::ExecuteQuery($delete_query);
        if($success && $user){
            $folder_path = dirname(__FILE__, 3) ."/". $user->GetUserFolderPath();
            if(file_exists($folder_path) && $folder_path !== dirname(__FILE__, 3)."/"){
                array_map('unlink', glob("$folder_path/*.*"));
                rmdir($folder_path);
            }
        }
    }

    function DeletePost($item_query){
        $post_object = new Post(Db::GetResult($item_query)->fetch());
        $post_object->DeleteSelf();
    }
    function DeleteTag($delete_query){
        Db::ExecuteQuery($delete_query);
    }
?>