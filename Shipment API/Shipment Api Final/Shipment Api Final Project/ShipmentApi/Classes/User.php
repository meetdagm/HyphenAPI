<?php


class User
{
    var $isError;
    var $user_exist;
    var $user_info;
    var $user_type;  //0 = Admin_Front_End or 1 = User

    //region Register
    function register($full_name, $email, $password, $profile_pic, $user_type)
    {
        global $database, $verification;
        $crypt_password = crypt($password, "454545");
        if ($verification->emailValidate($email) && $verification->passwordNumValidate($password)) {
            if ($verification->emailTaken($email)) {
                // ADD USER TO USER TABL
                $result = $database->database_query("INSERT INTO user_table(user_id, full_name, e_mail, registration_date, password, profile_pic_url,user_type) VALUES('','$full_name','$email','','$crypt_password','$profile_pic','$user_type')");
                if ($result != 0) {
                    return "registration_success";
                } else if ($result == 0) {
                    $this->isError = 1;
                    return "registration_fail";
                }
            } else {
                return "EMAIL_TAKEN";
            }
        } else {
            return "email_or_password_not_valid";
        }
    }
    //endregion

    //region Login
    function Login($email, $password)
    {
        global $database, $verification;
        $server_response = array();
        $current_time = time();
        if ($verification->emailValidate($email)) {
            $user = $database->database_query("SELECT * FROM user_table  WHERE  LOWER(e_mail)='$email'");
            if ($database->database_num_rows($user) != 0) {
                $this->user_info = $database->database_fetch_assoc($user);
                $crypt_password = crypt($password, "454545");
                if (str_replace(" ", "", $password) == "" || $crypt_password != $this->user_info['password']) {
                    $this->isError = 1;
                    array_push($server_response, array(
                        "server_response" => "login_error",
                        "error" => "incorrect password. Please try again."
                    ));
                    echo json_encode($server_response);
                } else {
                    $this->user_exist = 1;
                    $this->user_type = $this->user_info["user_type"];
                    // UPDATE USER LOGIN INFO
                    $database->database_query("UPDATE user_table SET last_login_date=$current_time WHERE id='" . $this->user_info['user_id'] . "'");
                    // LOG USER IN
                    $this->setUserCookie();
                }
            } else {
                $this->isError = 1;
                array_push($server_response, array(
                    "server_response" => "login_error",
                    "error" => "incorrect email. Please try again."
                ));
                echo json_encode($server_response);
            }
        }

    }
    //endregion

    //region set working_class Cookie
    function setUserCookie()
    {
        //region set cookie data
        $cookie_id = $cookie_email = $cookie_password = "";
        if (!empty($this->user_info) && !empty($this->user_info['e_mail']) && !empty($this->user_info['user_id']) && !empty($this->user_info['password'])) {
            $cookie_id = $this->user_info['user_id'];
            $cookie_email = base64_encode($this->user_info['e_mail']);
            $cookie_password = $this->user_info['password'];
        }
        //endregion

        //region set cookie to browser
        $cookie_time = time() + 99999999;
        setcookie("user_id", $cookie_id, $cookie_time, "/");
        setcookie("user_email", $cookie_email, $cookie_time, "/");
        setcookie("user_password", $cookie_password, $cookie_time, "/");
        $response = array();
        array_push($response, array(
            "server_response" => "success",
            "COOKIE" => "SET",
            "USER_ID" => $cookie_id,
            "USER_EMAIL" => $cookie_email,
            "USER_PASSWORD" => $cookie_password
        ));
        echo json_encode($response);
        //endregion
    }
    //endregion

    //region Check cookie
    function check_cookie()
    {
        global $database, $verification;
        if (isset($_COOKIE['user_id']) & isset($_COOKIE['user_email']) & isset($_COOKIE['user_password'])) {
//            echo "COOKIE DETECTED!<br>";
            // GET USER ROW IF AVAILABLE
            $email = base64_decode($_COOKIE['user_email']);
            $user = $database->database_query("SELECT * FROM user_table  WHERE  LOWER(e_mail)='$email'");
            if ($verification->emailValidate($email)) {
                if ($database->database_num_rows($user) != 0) {
                    $this->user_exist = 1;
                    $this->user_info = $database->database_fetch_assoc($user);
                    $this->user_type = $this->user_info["user_type"];
                }
            }

            // VERIFY USER EXISTS, LOGIN COOKIE VALUES ARE CORRECT, AND EMAIL HAS BEEN VERIFIED - ELSE RESET USER CLASS
            if ($this->user_exist == 0 | $_COOKIE['user_email'] != base64_encode($this->user_info['e_mail']) | $_COOKIE['user_password'] != $this->user_info['password']) {
//                echo "CLEARING USER DATA BECAUSE USER HAS NOT BEEN IDENTIFIED<br>";
                $this->user_clear();
            }
        }

        // IF USER LOGGED IN, UPDATE LAST ACTIVITY
        if ($this->user_exist != 0) {
            $time_current = time();
            $database->database_query("UPDATE user_table SET last_login_date='$time_current' WHERE user_id='" . $this->user_info['user_id'] . "'");
        }
    }

    //endregion

    //region clear user data
    function user_clear()
    {
        $this->user_exist = 0;
        $this->user_info = "";
    }
    //endregion

    //region clear user data
    function logout()
    {
        // CREATE PLAINTEXT USER EMAIL COOKIE WHILE LOGGED OUT
        setcookie("prev_email", $this->user_info['e_mail'], time() + 99999999, "/");

        $this->user_clear();
        $this->setUserCookie();
    }
    //endregion
}
