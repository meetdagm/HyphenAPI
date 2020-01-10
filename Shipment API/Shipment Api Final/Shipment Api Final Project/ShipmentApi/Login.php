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
if ($user->user_exist == 1) {
    array_push($response, array("server_response" => "USER_EXISTS"));
    echo json_encode($response);
    exit();
}
//endregion

//region SET ERROR VARS
$isError = 0;
$errorMessage = "";
//endregion

//region get user login info (email or phone)
$email = $url->getHttpHeaderData('email');
$password = $url->getHttpHeaderData('password');

if ($email == "") {
    $isError = 1;
    $errorMessage = "Email is not provided";

}
if ($password == "") {
    $isError = 1;
    $errorMessage = "Password is not provided";

}
//endregion


//region check weather login was successful or not
if ($isError != 1) {
    //region attempt to login user
    $password = base64_decode($password);
    $user->login($email, $password);
    //endregion
} else {
    array_push($response, array("server_response" => "login_error",
        "error" => $errorMessage . $user->isError));
    echo json_encode($response);
    exit();

}
//endregion