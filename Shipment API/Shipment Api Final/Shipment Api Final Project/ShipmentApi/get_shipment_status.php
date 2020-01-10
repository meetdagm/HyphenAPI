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

//region check user exist
$response = array();
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

//region get shipment id
$shipment_id = $url->getHttpHeaderData('shipment_id');
//endregion

//region attempt to List data admin side
if ($shipment_id == "") {
    $isError = 1;
    $errorMessage = "You must specify the shipment!";
}
if ($isError == 1) {
    array_push($response, array("server_response" => "get_shipment_status_error", "error" => $errorMessage));
    echo json_encode($response);
    exit();
} else {
    $shipment->getShipmentStatus($shipment_id);
}
//endregion