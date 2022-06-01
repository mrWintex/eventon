<?php
    require_once(__DIR__ . "/functions/admin_functions.php");
    require_once(__DIR__ . "/class_autoloader.php");

    if(isset($_GET["search_query"])){
        $items = Db::GetResult($_GET["search_query"])->fetchAll(PDO::FETCH_ASSOC);
        foreach($items as $item){
            GetTableData($item);
        }
    }

    if(isset($_GET["delete_query"])){
        switch($_GET["data_index"]){
            case 0: DeleteUser($_GET["item_query"], $_GET["delete_query"]); break;
            case 1: DeletePost($_GET["item_query"]); break;
            case 2: DeleteTag($_GET["delete_query"]); break;
        }
    }

    if(isset($_GET["change_query"])){
        Db::ExecuteQuery($_GET["change_query"]);
    }

    if(isset($_GET["header_query"])){
        $header_item = Db::GetResult($_GET["header_query"])->fetch(PDO::FETCH_ASSOC);
        if($header_item){
            GetTableHeader($header_item);
        }
    }
    
    if(isset($_GET["body_query"])){
        $body_items = Db::GetResult($_GET["body_query"])->fetchAll(PDO::FETCH_ASSOC);
        if($body_items){
            foreach($body_items as $body_item) GetTableData($body_item);
        }
    }
?>