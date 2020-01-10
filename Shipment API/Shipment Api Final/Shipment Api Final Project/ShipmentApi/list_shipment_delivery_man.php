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

//region get shipment by type(new or on_progress)
$shipment_type = $url->getHttpHeaderData('shipment_type');
$limit = $url->getHttpHeaderData('limit');
$offset = $url->getHttpHeaderData('offset');
//endregion

//region attempt to post
if ($shipment_type == "") {
    $isError = 1;
    $errorMessage = "Shipment type is not provided";

}
//endregion

//region attempt to list open shipment
if ($isError != 1) {
    $shipmentDeliveryMan->listShipmentForDeliveryMan($limit, $offset, $shipment_type);
} else {
    array_push($response, array("server_response" => "list_shipment_error",
        "error" => $errorMessage));
    echo json_encode($response);
    exit();
}
//endregion