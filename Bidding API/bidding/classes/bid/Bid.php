<?php
/**
 * Created by PhpStorm.
 * User: hi
 * Date: 10/31/2019
 * Time: 11:17 PM
 */

class Bid
{
    function post_bid($bid_title, $bid_description, $price, $bid_picture_url, $expiration_date_time)
    {
        global $database, $user;
        $user_id = $user->userData['user_id'];
        $database->dbQuery("INSERT INTO bids (
                                  user_id,bid_title,
                                  bid_description,
                                  price,
                                  bid_pic_url,
                                  expiry_date
                                  ) VALUES ('$user_id',
                                  '$bid_title',
                                  '$bid_description',
                                  '$price',
                                  '$bid_picture_url',
                                  '$expiration_date_time'
                                   )");
        if ($database->dbAffectedRows($database->db_connection) != 0) {
            echo json_encode(array("server_response" => "bid_post_success"));
        } else {
            echo json_encode(array("server_response" => "bid_post_error",
                "error" => "Unknown error!"));
        }
    }

    function edit_bid($bid_id, $bid_title, $bid_description, $price, $bid_picture_url, $expiration_date_time)
    {
        global $database, $user;
        $user_id = $user->userData['user_id'];
        $bid_detail_selection_result = $database->dbQuery("select * from bids where bid_id='$bid_id'");
        $bid_detail_final = $database->dbFetchArray($bid_detail_selection_result);
        if ($bid_detail_final['user_id'] != $user_id) {
            echo json_encode(array("server_response" => "edit_bid_error",
                "error" => "You can not edit bids posted by others!"));
            exit();
        } elseif ($bid_detail_final['status'] != 1) {
            echo json_encode(array("server_response" => "edit_bid_error",
                "error" => "You can not edit closed bids!"));
            exit();
        }
        $database->dbQuery("update bids SET bid_title='$bid_title',bid_description='$bid_description',price='$price',bid_pic_url='$bid_picture_url',expiry_date='$expiration_date_time' WHERE bid_id='$bid_id'");
        if ($database->dbAffectedRows($database->db_connection) != 0) {
            echo json_encode(array("server_response" => "edit_bid_success"));
        } else {
            echo json_encode(array("server_response" => "edit_bid_error",
                "error" => "No Changes Made!!"));
        }
    }

    function delete_bid($bid_id)
    {
        global $database, $user;
        $user_id = $user->userData['user_id'];
        $bid_detail_selection_result = $database->dbQuery("select * from bids where bid_id='$bid_id'");
        $bid_detail_final = $database->dbFetchArray($bid_detail_selection_result);
        if ($database->dbAffectedRows($database->db_connection) > 0) {
            if ($bid_detail_final['user_id'] != $user_id) {
                echo json_encode(array("server_response" => "delete_bid_error",
                    "error" => "You can not delete bids posted by others!"));
                exit();
            } elseif ($bid_detail_final['status'] != 1) {
                echo json_encode(array("server_response" => "delete_bid_error",
                    "error" => "You can not delete closed bids!"));
                exit();
            }
            $database->dbQuery("DELETE from bids WHERE bid_id='$bid_id'");
            if ($database->dbAffectedRows($database->db_connection) != 0) {
                echo json_encode(array("server_response" => "delete_bid_success"));
            } else {
                echo json_encode(array("server_response" => "delete_bid_error",
                    "error" => "Unknown error!"));
            }
        } else {
            echo json_encode(array("server_response" => "delete_bid_error",
                "error" => "There is no such bid!"));
            exit();
        }
    }

    function delete_bidding_info($bidding_id)
    {
        global $database, $user;
        $user_id = $user->userData['user_id'];
        $bidding_detail_selection_result = $database->dbQuery("select * from bidding where bidding_id='$bidding_id'");
        $bidding_detail_final = $database->dbFetchArray($bidding_detail_selection_result);
        if ($database->dbAffectedRows($database->db_connection) > 0) {
            if ($bidding_detail_final['status'] == "2") {
                echo json_encode(array("server_response" => "delete_bidding_info_error",
                    "error" => "You can not delete approved bidding!"));
                exit();
            }
            if ($bidding_detail_final['user_id'] != $user_id) {
                echo json_encode(array("server_response" => "delete_bidding_info_error",
                    "error" => "You can only delete your bidding!"));
                exit();
            }
            $database->dbQuery("DELETE from bidding WHERE bidding_id='$bidding_id' AND user_id='$user_id'");
            if ($database->dbAffectedRows($database->db_connection) > 0) {
                echo json_encode(array("server_response" => "delete_bidding_info_success"));
            } else {
                echo json_encode(array("server_response" => "delete_bidding_info_error",
                    "error" => "Can not delete this bidding!"));
            }
        } else {
            echo json_encode(array("server_response" => "delete_bidding_info_error",
                "error" => "There is no such bidding!"));
            exit();
        }
    }

    public function view_bids($limit, $offset, $bid_status)
    {
        global $database;
        if ($limit == "") {
            if ($bid_status == "") {
                $bids_selection_query = "SELECT * FROM bids LIMIT 10 ";
            } else {
                $bids_selection_query = "SELECT * FROM bids WHERE status = '$bid_status' LIMIT 10 ";
            }
        } else {
            if ($bid_status == "") {
                $bids_selection_query = "SELECT * FROM bids LIMIT $limit OFFSET $offset ";
            } else {
                $bids_selection_query = "SELECT * FROM bids WHERE status = '$bid_status' LIMIT $limit OFFSET $offset ";
            }
        }
        $bids_selection_result = $database->dbQuery($bids_selection_query);
        $bids = Array();
        if ($bids_selection_result) {
            while ($final_result_1 = $database->dbFetchArray($bids_selection_result)) {
                array_push($bids, array(
                    "bid_id" => $final_result_1['bid_id'],
                    "user_id" => $final_result_1['user_id'],
                    "bid_title" => $final_result_1['bid_title'],
                    "bid_description" => $final_result_1['bid_description'],
                    "bid_pic_url" => $final_result_1['bid_pic_url'],
                    "price" => $final_result_1['price'],
                    "posted_time" => $final_result_1['posted_time'],
                    "expiry_date" => $final_result_1['expiry_date'],
                    "status" => $final_result_1['status'],
                    "rating" => $final_result_1['rating']
                ));
            }
            return $bids;
        }
    }

    public function view_my_bids()
    {
        global $database, $user;
        $user_id = $user->userData['user_id'];
        $bids_selection_query = "SELECT * FROM bids WHERE user_id='$user_id'";
        $biddings_selection_query = "SELECT * FROM bidding WHERE user_id='$user_id'";
        $bids_selection_result = $database->dbQuery($bids_selection_query);
        $biddings_selection_result = $database->dbQuery($biddings_selection_query);
        $bids = Array();
        $biddings = Array();
        if ($bids_selection_result) {
            while ($final_result_1 = $database->dbFetchArray($bids_selection_result)) {

                array_push($bids, array(
                    "bid_id" => $final_result_1['bid_id'],
                    "user_id" => $final_result_1['user_id'],
                    "bid_title" => $final_result_1['bid_title'],
                    "bid_description" => $final_result_1['bid_description'],
                    "bid_pic_url" => $final_result_1['bid_pic_url'],
                    "price" => $final_result_1['price'],
                    "posted_time" => $final_result_1['posted_time'],
                    "expiry_date" => $final_result_1['expiry_date'],
                    "status" => $final_result_1['status'],
                    "rating" => $final_result_1['rating']
                ));
            }

        }
        if ($biddings_selection_result) {
            while ($final_bidding_result = $database->dbFetchArray($biddings_selection_result)) {
                array_push($biddings, array(
                    "bidding_id" => $final_bidding_result['bidding_id'],
                    "bid_id" => $final_bidding_result['bid_id'],
                    "user_id" => $final_bidding_result['user_id'],
                    "proposed_price" => $final_bidding_result['proposed_price'],
                    "description" => $final_bidding_result['description']
                ));
            }

        }
        echo json_encode(array("server_response" => "list_my_bids_success",
            "posted" => $bids,
            "participated" => $biddings));

    }


    function view_single_bid_detail($bid_id, $limit, $offset)
    {
        global $database, $user;
        $bid_selection_query = "SELECT * FROM bids WHERE bid_id='$bid_id'";
        if ($limit == "") {
            $bidding_selection_query = "SELECT * FROM bidding WHERE bid_id= '$bid_id' LIMIT 10 ";
        } else {
            $bidding_selection_query = "SELECT * FROM bidding WHERE bid_id= '$bid_id' LIMIT $limit OFFSET $offset ";
        }
        $bidding_selection_result = $database->dbQuery($bidding_selection_query);
        $bid_selection_result = $database->dbQuery($bid_selection_query);

        if ($database->dbNumRows($bid_selection_result) > 0) {
            $final_bid_result = $database->dbFetchArray($bid_selection_result);
            if ($database->dbNumRows($bidding_selection_result) > 0) {
                $biddings = Array();
                while ($final_bidding_result = $database->dbFetchArray($bidding_selection_result)) {
                    array_push($biddings, array(
                        "bidding_id" => $final_bidding_result['bidding_id'],
                        "bid_id" => $final_bidding_result['bid_id'],
                        "user_id" => $final_bidding_result['user_id'],
                        "proposed_price" => $final_bidding_result['proposed_price'],
                        "description" => $final_bidding_result['description']
                    ));
                }
            }
            echo json_encode(array("server_response" => "view_single_bid_success",
                "bid" => array("bid_id" => $final_bid_result['bid_id'],
                    "user_id" => $final_bid_result['user_id'],
                    "bid_title" => $final_bid_result['bid_title'],
                    "bid_description" => $final_bid_result['bid_description'],
                    "bid_pic_url" => $final_bid_result['bid_pic_url'],
                    "price" => $final_bid_result['price'],
                    "posted_time" => $final_bid_result['posted_time'],
                    "expiry_date" => $final_bid_result['expiry_date'],
                    "status" => $final_bid_result['status'],
                    "rating" => $final_bid_result['rating']),
                "bidders" => $biddings));
        }
    }

    function get_bidding_info($bidding_id)
    {
        global $database, $user;
        $bidding_selection_query = "SELECT * FROM bidding WHERE bidding_id='$bidding_id'";
        $bidding_selection_result = $database->dbQuery($bidding_selection_query);
        if ($database->dbNumRows($bidding_selection_result) > 0) {
            $bidding_selection_result = $database->dbFetchArray($bidding_selection_result);
            echo json_encode(array(
                "server_response" => "get_bidding_info_success","bidding_info"=>array("bidding_id" => $bidding_selection_result['bidding_id'],
                    "bid_id" => $bidding_selection_result['bid_id'],
                    "user_id" => $bidding_selection_result['user_id'],
                    "proposed_price" => $bidding_selection_result['proposed_price'],
                    "description" => $bidding_selection_result['description'],
                    "time" => $bidding_selection_result['time'],
                    "status" => $bidding_selection_result['status'])

            ));
        } else {
            echo json_encode(array("server_response" => "get_bidding_info_error",
                "error" => "No such bidding!"));
        }

    }


    function add_bidding($bid_id, $proposed_price, $bidding_description)
    {
        global $database, $user;
        $user_id = $user->userData['user_id'];
        $bidding_selection_result = $database->dbQuery("SELECT * FROM bidding WHERE bid_id='$bid_id' AND user_id='$user_id'");
        if ($database->dbNumRows($bidding_selection_result) > 0) {
            echo json_encode(array("server_response" => "bid_error",
                "error" => 'You can not bid again, you can only edit the bid that you proposed before!'));
            exit();
        } else {
            if ($bidding_description == "") {
                $user->isError = 1;
                $user->errorMessage = "description is not provided";
            } elseif ($proposed_price == "") {
                $user->isError = 1;
                $user->errorMessage = "price is not provided";
            }
            $user->errorMessage = "";
            if ($user->isError == 1) {
                echo json_encode(array("server_response" => "bid_error",
                    "error" => $user->errorMessage));
                exit();
            } else {
                $database->dbQuery("INSERT INTO bidding (
                                  bid_id,user_id,
                                  proposed_price,
                                  description
                                  ) VALUES ('$bid_id',
                                  '$user_id',
                                  '$proposed_price',
                                  '$bidding_description'
                                   )");
            }
        }

        if ($database->dbAffectedRows($database->db_connection) > 0) {
            echo json_encode(array("server_response" => "bidding_success"));
        } else {
            echo json_encode(array("server_response" => "bidding_error",
                "error" => "Unknown error!"));
        }
    }

    function edit_bidding($bidding_id, $proposed_price, $bidding_description)
    {
        global $database, $user;
        $user_id = $user->userData['user_id'];
        $bidding_selection_result = $database->dbQuery("SELECT * FROM bidding WHERE bidding_id='$bidding_id' AND user_id='$user_id'");
        if ($database->dbNumRows($bidding_selection_result) > 0) {
            $bidding_infos = $database->dbFetchArray($bidding_selection_result);
            $bidding_id = $bidding_infos['bidding_id'];
            if ($proposed_price != "" && $bidding_description != "") {
                $database->dbQuery("UPDATE bidding SET 
                                  proposed_price='$proposed_price',
                                  description='$bidding_description'
                                  WHERE bidding_id='$bidding_id' AND user_id='$user_id'");
            } elseif ($proposed_price != "" && $bidding_description == "") {
                $database->dbQuery("UPDATE bidding SET 
                                  proposed_price='$proposed_price' 
                                  WHERE bidding_id='$bidding_id' AND user_id='$user_id'");
            } elseif ($proposed_price == "" && $bidding_description != "") {
                $database->dbQuery("UPDATE bidding SET 
                                  description='$bidding_description'
                                  WHERE bidding_id='$bidding_id' AND user_id='$user_id'");
            } elseif ($proposed_price == "" && $bidding_description == "") {
                echo json_encode(array("server_response" => "edit_bidding_error",
                    "error" => "No changes made!"));
            }
        } else {
            echo json_encode(array("server_response" => "edit_bidding_error",
                "error" => "There is no such bidding or you're trying to edit other's bidding!"
            ));
            exit();
        }

        if ($database->dbAffectedRows($database->db_connection) > 0) {
            echo json_encode(array("server_response" => "edit_bidding_success"));
        } else {
            echo json_encode(array("server_response" => "edit_bidding_error",
                "error" => "No changes made!"));
        }
    }

    function approve_bidding($bidding_id)
    {
        global $database, $user;
        $user_id = $user->userData['user_id'];
        $bidding_detail_selection_result = $database->dbQuery("select * from bidding where bidding_id='$bidding_id'");
        if ($database->dbNumRows($bidding_detail_selection_result) > 0) {
            $bidding_selection_result = $database->dbFetchArray($bidding_detail_selection_result);
            $bid_id = $bidding_selection_result['bid_id'];
            $bid_detail_selection_result = $database->dbQuery("select * from bids where bid_id='$bid_id' AND status='1'");
            if ($database->dbNumRows($bid_detail_selection_result) > 0) {
                $bid_selection_result = $database->dbFetchArray($bid_detail_selection_result);
                $bid_poster_id = $bid_selection_result['user_id'];
                if ($bid_poster_id == $user_id) {
                    $database->dbQuery("update bidding SET status='2' WHERE bidding_id='$bidding_id'");
                    if ($database->dbAffectedRows($database->db_connection) > 0) {
                        $database->dbQuery("update bids SET status='2' WHERE bid_id='$bid_id'");
                        if ($database->dbAffectedRows($database->db_connection) > 0) {
                            echo json_encode(array("server_response" => "approve_bidding_success"));
                            exit();
                        } else {
                            echo json_encode(array("server_response" => "approve_bidding_error",
                                "error" => "The bid is closed before!"));
                        }
                    } else {
                        echo json_encode(array("server_response" => "approve_bidding_error",
                            "error" => "The bidding is approved before!"));
                    }

                } else {
                    echo json_encode(array("server_response" => "approve_bidding_error",
                        "error" => "You can not approve bid posted by others!"));
                }

            } else {
                echo json_encode(array("server_response" => "approve_bidding_error",
                    "error" => "Sorry, can't approve! Check the status of your bid if it is closed and/or the status of your bidding if it is already approved!"));
            }
        } else {
            echo json_encode(array("server_response" => "approve_bidding_error",
                "error" => "No such bidding!"));
        }

    }

    public function rate_bidder($bid_id, $rate_value)
    {
        global $database, $user;
        $user_id = $user->userData['user_id'];
        $bid_detail_selection_result = $database->dbQuery("select * from bids where bid_id='$bid_id' ");
        if ($database->dbNumRows($bid_detail_selection_result) > 0) {
            $bid_selection_result = $database->dbFetchArray($bid_detail_selection_result);
            $bid_id = $bid_selection_result['bid_id'];
            $bid_poster_id = $bid_selection_result['user_id'];
            $status = $bid_selection_result['status'];
            if ($bid_poster_id != $user_id) {
                echo json_encode(array("server_response" => "rate_error",
                    "error" => "You can not rate bid posted by others!"));
                exit();
            }elseif ($status != '3') {
                echo json_encode(array("server_response" => "rate_error",
                    "error" => "You can not rate bidder before completion"));
                exit();
            }  elseif ($bid_poster_id == $user_id && $status == '3') {
                $database->dbQuery("update bids SET rating='$rate_value' WHERE bid_id='$bid_id'");
                if ($database->dbAffectedRows($database->db_connection) > 0) {
                    echo json_encode(array("server_response" => "rate_success"));
                    exit();
                } else {
                    echo json_encode(array("server_response" => "rate_error",
                        "error" => "No changes made!"));
                }

            }
        }

    }

    public function complete_task($bid_id)
    {
        global $database, $user;
        $user_id = $user->userData['user_id'];
        $bid_detail_selection_result = $database->dbQuery("select * from bids where bid_id='$bid_id' ");
        if ($database->dbNumRows($bid_detail_selection_result) > 0) {
            $bid_selection_result = $database->dbFetchArray($bid_detail_selection_result);
            $bid_id = $bid_selection_result['bid_id'];
            $bid_poster_id = $bid_selection_result['user_id'];
            if ($bid_poster_id != $user_id) {
                echo json_encode(array("server_response" => "rate_bidder_error",
                    "error" => "You can not mark as completed for tasks posted by others!"));
                exit();
            }  else{
                $database->dbQuery("update bids SET status='3' WHERE bid_id='$bid_id'");
                if ($database->dbAffectedRows($database->db_connection) > 0) {
                    echo json_encode(array("server_response" => "complete_task_success"));
                    exit();
                } else {
                    echo json_encode(array("server_response" => "approve_bidding_error",
                        "error" => "The task is completed before!"));
                }

            }
        }
    }

    public function check_expiration($bid_id)
    {
        global $database, $user;
        $user_id = $user->userData['user_id'];
        $bid_detail_selection_result = $database->dbQuery("select * from bids where bid_id='$bid_id' ");

        if ($database->dbNumRows($bid_detail_selection_result) > 0) {
            $bid_selection_result = $database->dbFetchArray($bid_detail_selection_result);
            $bid_id = $bid_selection_result['bid_id'];
            $bid_poster_id = $bid_selection_result['user_id'];
            $expiry_date = $bid_selection_result['expiry_date'];
            echo $expiry_date;
                if(1){
                $database->dbQuery("update bids SET status='3' WHERE bid_id='$bid_id'");
                if ($database->dbAffectedRows($database->db_connection) > 0) {
                    echo json_encode(array("server_response" => "complete_task_success"));
                    exit();
                } else {
                    echo json_encode(array("server_response" => "approve_bidding_error",
                        "error" => "The task is completed before!"));
                }

            }
        }
    }
}