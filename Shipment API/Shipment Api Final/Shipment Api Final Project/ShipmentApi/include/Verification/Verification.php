<?php
/**
 * Created by PhpStorm.
 * User: AMANA
 * Date: 11/4/2019
 * Time: 6:08 AM
 */

class Verification
{
    function emailValidate($email)
    {
        global $user;
        $regexp = "/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
        if (!preg_match($regexp, $email)) {
            $user->isError = 1;
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function emailTaken($email)
    {
        global $database, $user;
        $lowercase_email = strtolower($email);
        $email_query = $database->database_query("SELECT e_mail FROM user_table WHERE LOWER(e_mail)='$lowercase_email' LIMIT 1");
        if ($email_query) {
            $email_query_count = $database->database_num_rows($email_query);
            if ($email_query_count > 0) {
                $user->isError = 1;
                return false;
            } else {
                return true;
            }
        }
    }

    function phoneNumValidate($mobile_number)
    {
        $other_elements = array("+", "-", "(", ")", " ");
        $mobile_number = str_replace($other_elements, "", $mobile_number, $count);

        $regexp = "/^[0-9]{9,13}$/";
        if (!preg_match($regexp, $mobile_number)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function passwordNumValidate($password_number)
    {
        global $user;
//        $regexp = "/^[a-zA-Z0-9]{9,30}$/";
//        if (!preg_match($regexp, $password_number)) {
//            $user->isError = 1;
//            return FALSE;
//        } else {
//            return TRUE;
//        }

        if ($password_number != "") {
            return TRUE;
        } else {
            $user->isError = 1;
            return FALSE;
        }
    }

    function format_mobile_phone($mobile_number)
    {
        $final_mobile_number = "";

        $other_elements = array("+", "-", "(", ")", "[", "]", " ");
        $mobile_number = str_replace($other_elements, "", $mobile_number, $count);

        $start_index = 0;//$start_index=1 i=1 $mobile_number=10
        for ($i = 0; $i < strlen($mobile_number); $i++) {
            if ($mobile_number[$i] != 0) {
                if ($mobile_number[$i] == 9) {
                    break;
                }
                $start_index++;
            } else {
                $start_index++;
            }
        }

        for ($j = $start_index; $j < strlen($mobile_number); $j++) {
            $final_mobile_number[$j - $start_index] = $mobile_number[$j];
        }

        $regexp = "/^[0-9]{9}$/";
        if (!preg_match($regexp, $final_mobile_number)) {
            return FALSE;
        } else {
            return $final_mobile_number;
        }
    }

    function numberValidate()
    {

    }

    function userNameValidation()
    {

    }
}