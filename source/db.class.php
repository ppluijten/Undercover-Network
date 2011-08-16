<?php

class DB {

    var $conn;

    function __construct($server, $username, $password, $database, $port = 3306) {
        $this->conn = mysqli_connect($server, $username, $password, $database, $port) or die("Could not connect to database.");
    }

    function GetQuery($query) {
        $sql = mysqli_query($this->conn, $query) or die("Could not execute query: <b>" . mysqli_error($this->conn) . "</b><br />In query: <b>" . $query . "<br />");
        if($sql) { return $sql; } else { return FALSE; }
    }

    function GetError() {
        return mysqli_error($this->conn);
    }

    function GetArray($sql) {
        return mysqli_fetch_array($sql);
    }

    function GetAssoc($sql) {
        return mysqli_fetch_assoc($sql);
    }

    function GetNumRows($sql) {
        return mysqli_num_rows($sql);
    }

    function EscapeString($string) {
        return mysqli_real_escape_string($this->conn, $string);
    }

    function GetInsertId() {
        return mysqli_insert_id($this->conn);
    }

}