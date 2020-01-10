<?php
/**
 * Created by PhpStorm.
 * User: AMANA
 * Date: 11/4/2019
 * Time: 6:40 AM
 */

class Shipment
{
    function post($Starting_loc, $Destination_loc, $Starting_time, $End_time, $Receiver_address, $note, $mode)
    {
        global $database, $user;
        $response = array();
        $user_id = $user->user_info["user_id"];
        if ($user->user_type != "ADMIN") {
            array_push($response, array(
                "server_response" => "post_shipment_error",
                "error" => "user is not admin",
            ));
            echo json_encode($response);
            exit();
        }

        $result = $database->database_query("INSERT INTO shipment(shipment_id, user_id, delivery_man_id, receiver_address, starting_location, destination_location, starting_time, end_time, created_at, shipment_status, delivery_man_starting_location, path, current_location,note,mode) VALUES('', '$user_id', '', '$Receiver_address', '$Starting_loc', '$Destination_loc', '$Starting_time', '$End_time', '', 'open', '', '', '','$note','$mode')");
        if ($result != 0) {
            array_push($response, array(
                "server_response" => "post_shipment_success",
            ));
            echo json_encode($response);
        } else {
            array_push($response, array(
                "server_response" => "post_shipment_error",
                "error" => "Can't add shipment ",
            ));
            echo json_encode($response);
        }
    }

    function editShipment($shipment_id, $Starting_loc, $Destination_loc, $Starting_time, $End_time, $Receiver_address, $note, $mode)
    {
        global $database, $user;
        $response = array();
        $user_id = $user->user_info["user_id"];
        if ($user->user_type != "ADMIN") {
            array_push($response, array(
                "server_response" => "post_shipment_error",
                "error" => "user is not admin",
            ));
            echo json_encode($response);
            exit();
        }
        $update_query = "UPDATE shipment set starting_location = '$Starting_loc',destination_location = '$Destination_loc',starting_time = '$Starting_time',end_time = '$End_time',receiver_address = '$Receiver_address',note = '$note',mode = '$mode' WHERE shipment_id = " . $shipment_id . " and user_id = " . $user_id;
        if ($database->database_query($update_query)) {
            $update_affected_rows = $database->database_affected_rows($database->database_connection);
            if ($update_affected_rows >= 1) {
                array_push($response, array(
                    "server_response" => "edit_shipment_success",
                ));
                echo json_encode($response);
            } else if ($update_affected_rows == 0) {
                array_push($response, array(
                    "server_response" => "edit_shipment_error",
                    "error" => "NO_CHANGE"
                ));
                echo json_encode($response);
            } else {
                array_push($response, array(
                    "server_response" => "edit_shipment_error",
                    "error" => "can't update"
                ));
                echo json_encode($response);
            }
        } else {
            array_push($response, array(
                "server_response" => "edit_shipment_error",
                "error" => "can't update"
            ));
            echo json_encode($response);
        }
    }

    function deleteShipment($shipment_id)
    {
        global $database, $user;
        $response = array();
        $user_id = $user->user_info["user_id"];
        $shipment_detail_selection_result = $database->database_query("select * from shipment where shipment_id='$shipment_id'");
        $shipment_detail_final = $database->database_fetch_array($shipment_detail_selection_result);
        if ($database->database_affected_rows($database->database_connection) > 0) {
            if ($shipment_detail_final['user_id'] != $user_id) {
                echo json_encode(array("server_response" => "delete_shipment_error",
                    "error" => "You can not delete shipments posted by others!"));
                exit();
            } elseif ($shipment_detail_final['shipment_status'] != "open" && $shipment_detail_final['shipment_status'] != "delivered") {
                echo json_encode(array("server_response" => "delete_shipment_error",
                    "error" => "You can not delete on progress shipments!"));
                exit();
            }
        } else {
            echo json_encode(array("server_response" => "delete_shipment_error",
                "error" => "There is no such shipment!"));
            exit();
        }
        $delete_result = $database->database_query("DELETE FROM shipment WHERE shipment_id ='" . $shipment_id . "' and user_id = " . $user_id);
        if ($delete_result != 0) {
            array_push($response, array(
                "server_response" => "delete_shipment_success",
            ));
            echo json_encode($response);
        } else {
            array_push($response, array(
                "server_response" => "delete_shipment_error",
            ));
            echo json_encode($response);
        }
    }

    function listShipmentForAdmin($limit, $offset, $status)
    {
        global $database, $user;
        $response = array();
        $user_id = $user->user_info["user_id"];
        if ($user->user_type != "ADMIN") {
            array_push($response, array(
                "server_response" => "list_shipment_error",
                "error" => "user is not admin",
            ));
            echo json_encode($response);
            exit();
        }
        if ($limit == "") {
            if ($status == "All") {
                $user_selection_query = "SELECT * FROM shipment WHERE user_id='$user_id' LIMIT 10 ";
            } else {
                $user_selection_query = "SELECT * FROM shipment WHERE user_id='$user_id'and shipment_status='$status' LIMIT 10 ";
            }
        } else {
            if ($status == "All") {
                $user_selection_query = "SELECT * FROM shipment WHERE user_id='$user_id' LIMIT $limit OFFSET $offset ";
            } else {
                $user_selection_query = "SELECT * FROM shipment WHERE user_id='$user_id'and shipment_status='$status' LIMIT $limit OFFSET $offset ";
            }
        }
        $user_selection_result = $database->database_query($user_selection_query);
        if ($user_selection_result) {
            $deliveryman_count = $database->database_num_rows($user_selection_result);
            if ($deliveryman_count > 0) {
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
            $response = array();
            array_push($response, array(
                "server_response" => "list_shipment_error",
                "error" => "No data",
            ));
            echo json_encode($response);
        }
    }

    function getShipmentStatus($shipment_id)
    {
        global $database, $user;
        $response = array();
        $user_id = $user->user_info["user_id"];
        if ($user->user_type != "ADMIN") {
            array_push($response, array(
                "server_response" => "get_shipment_status_error",
                "error" => "user is not admin",
            ));
            echo json_encode($response);
            exit();
        }
        $get_shipment_query = "SELECT * FROM shipment WHERE user_id='$user_id'and shipment_id='$shipment_id'";
        $get_shipment_result = $database->database_query($get_shipment_query);
        if ($database->database_num_rows($get_shipment_result) > 0) {
            $row = $database->database_fetch_assoc($get_shipment_result);
            $row['server_response'] = "get_shipment_status_success";
            array_push($response, $row);
            echo json_encode($response);
        } else {
            array_push($response, array(
                "server_response" => "get_shipment_status_error",
                "error" => "No data",
            ));
            echo json_encode($response);
        }
    }
}