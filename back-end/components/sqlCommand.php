<?php


class SqlCommand
{
    public static function insert($table, $columns)
    {
        $sql = "INSERT INTO $table (" . implode(", ", $columns) . ") VALUES (" . str_repeat("?, ", count($columns) - 1) . "?)";
        return $sql;
    }

    public static function select($table, $columns = "*")
    {
        $sql = "SELECT $columns FROM $table";
        return $sql;
    }

    public static function selectById($table)
    {
        $sql = "SELECT * FROM $table WHERE id = ?";
        return $sql;
    }
    public static function update($table, $columns)
    {
        $sql = "UPDATE $table SET ";
        foreach ($columns as $column) $sql .= "$column = ?, ";
        $sql = rtrim($sql, ", ");
        $sql .= " WHERE id = ?";
        return $sql;
    }

    public static function delete($table)
    {
        $sql = "DELETE FROM $table WHERE id = ?";
        return $sql;
    }
    public static function selectByEmail($table, $email = "?")
    {
        $sqlQuery = "SELECT * FROM $table WHERE email = $email";
        return $sqlQuery;
    }
    public static function select2Tables($table1, $table2, $email = "?")
    {
        $sqlQuery = "SELECT id , email , password , role FROM $table1 WHERE email = $email UNION SELECT id , email , password , role FROM $table2 WHERE email = $email";
        return $sqlQuery;
    }
}
