<?php

class Url {

	var $url_base;            // CONTAINS THE BASE URL TO WHICH FILENAMES CAN BE APPENDED
	var $convert_urls;        // CONTAINS THE URL CONVERSIONS
    // region get data from http header
    function getHttpHeaderData($headerName)
    {
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