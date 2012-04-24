<?php

class DB {

    private static $conn;

    public static function Connect($server, $username, $password, $database, $port = 3306) {
        self::$conn = mysqli_connect($server, $username, $password, $database, $port) or die("Could not connect to database.");
    }

    /**
     * Fetch a query
     *
     * @param string $query the query to execute
     * @return mysqli_result the result of the query, false if it failed
     */
    public static function GetQuery($query) {
        if(!self::$conn)
            return false;
        
        $sql = mysqli_query(self::$conn, $query) or die("Could not execute query: <b>" . mysqli_error(self::$conn) . "</b><br />In query: <b>" . $query . "<br />");
        if($sql) { return $sql; } else { return FALSE; }
    }

    public static function GetError() {
        if(!self::$conn)
            return false;

        return mysqli_error(self::$conn);
    }

    public static function GetArray($sql) {
        return mysqli_fetch_array($sql);
    }

    public static function GetAssoc($sql) {
        return mysqli_fetch_assoc($sql);
    }

    public static function GetNumRows($sql) {
        return mysqli_num_rows($sql);
    }

    public static function EscapeString($string) {
        if(!self::$conn)
            return false;

        return mysqli_real_escape_string(self::$conn, $string);
    }

    public static function GetInsertId() {
        if(!self::$conn)
            return false;
        
        return mysqli_insert_id(self::$conn);
    }

}