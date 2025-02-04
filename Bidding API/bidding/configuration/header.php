<?php
/**
 * Created by PhpStorm.
 * User: Kaleab Yalewdeg
 * Date: 6/13/2019
 * Time: 12:00 PM
 */

/**
 * PHPIDS
 * Requirements: PHP5, SimpleXML
 *
 * Copyright (c) 2010 PHPIDS group (https://phpids.org)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

require_once __DIR__ . '\..\vendor\autoload.php';

use IDS\Init;
use IDS\Monitor;
use IDS\Log\CompositeLogger;
use IDS\Log\FileLogger;

if (!session_id()) {
    session_start();
}

try {

    /*
    * It's pretty easy to get the PHPIDS running
    * 1. Define what to scan

    *
    * Please keep in mind what array_merge does and how this might interfer
    * with your variables_order settings
    */
    $request = array(
        'REQUEST' => $_REQUEST,
        'GET' => $_GET,
        'POST' => $_POST,
        'COOKIE' => $_COOKIE
    );

    $init = Init::init(dirname(__FILE__) . '\\..\\lib\\IDS\\Config\\Config.ini.php');


    /**
     * You can also reset the whole configuration
     * array or merge in own data
     *
     * This usage doesn't overwrite already existing values
     * $config->setConfig(array('General' => array('filter_type' => 'xml')));
     *
     * This does (see 2nd parameter)
     * $config->setConfig(array('General' => array('filter_type' => 'xml')), true);
     *
     * or you can access the config directly like here:
     */
    $init->config['General']['base_path'] = dirname(__FILE__) . '\\..\\lib\\IDS\\';

    $init->config['General']['use_base_path'] = true;

    $init->config['Caching']['caching'] = 'none';

    // 2. Initiate the PHPIDS and fetch the results
    $ids = new Monitor($init);

    $result = $ids->run($request);

    /*
    * That's it - now you can analyze the results:
    *
    * In the result object you will find any suspicious
    * fields of the passed array enriched with additional info
    *
    * Note: it is moreover possible to dump this information by
    * simply echoing the result object, since IDS_Report implemented
    * a __toString method.
    */
    if (!$result->isEmpty()) {
        echo $result;

        /*
        * The following steps are optional to log the results
        */
        $compositeLog = new CompositeLogger();

        $compositeLog->addLogger(FileLogger::getInstance($init));


        /*
        * Note that you might also use different logging facilities
        * such as IDS\Log\EmailLogger or IDS\Log\DatabaseLogger
        *
        * Just uncomment the following lines to test the wrappers
        */
        /*
        *
        $compositeLog->addLogger(
            IDS\Log\EmailLogger::getInstance($init),
            IDS\Log\DatabaseLogger::getInstance($init)
        );
        */
        $compositeLog->execute($result);
    } else {
        //If you dont want the PHPIDS security measures you can just take this section that is on else{..} and delete all others.
        // development configuration
        ini_set('display_errors', TRUE);
        error_reporting(E_ALL ^ E_NOTICE);

        include "config.php";
        include "classes/database/Database.php";
        include "classes/user/User.php";
        include "classes/bid/Bid.php";
        include "classes/url/Url.php";

        include "functions/global/Functions.php";

// initiate database connection
        $database = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// create a url object
        $url = new Url();
// create a bid object
        $bid = new Bid();
// create a user object and attempt to login user
        $user = new User();

        $user->checkCookies();

    }
} catch (\Exception $e) {
    /*
    * sth went terribly wrong - maybe the
    * filter rules weren't found?
    */
    printf(
        'An error occured: %s',
        $e->getMessage()
    );
}


