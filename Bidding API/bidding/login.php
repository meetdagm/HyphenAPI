<?php
/**
 * Created by PhpStorm.
 * User: Kaleab Yalewdeg
 * Date: 6/14/2019
 * Time: 10:14 AM
 */

$page = "login";
include "configuration/header.php";

// if user is logged in, exit from login page
if ($user->userExists == 1) {
    echo json_encode(array('server_response'=>'user exists'));
    exit();
}

// initiate error variables
$isError = 0;
$errorMessage = "";

// get user login info (email or phone)
$email = $url->getHttpHeaderData('email');
$password = $url->getHttpHeaderData('password');
// attempt to login user
echo $user->login($email, $password);
// check weather login was successful or not
if ($user->isError == 0) {
    exit();
}