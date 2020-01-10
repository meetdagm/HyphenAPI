<?php
/**
 * Created by PhpStorm.
 * User: Kaleab Yalewdeg
 * Date: 6/13/2019
 * Time: 12:27 PM
 */

class User
{
    // region declare user variables
    var $isError; // determine whether error exists or not (0 - error, 1 - no error)
    var $errorMessage; // contains relevant error message
    var $userExists; // determine whether user exists or not (0 - user don't exist, 1 - user exists)
    var $userData; // contains user's information
    var $profileInfo; // contains user's profile
    var $userSettingInfo; // contains user's settings
    var $userSalt; // contains salt number that is used to encrypt user's password
    //endregion

    // region user constructor
    // THIS METHOD SETS INITIAL VARS SUCH AS USER INFO AND LEVEL INFO
    // INPUT: $user_unique (OPTIONAL) REPRESENTING AN ARRAY:
    //		$user_unique[0] REPRESENTS THE USER'S ID (user_id)
    //		$user_unique[1] REPRESENTS THE USER'S USERNAME (user_username)
    //		$user_unique[2] REPRESENTS THE USER'S EMAIL (user_email)
    //	    $select_fields (OPTIONAL) REPRESENTING AN ARRAY:
    //		$select_fields[0] REPRESENTS THE FIELDS TO SELECT FROM THE SE_USERS TABLE
    //		$select_fields[1] REPRESENTS THE FIELDS TO SELECT FROM THE SE_PROFILES TABLE (QUERY WILL NOT RUN AT ALL IF VALUE IS LEFT BLANK)
    //		$select_fields[2] REPRESENTS THE FIELDS TO SELECT FROM THE SE_LEVELS TABLE (QUERY WILL NOT RUN AT ALL IF VALUE IS LEFT BLANK)
    //		$select_fields[3] REPRESENTS THE FIELDS TO SELECT FROM THE SE_SUBNETS TABLE (QUERY WILL NOT RUN AT ALL IF VALUE IS LEFT BLANK)
    //
    // OUTPUT: NONE
    function __construct($userUnique = Array('0', '', ''), $fieldsToSelect = Array('*', '*', '*', '*'))
    {
        global $database;
        $this->isError = 0;
        $this->errorMessage = "";
        $this->userExists = 0;
        $this->userData['user_id'] = 0;

        // VERIFY USER_ID/USER_USERNAME/USER_EMAIL IS VALID AND SET APPROPRIATE OBJECT VARIABLES
        if ($userUnique[0] != 0 | $userUnique[1] != '' | $userUnique[2] != '') {
            // set user name and email to lowercase
            $loginInfo = strtolower($userUnique[2]);
            // search user on database
            $user = $database->dbQuery("SELECT $fieldsToSelect[0] FROM users WHERE user_id = $userUnique[0] OR LOWER(user_email) ='$loginInfo' ");
            //check weather user is found or not

            if ($database->dbNumRows($user) == 1) {

                $this->userExists = 1;
                $this->userData = $database->dbFetchAssoc($user);
                // set user salt
                $this->userSalt = "$1$" . $this->userData['user_code'] . "$";
            }
        }

    }
    // endregion

    //region create new user
    function createUser($fullname, $email, $password, $profile_pic_url = NULL)
    {
        global $database;
        $crypt_password = md5($password);
        $email_validation_response = $this->validate_email($email);

        if ($email_validation_response == "INCOMPLETE_FIELD") {
            return "Email is not provided!";

        } elseif ($email_validation_response == "INVALID_EMAIL") {
            return "Invalid email provided!";

        } elseif ($email_validation_response == "EMAIL_TAKEN") {
            return "The email you entered exists in the system!";

        } elseif ($email_validation_response == "EMAIL_VALID") {
            $database->dbQuery("INSERT INTO users (
                                  full_name,
                                  user_email,
                                  password,
                                  profile_pic_url
                                  ) VALUES (
                                  '$fullname',
                                  '$email',
                                  '$crypt_password',
                                  '$profile_pic_url'
                                   )");
            // get user information
            $user_id = $database->dbInsertId();
            $this->userData = $database->dbFetchAssoc($database->dbQuery("SELECT * FROM users WHERE user_id='$user_id'"));
            return "registration_success";

        }
        //endregion
    }
    //endregion

    //region E-mail validation
    function validate_email($email) //user_account
    {
        global $database, $setting, $lang_user_class, $lang_error_message;
        if (str_replace(" ", "", $email) == "") {
            $this->isError = 1;
            $this->errorMessage = "INCOMPLETE_FIELD";
            return "INCOMPLETE_FIELD";
        }
        if (!is_email_address($email)) {
            $this->isError = 1;
            $this->errorMessage = "INVALID_EMAIL";
            return "INVALID_EMAIL";
            exit();
        }

        $lowercase_email = strtolower($email);
        $email_query = $database->dbQuery("SELECT user_email FROM users WHERE LOWER(user_email)='$lowercase_email' LIMIT 1");
        if ($database->dbNumRows($email_query) != 0) {
            $this->isError = 1;
            $this->errorMessage = "EMAIL_TAKEN";
            return "EMAIL_TAKEN";
            exit();
        } else {
            return "EMAIL_VALID";
        }
    }
    //endregion

    //region login
    function login($loginInfo, $loginPassword)
    {
        global $database, $setting;
        $this->__construct(Array(0, "", $loginInfo));
        $loginPassword = base64_decode($loginPassword);
        $loginPassword = md5($loginPassword);
        $current_time = time();
        if ($this->userExists == 0) {
            $this->isError = 1;
            $this->errorMessage = "The login details you provided were invalid. Please try again.";
        } elseif (str_replace(" ", "", $loginPassword) == "" || $loginPassword != $this->userData['password']) {
            $this->isError = 1;
            $this->errorMessage = "The login details you provided were invalid. Please try again.";
            // check if user is enabled
        } elseif ($this->userData['user_verified'] == 0 & $setting['setting_signup_verify'] != 0) {
            $this->isError = 1;
            $this->errorMessage = "You have not yet verified your email address.";
            // initiate login and encrypt cookies
        } else {
//            $database->dbQuery("UPDATE users SET user_lastlogindate=$current_time, user_logins=user_logins+1 WHERE user_id='" . $this->userData['user_id'] . "'");
            // Login user
            return $this->setCookies();
        }
        if ($this->isError == 1) {
            return json_encode(array("server_response" => "login_error",
                "error" => $this->errorMessage));
        }
    }
    // endregion

    // region Set user cookies
    // This method generates cookie data for the user (used for session management).
    function setCookies()
    {
        $cookie_id = $cookie_email = $cookie_password = "";
        if (!empty($this->userData) && !empty($this->userData['user_email']) && !empty($this->userData['user_id']) && !empty($this->userData['password'])) {
            $cookie_id = $this->userData['user_id'];
            $cookie_email = base64_encode($this->userData['user_email']);
            $cookie_password = $this->userData['password'];
        }
        $cookie_time = time() + 99999999;
        setcookie("user_id", $cookie_id, $cookie_time, "/");
        setcookie("user_email", $cookie_email, $cookie_time, "/");
        setcookie("user_password", $cookie_password, $cookie_time, "/");
        return json_encode(array(
            "server_response" => "login_success","session_info"=> array("user_id" => $cookie_id,
                "user_email" => $cookie_email,
                "user_password" => $cookie_password)

        ));


    }
    // endregion

    // region check user cookies
    // THIS METHOD VERIFIES LOGIN COOKIES, SETS APPROPRIATE OBJECT VARIABLES
    // INPUT:
    // OUTPUT:
    function checkCookies()
    {
        if (isset($_COOKIE['user_id']) & isset($_COOKIE['user_email']) & isset($_COOKIE['user_password'])) {
            $user_id = $_COOKIE['user_id'];
            $this->__construct(Array($user_id, '', ''));
            // VERIFY USER EXISTS, LOGIN COOKIE VALUES ARE CORRECT, AND EMAIL HAS BEEN VERIFIED - ELSE RESET USER CLASS
            if ($this->userExists == 0 | $_COOKIE['user_email'] != base64_encode($this->userData['user_email']) | $_COOKIE['user_password'] != $this->userData['password']) {
                echo "cookie email: " . $_COOKIE['password'] . " userdata encrypted email : " . $this->userData['password'];
                $this->resetUserData();
            }
        }
    }
    // endregion

    // region reset user data
    // THIS METHOD CLEARS ALL THE CURRENT OBJECT VARIABLES
    function resetUserData()
    {
        $this->isError = 0;
        $this->errorMessage = "";
        $this->userExists = 0;
        $this->userData = "";
        $this->profileInfo = "";
    }
    // endregion

    //region GET USER DETAILS
    function get_user_details($user_id)
    {
        global $database;

        $user_selection_query = "SELECT * FROM users WHERE user_id = $user_id";
        $user_selection_result = $database->dbQuery($user_selection_query);
        if ($user_selection_result) {
            if ($row = $database->dbFetchAssoc($user_selection_result)) {
                return $row;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }
    //endregion

    //region UPDATE USER DATA
    function update_user_account_processor($table_to_update, $tab_to_update, $new_value)
    {
        global $database;

        $update_query = "UPDATE $table_to_update set $tab_to_update = '$new_value' WHERE user_id = " . $this->userData['user_id'];

        if ($database->dbQuery($update_query)) {
            $update_affected_rows = $database->dbAffectedRows($database->db_connection);

            if ($update_affected_rows == 1) {
                return "UPDATE_SUC";
            } else if ($update_affected_rows == 0) {
                return "NO_CHANGE";
            } else {
                return "UPDATE_ERR";
            }
        } else {
            return "UPDATE_ERR";
        }
    }
    //endregion


}