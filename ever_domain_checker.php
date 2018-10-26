<?php

if  (in_array  ('curl', get_loaded_extensions())) {
    $msg = 'cURL IS installed on this machine';
}

echo "
 --------------------------------------------------------------------------------
|   _____   _____      _      __  __           _____  __     __  _____   ____    |
|  |_   _| | ____|    / \    |  \/  |         | ____| \ \   / / | ____| |  _ \   |
|    | |   |  _|     / _ \   | |\/| |  _____  |  _|    \ \ / /  |  _|   | |_) |  |
|    | |   | |___   / ___ \  | |  | | |_____| | |___    \ V /   | |___  |  _ <   |
|    |_|   |_____| /_/   \_\ |_|  |_|         |_____|    \_/    |_____| |_| \_\  |
|                               Domain Checker v0.1                              |
|                                       $msg                                     |
 --------------------------------------------------------------------------------
";
sleep(3);

// require curl multi-requests handler
require('../RollingCurlX/src/rollingcurlx.class.php');

// require domain list
$urls = require('domain_list.php');
$urls_count = count($urls);


/**
 * Callback function
 */
function everDomainChecker($response, $url, $request_info, $user_data, $time) 
{
    echo "The domain {$request_info['url']} is {$request_info['curle_msg']} with a status code of {$request_info['http_code']}";
    echo "\n-----------------------------------------\n";
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

// some stats
echo "{$urls_count} URLs have been checked";
