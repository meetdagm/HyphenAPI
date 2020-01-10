<?php
/**
 * Created by PhpStorm.
 * User: Kaleab Yalewdeg
 * Date: 6/14/2019
 * Time: 9:05 AM
 */

//region Validate Email
// THIS FUNCTION CHECKS IF PROVIDED STRING IS AN EMAIL ADDRESS
// INPUT: $email REPRESENTING THE EMAIL ADDRESS TO CHECK
// OUTPUT: TRUE/FALSE DEPENDING ON WHETHER THE EMAIL ADDRESS IS VALIDLY CONSTRUCTED
function is_email_address($email)
{
    $regexp = "/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
    if (!preg_match($regexp, $email)) {
        return FALSE;
    } else {
        return TRUE;
    }
}

// endregion


