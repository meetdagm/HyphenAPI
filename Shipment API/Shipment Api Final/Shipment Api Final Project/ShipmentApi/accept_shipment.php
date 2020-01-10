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

//region Get post info (email or phone)
$Shipment_id = $url->getHttpHeaderData('shipment_id');
//endregion

//region attempt to delete
if ($Shipment_id != "") {
    $shipmentDeliveryMan->acceptShipment($Shipment_id);
} else {
    array_push($response, array("server_response" => "accept_shipment_error", "error" => "You must specify the shipment to be accepted!"));
    echo json_encode($response);
    exit();
}
//endregion