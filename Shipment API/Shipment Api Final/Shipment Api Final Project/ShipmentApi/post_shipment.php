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
$Starting_loc = $url->getHttpHeaderData('starting_loc');
$Destination_loc = $url->getHttpHeaderData('destination_loc');
$Starting_time = $url->getHttpHeaderData('starting_time');
$End_time = $url->getHttpHeaderData('end_time');
$Receiver_address = $url->getHttpHeaderData('receiver_address');
$note = $url->getHttpHeaderData('note');
$mode = $url->getHttpHeaderData('mode');
//endregion


//region attempt to post
if ($Starting_loc == "") {
    $isError = 1;
    $errorMessage = "Starting location is not provided";

}elseif ($mode == "") {
    $isError = 1;
    $errorMessage = "Destination location is not provided";

} elseif ($Destination_loc == "") {
    $isError = 1;
    $errorMessage = "Destination location is not provided";

} elseif ($Starting_time == "") {
    $isError = 1;
    $errorMessage = "Starting time is not provided";
} elseif ($Receiver_address == "") {
    $isError = 1;
    $errorMessage = "Receiver address is not provided";
}
if ($isError == 1) {
    array_push($response, array("server_response" => "post_shipment_error", "error" => $errorMessage));
    echo json_encode($response);
    exit();
} else {
    $shipment->post($Starting_loc, $Destination_loc, $Starting_time, $End_time, $Receiver_address, $note, $mode);
}
//endregion