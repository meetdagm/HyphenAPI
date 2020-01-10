<?php
/**
 * Created by PhpStorm.
 * User: hi
 * Date: 10/31/2019
 * Time: 11:19 PM
 */


$page = "view_bids";
include "configuration/header.php";

// if user is not logged in forward user to login page

$bid_id = $url->getHttpHeaderData('bid_id');
$limit = $url->getHttpHeaderData('limit');
$offset = $url->getHttpHeaderData('offset');
$bid_status = $url->getHttpHeaderData('bid_status');
if ($user->userExists == 0) {
    $response = array();
    echo json_encode(array("server_response" => "no_user"));
    exit();
} else {
    if ($bid_id != "") {
       $bid->view_single_bid_detail($bid_id,$limit, $offset);
//        echo json_encode(array("server_response" => "view_bid_success",
//            "bids" => $view_bid_response));
        exit();
    }
            $view_bid_response = $bid->view_bids($limit, $offset,$bid_status);
            echo json_encode(array("server_response" => "view_bid_success",
                "bids" => $view_bid_response));
            exit();

}

