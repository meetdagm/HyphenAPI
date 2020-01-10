<?php
/**
 * Created by PhpStorm.
 * User: Kaleab Yalewdeg
 * Date: 6/13/2019
 * Time: 11:58 AM
 */

$page = "home";
include "configuration/header.php";

// if user is not logged in forward user to login page
if ($user->userExists == 0) {
    $response = array();
    array_push($response, array("SERVER_RESPONSE" => "NO_USER"));
    echo json_encode($response);
    exit();
} else {

}

