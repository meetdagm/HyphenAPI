<?php
/**
 * Created by PhpStorm.
 * User: Kaleab Yalewdeg
 * Date: 6/28/2019
 * Time: 5:08 PM
 */

$page = "register";
include "configuration/header.php";

// if user is logged in, exit from login page
if ($user->userExists == 1) {
    echo json_encode(array('server_response'=>'user exists'));
    exit();
}

// SET ERROR VARS
$isError = 0;
$errorMessage = "";

// get user registration info (email or phone)
$full_name = $url->getHttpHeaderData('full_name');
$email = $url->getHttpHeaderData('email');
$password = $url->getHttpHeaderData('password');
$confirm_password = $url->getHttpHeaderData('confirm_password');
$profile_pic_url = $url->getHttpHeaderData('profile_pic_url');
if ($password == "" || $confirm_password == "") {
    $errorMessage = "passwords are not provided!";
    $isError = 1;
}
$password = base64_decode($password);
$confirm_password = base64_decode($confirm_password);
if ($full_name == "") {
    $errorMessage = "fullname is not provided!";
    $isError = 1;
}

if ($password != $confirm_password) {
    $errorMessage = "passwords are not the same!";
    $isError = 1;
}
if ($isError == 0) {

    $create_user_response = $user->createUser($full_name, $email, $password);

    if ($create_user_response == "registration_success") {
        echo json_encode(array("server_response" => "registration_success"));
        exit();
    } else {
        echo json_encode(array("server_response" => "registration_error", "error" => $create_user_response));
        exit();
    }

} else {
    echo json_encode(array("server_response" => "registration_error",
        "error" => $errorMessage));
    exit();
}
