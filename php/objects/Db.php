<?php
    class Db{
        private const HOSTNAME = "localhost";
        private const USERNAME = "root";
        private const PASSWORD = "";
        private const DATABASE = "eventon_database";

        private static $db_connection;
        private static $settings = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_EMULATE_PREPARES => false,
        );

        public static function Connect(){
            if(!isset(self::$db_connection)){
                self::$db_connection = @new PDO("mysql:host=".self::HOSTNAME.";dbname=".self::DATABASE, self::USERNAME, self::PASSWORD, self::$settings);
            }
        }

        public static function GetResult($query, $params = []){
            $result = self::$db_connection->prepare($query);
            $result->execute($params);
            return $result;
        }

        public static function GetOneRow($query, $params = []){
            return self::GetResult($query, $params)->fetch();
        }

        public static function GetAllRows($query, $params = []){
            return self::GetResult($query, $params)->fetchAll();
        }

        public static function Exists($table, $column, $item){
            $query = "SELECT * FROM $table WHERE $column = ?";
            $exists = (self::GetAllRows($query, [$item])) ? true : false;
            return $exists;
        }

        public static function ExecuteQuery($query, $params = []){
            $queryResult = self::$db_connection->prepare($query);
            $queryResult->execute($params);
            if($queryResult) return true;
            else return false;
        }

        public static function ChangeData($table, $column, $new_data, $id_column, $id){
            $change_query = "UPDATE $table SET $column = ? WHERE $id_column = ?";
            self::ExecuteQuery($change_query, [$new_data, $id]);
        }
    }

    Db::Connect();
?>