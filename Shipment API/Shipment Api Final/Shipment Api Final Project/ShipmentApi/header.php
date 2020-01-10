<?php

//region include classes
include "./include/database/Database.php";;
include "./include/url/Url.php";
include "./include/database/database_config.php";
include "./include/Verification/Verification.php";
include "./classes/user.php";
include "./classes/Shipment.php";
include "./classes/DeliveryManShipment.php";
//endregion

//region initiate database connection
$database = new Database($database_host, $database_username, $database_password, $database_name);
//endregion

//	//region ENSURE NO SQL INJECTIONS THROUGH POST OR GET ARRAYS
//	$_POST = security( $_POST );
//	$_GET  = security( $_GET );
//	//endregion

//region create a url object
$url = new Url();
//endregion

//region create a vehicle class to access and manage delivery man status
$verification = new Verification();
//endregion

$shipment = new Shipment();

$shipmentDeliveryMan = new DeliveryManShipment();

//region create a vehicle class to access and manage delivery man status
$user = new User();
$user->check_cookie();
//endregion