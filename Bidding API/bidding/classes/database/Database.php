<?php
/**
 * Created by PhpStorm.
 * User: Kaleab Yalewdeg
 * Date: 6/13/2019
 * Time: 12:02 PM
 */

class Database
{
    var $db_connection; //database connection variable

    //region database initialization
    function __construct($db_host, $db_username, $db_password, $db_name)
    {
        $this->db_connection = $this->dbConnect($db_host, $db_username, $db_password);
        $this->dbSelect($db_name);
    }
    //endregion

    //region connect to database server
    function dbConnect($db_host, $db_username, $db_password)
    {
        return mysqli_connect($db_host, $db_username, $db_password);
    }
    //endregion

    //region select database
    function dbSelect($db_name)
    {
        return mysqli_select_db($this->db_connection, $db_name);
    }
    //endregion

    //region query database and return result
    function dbQuery($db_query)
    {
        $query_result = mysqli_query($this->db_connection, $db_query);
        return $query_result;
    }
    //endregion

    // region fetch a result row as a numeric array
    function dbFetchArray($db_result)
    {
        return mysqli_fetch_array($db_result);
    }
    //endregion

    //region fetch a result row as an associative array
    function dbFetchAssoc($db_result)
    {
        return mysqli_fetch_assoc($db_result);
    }
    //endregion

    //region return the number of rows in a result set:
    function dbNumRows($db_result)
    {
        return mysqli_num_rows($db_result);
    }
    //endregion

    //region print out affected rows from different queries
    function dbAffectedRows($db_connection)
    {
        return mysqli_affected_rows($db_connection);
    }
    //endregion

    //region return the id used in the last query
    function dbInsertId()
    {
        return mysqli_insert_id($this->db_connection);
    }
    //endregion

    //region return the last error description for the most recent function call
    function dbError()
    {
        return mysqli_error($this->db_connection);
    }
    //endregion

    // region close a previously opened database connection
    function dbClose()
    {
        mysqli_close($this->db_connection);
    }
    // endregion
}