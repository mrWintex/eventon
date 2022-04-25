<?php
    require_once(__DIR__ . "/db_connect.php");
    require_once(__DIR__ . "/functions/admin_functions.php");
    require_once(__DIR__ . "/functions/database_functions.php");

    $table_items = [
        ["id_u", "username", "email", "folder_path", "admin"],  //USERS table
        ["id_p", "src", "comment", "add_date", "user_owner"],   //POSTS table
        ["id_t", "name"],                                       //TAGS table
    ];

    if(isset($_GET["search_query"])){
        $items = GetArrayFromQuery($database, $_GET["search_query"], 0);
        foreach($items as $item){
            GetTableData($item, $table_items[$_GET["data_index"]]);
        }
    }

    if(isset($_GET["delete_query"])){
        switch($_GET["data_index"]){
            case 0: DeleteUser($database, $_GET["item_query"], $_GET["delete_query"]); break;
            case 1: DeletePost($database, $_GET["item_query"], $_GET["delete_query"]); break;
            case 2: break;
        }
    }

    if(isset($_GET["change_query"])){
        mysqli_query($database, $_GET["change_query"]);
    }

    if(isset($_GET["header_query"])){
        $header_item = GetArrayFromQuery($database, $_GET["header_query"], 0);
        if(count($header_item))
            GetTableHeader($header_item[0], $table_items[$_GET["data_index"]]);
    }
    
    if(isset($_GET["body_query"])){
        $body_items = GetArrayFromQuery($database, $_GET["body_query"], 0);
        if(count($body_items))
        foreach($body_items as $body_item) GetTableData($body_item, $table_items[$_GET["data_index"]]);
    }
?>