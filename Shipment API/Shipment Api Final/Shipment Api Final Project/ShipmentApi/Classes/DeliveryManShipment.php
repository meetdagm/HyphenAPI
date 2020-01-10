<?php
/**
 * Created by PhpStorm.
 * User: AMANA
 * Date: 11/4/2019
 * Time: 6:40 AM
 */

class DeliveryManShipment
{
    function acceptShipment($shipment_id)
    {
        global $database, $user;
        $response = array();
        $user_id = $user->user_info["user_id"];
        if ($user->user_type != "DELIVERY_MAN") {
            array_push($response, array(
                "server_response" => "accept_shipment_error",
                "error" => "user is not Delivery Man",
            ));
            echo json_encode($response);
            exit();
        }
        $get_shipment_query = "SELECT * FROM shipment WHERE shipment_id='$shipment_id'";
        $get_shipment_result = $database->database_query($get_shipment_query);
        if ($database->database_num_rows($get_shipment_result) > 0) {
            $row = $database->database_fetch_assoc($get_shipment_result);
            if ($row['shipment_status'] == "open" || $row['shipment_status'] == "closed") {
                $update_query = "UPDATE shipment set delivery_man_id = '$user_id',shipment_status = 'accepted'WHERE shipment_id = " . $shipment_id;
                if ($database->database_query($update_query)) {
                    $update_affected_rows = $database->database_affected_rows($database->database_connection);
                    if ($update_affected_rows >= 1) {
                        array_push($response, array(
                            "server_response" => "accept_shipment_success",
                        ));
                        echo json_encode($response);
                    } else if ($update_affected_rows == 0) {
                        array_push($response, array(
                            "server_response" => "accept_shipment_error",
                            "error" => "NO_CHANGE",
                        ));
                        echo json_encode($response);
                    } else {
                        array_push($response, array(
                            "server_response" => "accept_shipment_error",
                            "error" => "Can't Update",
                        ));
                        echo json_encode($response);
                    }
                } else {
                    array_push($response, array(
                        "server_response" => "accept_shipment_error",
                        "error" => "Can't Update",
                    ));
                    echo json_encode($response);
                }
            } else if ($row['shipment_status'] == "accepted") {
                array_push($response, array(
                    "server_response" => "accept_shipment_error",
                    "error" => "Shipment is already accepted",
                ));
                echo json_encode($response);
            } else {
                array_push($response, array(
                    "server_response" => "accept_shipment_error",
                    "error" => "Shipment is not open or closed",
                ));
                echo json_encode($response);
            }
        } else {
            array_push($response, array(
                "server_response" => "accept_shipment_error",
                "error" => "No shipment Available ",
            ));
            echo json_encode($response);
        }
    }

    function startShipment($shipment_id, $startingLocation)
    {
        global $database, $user;
        $response = array();
        $user_id = $user->user_info["user_id"];
        if ($user->user_type != "DELIVERY_MAN") {
            array_push($response, array(
                "server_response" => "start_shipment_error",
                "error" => "user is not Delivery Man",
            ));
            echo json_encode($response);
            exit();
        }
        $get_shipment_query = "SELECT * FROM shipment WHERE shipment_id='$shipment_id' and delivery_man_id ='$user_id'";
        $get_shipment_result = $database->database_query($get_shipment_query);
        if ($database->database_num_rows($get_shipment_result) > 0) {
            $row = $database->database_fetch_assoc($get_shipment_result);
            if ($row['shipment_status'] == "accepted") {
                $update_query = "UPDATE shipment set delivery_man_starting_location = '$startingLocation',shipment_status = 'started'WHERE shipment_id = " . $shipment_id;
                if ($database->database_query($update_query)) {
                    $update_affected_rows = $database->database_affected_rows($database->database_connection);
                    if ($update_affected_rows >= 1) {
                        array_push($response, array(
                            "server_response" => "start_shipment_success",
                        ));
                        echo json_encode($response);
                    } else if ($update_affected_rows == 0) {
                        array_push($response, array(
                            "server_response" => "start_shipment_error",
                            "error" => "NO_CHANGE",
                        ));
                        echo json_encode($response);
                    } else {
                        array_push($response, array(
                            "server_response" => "start_shipment_error",
                            "error" => "Can't Update",
                        ));
                        echo json_encode($response);
                    }
                } else {
                    array_push($response, array(
                        "server_response" => "start_shipment_error",
                        "error" => "Can't Update",
                    ));
                    echo json_encode($response);
                }
            } else if ($row['shipment_status'] == "started") {
                array_push($response, array(
                    "server_response" => "start_shipment_error",
                    "error" => "Shipment is already started",
                ));
                echo json_encode($response);
            } else {
                array_push($response, array(
                    "server_response" => "start_shipment_error",
                    "error" => "Shipment is ". $row['shipment_status']
                ));
                echo json_encode($response);
            }
        } else {
            array_push($response, array(
                "server_response" => "start_shipment_error",
                "error" => "No shipment Available",
            ));
            echo json_encode($response);
        }
    }

    function receiveShipment($shipment_id)
    {
        global $database, $user;
        $response = array();
        $user_id = $user->user_info["user_id"];
        if ($user->user_type != "DELIVERY_MAN") {
            array_push($response, array(
                "server_response" => "received_shipment_error",
                "error" => "user is not Delivery Man",
            ));
            echo json_encode($response);
            exit();
        }
        $get_shipment_query = "SELECT * FROM shipment WHERE shipment_id='$shipment_id' and delivery_man_id ='$user_id'";
        $get_shipment_result = $database->database_query($get_shipment_query);
        if ($database->database_num_rows($get_shipment_result) > 0) {
            $row = $database->database_fetch_assoc($get_shipment_result);
            if ($row['shipment_status'] == "started") {
                $update_query = "UPDATE shipment set shipment_status = 'received'WHERE shipment_id = " . $shipment_id;
                if ($database->database_query($update_query)) {
                    $update_affected_rows = $database->database_affected_rows($database->database_connection);
                    if ($update_affected_rows >= 1) {
                        array_push($response, array(
                            "server_response" => "received_shipment_success",
                        ));
                        echo json_encode($response);
                    } else if ($update_affected_rows == 0) {
                        array_push($response, array(
                            "server_response" => "received_shipment_error",
                            "error" => "NO_CHANGE",
                        ));
                        echo json_encode($response);
                    } else {
                        array_push($response, array(
                            "server_response" => "received_shipment_error",
                            "error" => "Can't Update",
                        ));
                        echo json_encode($response);
                    }
                } else {
                    array_push($response, array(
                        "server_response" => "received_shipment_error",
                        "error" => "Can't Update",
                    ));
                    echo json_encode($response);
                }
            } else if ($row['shipment_status'] == "received") {
                array_push($response, array(
                    "server_response" => "received_shipment_error",
                    "error" => "Shipment is already received",
                ));
                echo json_encode($response);
            } else {
                array_push($response, array(
                    "server_response" => "received_shipment_error",
                    "error" => "Shipment is ".$row['shipment_status']
                ));
                echo json_encode($response);
            }
        } else {
            array_push($response, array(
                "server_response" => "received_shipment_error",
                "error" => "No shipment Available",
            ));
            echo json_encode($response);
        }
    }

    function finishShipment($shipment_id)
    {
        global $database, $user;
        $response = array();
        $user_id = $user->user_info["user_id"];
        if ($user->user_type != "DELIVERY_MAN") {
            array_push($response, array(
                "server_response" => "finish_shipment_error",
                "error" => "user is not Delivery Man",
            ));
            echo json_encode($response);
            exit();
        }
        $get_shipment_query = "SELECT * FROM shipment WHERE shipment_id='$shipment_id' and delivery_man_id ='$user_id'";
        $get_shipment_result = $database->database_query($get_shipment_query);
        if ($database->database_num_rows($get_shipment_result) > 0) {
            $row = $database->database_fetch_assoc($get_shipment_result);
            if ($row['shipment_status'] == "received") {
                $update_query = "UPDATE shipment set shipment_status = 'delivered'WHERE shipment_id = " . $shipment_id;
                if ($database->database_query($update_query)) {
                    $update_affected_rows = $database->database_affected_rows($database->database_connection);
                    if ($update_affected_rows >= 1) {
                        array_push($response, array(
                            "server_response" => "finish_shipment_success",
                        ));
                        echo json_encode($response);
                    } else if ($update_affected_rows == 0) {
                        array_push($response, array(
                            "server_response" => "finish_shipment_error",
                            "error" => "NO_CHANGE",
                        ));
                        echo json_encode($response);
                    } else {
                        array_push($response, array(
                            "server_response" => "finish_shipment_error",
                            "error" => "Can't Update",
                        ));
                        echo json_encode($response);
                    }
                } else {
                    array_push($response, array(
                        "server_response" => "finish_shipment_error",
                        "error" => "Can't Update",
                    ));
                    echo json_encode($response);
                }
            } else if ($row['shipment_status'] == "delivered") {
                array_push($response, array(
                    "server_response" => "finish_shipment_error",
                    "error" => "Shipment is already delivered",
                ));
                echo json_encode($response);
            } else {
                array_push($response, array(
                    "server_response" => "finish_shipment_error",
                    "error" => "Shipment is ".$row['shipment_status']
                ));
                echo json_encode($response);
            }
        } else {
            array_push($response, array(
                "server_response" => "finish_shipment_error",
                "error" => "No shipment Available",
            ));
            echo json_encode($response);
        }
    }

    function cancelShipment($shipment_id, $reason)
    {
        global $database, $user;
        $response = array();
        $user_id = $user->user_info["user_id"];
        if ($user->user_type != "DELIVERY_MAN") {
            array_push($response, array(
                "server_response" => "cancel_shipment_error",
                "error" => "user is not Delivery Man",
            ));
            echo json_encode($response);
            exit();
        }
        $get_shipment_query = "SELECT * FROM shipment WHERE shipment_id='$shipment_id' and delivery_man_id ='$user_id'";
        $get_shipment_result = $database->database_query($get_shipment_query);
        if ($database->database_num_rows($get_shipment_result) > 0) {
            $row = $database->database_fetch_assoc($get_shipment_result);
            if ($row['shipment_status'] == "accepted" || $row['shipment_status'] == "started" || $row['shipment_status'] == "received") {
                $update_query = "UPDATE shipment set shipment_status = 'closed', note = '" . $row['note'] . ":Cancel - " . $reason . "' WHERE shipment_id = " . $shipment_id;
                if ($database->database_query($update_query)) {
                    $update_affected_rows = $database->database_affected_rows($database->database_connection);
                    if ($update_affected_rows >= 1) {
                        array_push($response, array(
                            "server_response" => "cancel_shipment_success",
                        ));
                        echo json_encode($response);
                    } else if ($update_affected_rows == 0) {
                        array_push($response, array(
                            "server_response" => "cancel_shipment_error",
                            "error" => "NO_CHANGE",
                        ));
                        echo json_encode($response);
                    } else {
                        array_push($response, array(
                            "server_response" => "cancel_shipment_error",
                            "error" => "Can't Update",
                        ));
                        echo json_encode($response);
                    }
                } else {
                    array_push($response, array(
                        "server_response" => "cancel_shipment_error",
                        "error" => "Can't Update",
                    ));
                    echo json_encode($response);
                }
            } else if ($row['shipment_status'] == "closed") {
                array_push($response, array(
                    "server_response" => "cancel_shipment_error",
                    "error" => "Shipment is already Cancel",
                ));
                echo json_encode($response);
            } else {
                array_push($response, array(
                    "server_response" => "cancel_shipment_error",
                    "error" => "Shipment is delivered or open",
                ));
                echo json_encode($response);
            }
        } else {
            array_push($response, array(
                "server_response" => "cancel_shipment_error",
                "error" => "No shipment Available",
            ));
            echo json_encode($response);
        }
    }

    function getShipmentStatus($shipment_id, $shipment_status)
    {
        global $database, $user;
        $response = array();
        $user_id = $user->user_info["user_id"];
        if ($user->user_type != "DELIVERY_MAN") {
            array_push($response, array(
                "server_response" => "get_shipment_status_error",
                "error" => "user is not Delivery Man",
            ));
            echo json_encode($response);
            exit();
        }
        if ($shipment_status == "open" || $shipment_status == "closed") {
            $get_shipment_query = "SELECT * FROM shipment WHERE shipment_id='$shipment_id' and  delivery_man_id=''";
            $get_shipment_result = $database->database_query($get_shipment_query);
            if ($database->database_num_rows($get_shipment_result) > 0) {
                $row = $database->database_fetch_assoc($get_shipment_result);
                echo json_encode(array(
                    "server_response" => "get_shipment_status_success",
                    "starting_location" => $row['starting_location'],
                    "destination" => $row['destination_location'],
                    "starting_time" => $row['starting_time'],
                    "end_time" => $row['end_time'],
                    "receiver_address" => $row['receiver_address'],
                    "note" => $row['note'],
                    "mode" => $row['mode']
                ));
            } else {
                array_push($response, array(
                    "server_response" => "get_shipment_status_error",
                    "error" => "No shipment Available ",
                ));
                echo json_encode($response);
            }
        } else if ($shipment_status == "started" || $shipment_status == "accepted" || $shipment_status == "delivered" || $shipment_status == "received") {
            $get_shipment_query = "SELECT * FROM shipment WHERE delivery_man_id='$user_id'and shipment_id='$shipment_id'";
            $get_shipment_result = $database->database_query($get_shipment_query);
            if ($database->database_num_rows($get_shipment_result) > 0) {
                $row = $database->database_fetch_assoc($get_shipment_result);
                $row['server_response'] = "get_shipment_status_success";
                array_push($response, $row);
                echo json_encode($response);
            } else {
                array_push($response, array(
                    "server_response" => "get_shipment_status_error",
                    "error" => "No shipment Available ",
                ));
                echo json_encode($response);
            }
        } else {
            array_push($response, array(
                "server_response" => "get_shipment_status_error",
                "error" => "invalid shipment status",
            ));
            echo json_encode($response);
        }
    }

    function listShipmentForDeliveryMan($limit, $offset, $shipment_status)
    {
        global $database, $user;
        $response = array();
        if ($user->user_type != "DELIVERY_MAN") {
            array_push($response, array(
                "server_response" => "list_shipment_error",
                "error" => "user is not Delivery Man",
            ));
            echo json_encode($response);
            exit();
        }
        $user_id = $user->user_info['user_id'];
        if ($shipment_status == "new") {
            if ($limit == "") {
                $user_selection_query = "SELECT * FROM shipment WHERE  shipment_status='open' or shipment_status='closed' LIMIT 10";
            } else {
                $user_selection_query = "SELECT * FROM shipment WHERE  shipment_status='open' or shipment_status='closed' LIMIT $limit OFFSET $offset";
            }
            $user_selection_result = $database->database_query($user_selection_query);
            if ($database->database_num_rows($user_selection_result) > 0) {
                while ($row = $database->database_fetch_assoc($user_selection_result)) {
                    array_push($response, array("server_response" => "list_shipment_success",
                        "shipment_id" => $row['shipment_id'],
                        "starting_location" => $row['starting_location'],
                        "destination" => $row['destination_location'],
                        "starting_time" => $row['starting_time'],
                        "end_time" => $row['end_time'],
                        "receiver_address" => $row['receiver_address'],
                        "note" => $row['note'],
                        "mode" => $row['mode']
                    ));
                }
                echo json_encode($response);
            } else {
                array_push($response, array(
                    "server_response" => "list_shipment_error",
                    "error" => "No shipment Available ",
                ));
                echo json_encode($response);
            }
        } else if ($shipment_status == "progress") {
            if ($limit == "") {
                $user_selection_query = "SELECT * FROM shipment WHERE  delivery_man_id='$user_id' LIMIT 10";
            } else {
                $user_selection_query = "SELECT * FROM shipment WHERE  delivery_man_id='$user_id' LIMIT $limit OFFSET $offset";
            }
            $user_selection_result = $database->database_query($user_selection_query);
            if ($database->database_num_rows($user_selection_result) > 0) {
                while ($row = $database->database_fetch_assoc($user_selection_result)) {
                    $row['server_response'] = "list_shipment_success";
                    array_push($response, $row);
                }
                echo json_encode($response);
            } else {
                array_push($response, array(
                    "server_response" => "list_shipment_error",
                    "error" => "No shipment Available ",
                ));
                echo json_encode($response);
            }
        } else {
            array_push($response, array(
                "server_response" => "get_shipment_status_error",
                "error" => "invalid shipment type",
            ));
            echo json_encode($response);

        }
    }

    function updateLocation($shipment_id, $current_location)
    {
        global $database, $user;
        $response = array();
        $user_id = $user->user_info["user_id"];
        if ($user->user_type != "DELIVERY_MAN") {
            array_push($response, array(
                "server_response" => "update_location_error",
                "error" => "user is not Delivery Man",
            ));
            echo json_encode($response);
            exit();
        }
        $get_shipment_query = "SELECT * FROM shipment WHERE shipment_id='$shipment_id' and delivery_man_id='$user_id'";
        $get_shipment_result = $database->database_query($get_shipment_query);
        if ($database->database_num_rows($get_shipment_result) > 0) {
            $row = $database->database_fetch_assoc($get_shipment_result);
            if ($row['shipment_status'] != "accepted" && $row['shipment_status'] != "open" && $row['shipment_status'] != "delivered") {
                if ($row['path'] != 0) {
                    $path = $row['path'] . ":" . $current_location;
                } else {
                    $path = $current_location;
                }
                $update_query = "UPDATE shipment set path = '$path',current_location = '$current_location'WHERE shipment_id = ' $shipment_id' and delivery_man_id='$user_id'";
                if ($database->database_query($update_query)) {
                    $update_affected_rows = $database->database_affected_rows($database->database_connection);
                    if ($update_affected_rows >= 1) {
                        array_push($response, array(
                            "server_response" => "update_location_success",
                        ));
                        echo json_encode($response);
                    } else if ($update_affected_rows == 0) {
                        array_push($response, array(
                            "server_response" => "update_location_error",
                            "error" => "NO_CHANGE",
                        ));
                        echo json_encode($response);
                    } else {
                        array_push($response, array(
                            "server_response" => "update_location_error",
                            "error" => "Can't Update",
                        ));
                        echo json_encode($response);
                    }
                } else {
                    array_push($response, array(
                        "server_response" => "update_location_error",
                        "error" => "Can't Update",
                    ));
                    echo json_encode($response);
                }
            } else {
                array_push($response, array(
                    "server_response" => "update_location_error",
                    "error" => "Shipment is open, accepted or delivered",
                ));
                echo json_encode($response);
            }
        } else {
            array_push($response, array(
                "server_response" => "update_location_error",
                "error" => "No shipment Available",
            ));
            echo json_encode($response);
        }
    }
}