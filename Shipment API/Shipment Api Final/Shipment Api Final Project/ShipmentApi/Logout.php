<?php
/**
 * Created by PhpStorm.
 * User: AMANA
 * Date: 11/4/2019
 * Time: 6:24 AM
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

//region attempt to logout user
$user->logout();
//endregion
