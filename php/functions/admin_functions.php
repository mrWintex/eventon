<?php
    //Table visuals functions
    function GetTableData($data, $indexes){
        ?>
        <tr id="<?=$data[$indexes[0]]?>">
            <?php
            for($i = 0; $i < count($indexes); $i++){
                ?><td title="<?=$data[$indexes[$i]]?>"><input class="datachange-input" type="text" name="<?=$indexes[$i]?>" value="<?=$data[$indexes[$i]]?>"></td>
            <?php
            }?>
            <td class="buttons"><button class="delete-button" type="button" onclick="Delete(this)">DELETE</button></td>
        </tr>
        <?php
    }

    function GetTableHeader($data, $indexes){
        ?>
        <tr>
            <?php
                foreach($data as $key=>$value){
                    if(in_array($key, $indexes)){
                        ?> <th><?=$key?></th> <?php
                    }
                }
            ?>
        </tr>
        <?php
    }

    //Admin functions
    function DeleteUser($database, $item_query, $delete_query){
        $user = GetArrayFromQuery($database, $item_query, 2);
        $success = mysqli_query($database, $delete_query);
        if($success && count($user) > 0){
            $folder_path = dirname(__FILE__, 3) ."/". $user["folder_path"];
            if(file_exists($folder_path) && $folder_path !== dirname(__FILE__, 2)."/"){
                array_map('unlink', glob("$folder_path/*.*"));
                rmdir($folder_path);
            }
        }
    }

    function DeletePost($database, $item_query, $delete_query){
        $post = GetArrayFromQuery($database, $item_query, 2);
        $success = mysqli_query($database, $delete_query);
        $post_path = dirname(__FILE__, 3) ."/".$post["src"];
        echo($post_path);
        if($success){
            if(file_exists($post_path)){
                unlink($post_path);
            }
        }
    }
?>