<?php
/**
 * Created by PhpStorm.
 * User: hi
 * Date: 10/31/2019
 * Time: 11:19 PM
 */


$page = "edit_bid";
include "configuration/header.php";

// if user is not logged in forward user to login page
$bid_id = $url->getHttpHeaderData('bid_id');
$bid_title = $url->getHttpHeaderData('bid_title');
$bid_description = $url->getHttpHeaderData('bid_description');
$price = $url->getHttpHeaderData('price');
$bid_picture_url = $url->getHttpHeaderData('bid_picture_url');
$expiration_date_time = $url->getHttpHeaderData('expiration_date_time');
if ($user->userExists == 0) {
    $response = array();
    echo json_encode(array("server_response" => "no_user"));
    exit();
} else {
    if ($bid_title == "") {
        $user->isError = 1;
        $user->errorMessage = "title is not provided!";

    } elseif ($bid_description == "") {
        $user->isError = 1;
        $user->errorMessage = "description is not provided!";

    } elseif ($price == "") {
        $user->isError = 1;
        $user->errorMessage = "price is not provided!";
    }
    if ($user->isError == 1) {
        echo json_encode(array("server_response" => "edit_bid_error",
            "error" => $user->errorMessage));
    }else{
        $bid->edit_bid($bid_id,$bid_title,$bid_description,$price,$bid_picture_url,$expiration_date_time);
    }
}

