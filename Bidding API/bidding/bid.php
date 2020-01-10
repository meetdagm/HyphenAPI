<?php
/**
 * Created by PhpStorm.
 * User: hi
 * Date: 10/31/2019
 * Time: 11:19 PM
 */


$page = "bid";
include "configuration/header.php";

// if user is not logged in forward user to login page
$bid_id = $url->getHttpHeaderData('bid_id');
$proposed_price = $url->getHttpHeaderData('proposed_price');
$bidding_description = $url->getHttpHeaderData('bidding_description');
if ($user->userExists == 0) {
    $response = array();
    echo json_encode(array("server_response" => "no_user"));
    exit();
} else {
    if ($bid_id == "") {
        $user->isError = 1;
        $user->errorMessage = "Specify to which bid you are bidding!";
    }
    if ($user->isError == 1) {
        echo json_encode(array("server_response" => "bid_error",
            "error" => $user->errorMessage));
    } else {
//        $bid->add_bidding($bid_id, $proposed_price, $bidding_description);
        $bid->add_bidding($bid_id, $proposed_price, $bidding_description);
    }
}

