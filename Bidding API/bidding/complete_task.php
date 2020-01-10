<?php
/**
 * Created by PhpStorm.
 * User: hi
 * Date: 10/31/2019
 * Time: 11:19 PM
 */


$page = "complete_task";
include "configuration/header.php";

// if user is not logged in forward user to login page

$bid_id = $url->getHttpHeaderData('bid_id');
if ($user->userExists == 0) {
    $response = array();
    echo json_encode(array("server_response" => "no_user"));
    exit();
} else {
    if ($bid_id != "") {
       $bid->complete_task($bid_id);
        exit();
    }else{
        echo json_encode(array("server_response" => "approve_bid_error",
            "error" => "You must specify the bid to be marked as completed!"));
    }

}

