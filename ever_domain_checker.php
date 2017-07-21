<?php

/**
 * Ever Domain Checker 
 */

// require curl multi-requests handler
require('../RollingCurlX/src/rollingcurlx.class.php');

// require domain list
$urls = require('domain_list.php');

/**
 * Callback function
 */
function everDomainChecker($response, $url, $request_info, $user_data, $time) 
{
    echo "The domain {$request_info['url']} is {$request_info['curle_msg']} with a status code of {$request_info['http_code']}: \n------------------\n";
}

// rollingCurlX init
$RCX = new RollingCurlX(20);

// define cURL global options
$RCX->setTimeout(10000); // milliseconds
$RCX->setOptions([
    CURLOPT_NOBODY => true,
    CURLOPT_FOLLOWLOCATION => true
]);

// loop through domain list
foreach ($urls as $url) {
    $RCX->addRequest($url, NULL, 'everDomainChecker');
}

$RCX->execute();
