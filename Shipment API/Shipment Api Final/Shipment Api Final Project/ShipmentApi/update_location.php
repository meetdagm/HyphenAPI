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
$Shipment_id = $url->getHttpHeaderData('shipment_id');
$current_location = $url->getHttpHeaderData('current_location');


//$Shipment_id = "5";
//$current_location = "7.45545,8.4545";


if ($current_location == "") {
    $errorMessage = "Current location is not provided!";
    $isError = 1;
}

if ($Shipment_id == "") {
    $errorMessage = "Shipment_id is not provided!";
    $isError = 1;
}
//endregion

//region attempt to delete
if ($isError != 1) {
    $shipmentDeliveryMan->updateLocation($Shipment_id, $current_location);
} else {
    array_push($response, array("server_response" => "update_location_error",
        "error" => "$errorMessage"));
    echo json_encode($response);
    exit();
}
//endregion