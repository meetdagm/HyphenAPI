<?php
/**
 * Created by PhpStorm.
 * User: AMANA
 * Date: 11/4/2019
 * Time: 6:21 AM
 */

//region Include Header
include "header.php";
//endregion

$response = array();
//region check user exist
if ($user->user_exist == 0) {
    array_push($response, array("server_response" => "USER_NOT_EXISTS"));
    echo json_encode($response);
    exit();
}
//endregion

//region SET ERROR VARS
$isError = 0;
$errorMessage = "";
//endregion

//region Get post info (email or phone)
$shipment_status = $url->getHttpHeaderData('shipment_status');
$limit = $url->getHttpHeaderData('limit');
$offset = $url->getHttpHeaderData('offset');
//endregion

//region attempt to List data admin side
if ($shipment_status == "") {
    $isError = 1;
    $errorMessage = "shipment_status is not provided";
}
if ($limit != "") {
    if ($offset == "") {
        $isError = 1;
        $errorMessage = "offset is not provided";
    }
}
if ($isError == 1) {
    array_push($response, array("server_response" => "list_shipment_error", "error" => $errorMessage));
    echo json_encode($response);
    exit();
} else {
    $shipment->listShipmentForAdmin($limit, $offset, $shipment_status);
}
//endregion