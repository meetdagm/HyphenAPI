<?php


class Database
{
    //region Variable Initialization
    var $database_connection; //DATABASE LINK IDENTIFIER
    var $log_stats; // VARIABLE DETERMINING WHETHER QUERY INFO SHOULD BE LOGGED
    var $query_stats; // ARRAY CONTAINING RELEVANT INFORMATION ABOUT QUERIES RUN
    //endregion

    // region Database Initialization
    function __construct($database_host, $database_username, $database_password, $database_name) {
        $this->database_connection = $this->database_connect($database_host, $database_username, $database_password);
        $this->database_select($database_name);
        $this->log_stats = 1;
        $this->query_stats = Array();
    }
    //endregion

    // region Connect To Database Server
    function database_connect($database_host, $database_username, $database_password)
    {
        return mysqli_connect($database_host, $database_username, $database_password);
    }
    //endregion

    // region Select Database
    function database_select($database_name)
    {
        return mysqli_select_db($this->database_connection, $database_name);
    }
    //endregion

    // region Query Database
    function database_query($database_query)
    {
        $query_result = mysqli_query($this->database_connection, $database_query);
        return $query_result;
    }
    //endregion

    // region Fetch a result row as a numeric array
    function database_fetch_array($database_result)
    {
        return mysqli_fetch_array($database_result);
    }
    //endregion

    //region Fetch a result row as an associative array
    function database_fetch_assoc($database_result)
    {
        return mysqli_fetch_assoc($database_result);
    }
    //endregion

    // region Return the number of rows in a result set:
    function database_num_rows($database_result)
    {
        return mysqli_num_rows($database_result);
    }
    //endregion

    // region Print out affected rows from different queries
    function database_affected_rows($database_connection)
    {
        return mysqli_affected_rows($database_connection);
    }
    // endregion

    // region Return the id used in the last query
    function database_insert_id()
    {
        return mysqli_insert_id($this->database_connection);
    }
    // endregion

    // region Return the last error description for the most recent function call
    function database_error()
    {
        return mysqli_error($this->database_connection);
    }
    // endregion

    // region Close a previously opened database connection
    function database_close()
    {
        mysqli_close($this->database_connection);
    }
    // endregion
}