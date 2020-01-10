<?php
/**
 * Created by PhpStorm.
 * User: AMANA
 * Date: 11/4/2019
 * Time: 6:43 AM
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

//region Get post info (email or phone)
$Shipment_id = $url->getHttpHeaderData('shipment_id');
$startingLocation = $url->getHttpHeaderData('starting_location');

if ($Shipment_id == "") {
    $errorMessage = "You must specify the shipment to be accepted!";
    $isError = 1;
}
if ($startingLocation == "") {
    $errorMessage = "Starting Location not provided";
    $isError = 1;
}
//endregion

//region attempt to delete
if ($isError != 1) {
    $shipmentDeliveryMan->startShipment($Shipment_id, $startingLocation);
} else {
    array_push($response, array("server_response" => "start_shipment_error", "error" => $errorMessage));
    echo json_encode($response);
    exit();
}
//endregion