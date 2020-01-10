<?php
/**
 * Created by PhpStorm.
 * User: Kaleab Yalewdeg
 * Date: 6/14/2019
 * Time: 11:05 AM
 */

class Url
{
    // region get data from http header
    function getHttpHeaderData($headerName)
    {
        global $mysqli;
        $headerName = mysqli_real_escape_string($mysqli, $headerName);
        if (isset($_POST[$headerName])) {
            $headerData = $_POST[$headerName];
        } elseif (isset($_GET[$headerName])) {
            $headerData = $_GET[$headerName];
        } else {
            $headerData = "";
        }
        return $headerData;
    }
    // endregion
}