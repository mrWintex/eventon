<?php
    class Db{
        private const HOSTNAME = "localhost";
        private const USERNAME = "root";
        private const PASSWORD = "";
        private const DATABASE = "eventon_database";

        private $db_connection;

        function __construct()
        {
            $this->db_connection = mysqli_connect(self::HOSTNAME, self::USERNAME, self::PASSWORD, self::DATABASE);
        }

        public function GetConnection(){
            return $this->db_connection;
        }

        function GetArrayFromQuery($query, $mode="multi_assoc"){
            $result = mysqli_query($this->db_connection, $query);
            if($result === false)
                return false;
    
            switch($mode){
                case "multi_assoc":
                    $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    break;
                case "num":
                    $array = mysqli_fetch_all($result, MYSQLI_NUM);
                    break;
                case "single_assoc":
                    $array = mysqli_fetch_assoc($result);
                    break;
            }
            return $array;
        }

        function Exists($table, $column, $item){
            $query = "SELECT * from {$table} WHERE {$column} = '{$item}' LIMIT 1";
            $result = mysqli_query($this->db_connection, $query);
            if(mysqli_fetch_assoc($result)) return true;
            else return false;
        }

        function ChangeData($table, $column, $new_data, $id_column, $id){
            $change_query = "UPDATE {$table} SET {$column} = '{$new_data}' WHERE {$id_column} = '{$id}'";
            $this->RunQuery($change_query);
        }

        function RunQuery($query){
            mysqli_query($this->db_connection, $query);
        }
    }
?>