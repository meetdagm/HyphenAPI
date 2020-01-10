<?php

//region Include Header
include "header.php";
//endregion

//region SET ERROR VARS
$isError = 0;
$errorMessage = "";
//endregion

//region check user exist
//if user is logged in, exit from login page
$response = array();
if ($user->user_exist == 1) {
    array_push($response, array("server_response" => "USER_EXIST"));
    echo json_encode($response);
    exit();
}
//endregion

//region get user registration info (email or phone)
$full_name = $url->getHttpHeaderData('full_name');
$email = $url->getHttpHeaderData('email');
$password = $url->getHttpHeaderData('password');
$confirm_password = $url->getHttpHeaderData('confirm_password');
$profile_pic_url = $url->getHttpHeaderData('profile_pic_url');
$user_type = $url->getHttpHeaderData('user_type');
//endregion

//region check register info
$password = base64_decode($password);
$confirm_password = base64_decode($confirm_password);
if ($full_name == "") {
    $errorMessage = "fullname is not provided!";
    $isError = 1;
}
if ($user_type == "") {
    $errorMessage = "user type is not provided!";
    $isError = 1;
}
if ($email == "") {
    $errorMessage = "fullname is not provided!";
    $isError = 1;
}

if ($password != $confirm_password) {
    $errorMessage = "passwords are not the same!";
    $isError = 1;
}
//endregion

//region attempt to register user
if ($isError == 0) {
    $create_user_response = $user->register($full_name, $email, $password, $profile_pic_url, $user_type);
    if ($create_user_response == "registration_success") {
        array_push($response, array("server_response" => "registration_success"));
        echo json_encode($response);
        exit();
    } else {
        array_push($response, array("server_response" => "registration_error", "error" => $create_user_response));
        echo json_encode($response);
        exit();
    }
} else {
    array_push($response, array("server_response" => "registration_error",
        "error" => $errorMessage));
    echo json_encode($response);
    exit();
}
//endregion