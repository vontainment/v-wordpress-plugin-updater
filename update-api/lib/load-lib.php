<?php
/*
 * Project: Update API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: load-lib.php
 * Description: WordPress Update API
*/

$ip = $_SERVER['REMOTE_ADDR'];
if (is_blacklisted($ip)) {
    // Stop the script and show an error if the IP is blacklisted
    http_response_code(403); // Optional: Set HTTP status code to 403 Forbidden
    echo "Your IP address has been blacklisted. If you believe this is an error, please contact us.";
    exit();
} elseif (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // The user is not logged in, redirect to the login page
    header('Location: login.php');
    exit();
} elseif (isset($_GET['page'])) {
    $page = $_GET['page'];

    $_SESSION['timeout'] = time();

    // Check if $page-helper.php exists
    $helperFile = "../app/helpers/" . $page . "-helper.php";
    if (file_exists($helperFile)) {
        require_once($helperFile);
    }

    // Check if $page-forms.php exists
    $formsFile = "../app/forms/" . $page . "-forms.php";
    if (file_exists($formsFile)) {
        require_once($formsFile);
    }

    // Check if $page.php exists
    $pageFile = "../app/pages/" . $page . ".php";
    if (file_exists($pageFile)) {
        $pageOutput = $pageFile;
    }
}
