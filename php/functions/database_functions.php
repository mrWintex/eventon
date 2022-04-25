<?php
    function GetArrayFromQuery($database, $query, $mode=0){
        $result = mysqli_query($database, $query);
        if($result === false)
            return false;

        switch($mode){
            case 0:
                $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
                break;
            case 1:
                $array = mysqli_fetch_all($result, MYSQLI_NUM);
                break;
            case 2:
                $array = mysqli_fetch_assoc($result);
                break;
        }
        return $array;
    }

    function ChangeData($database, $table, $column, $new_data, $id_column, $id){
        $change_query = "UPDATE {$table} SET {$column} = '{$new_data}' WHERE {$id_column} = '{$id}'";
        mysqli_query($database, $change_query);
    }

    function Exists($database, $table, $column, $item){
        $query = "SELECT * from {$table} WHERE {$column} = '{$item}' LIMIT 1";
        $result = mysqli_query($database, $query);
        if(mysqli_fetch_assoc($result)) return 1;
        else return 0;
    }
?>