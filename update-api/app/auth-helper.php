<?php
/*
WP Plugin Update API
Version: 1.1
Author: Vontainment
Author URI: https://vontainment.com
*/


// Include the config file
require_once '../config.php';

// Check if the user is not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // If the current file is not login.php, redirect to login.php
    $current_file = basename($_SERVER['PHP_SELF']);
    if ($current_file !== 'login.php') {
        header('Location: login.php');
        exit();
    }

    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Validate the login
        $username = $_POST['username'];
        $password = $_POST['password'];
        if ($username === VALID_USERNAME && $password === VALID_PASSWORD) {
            $_SESSION['logged_in'] = true;
            header('Location: index.php');
            exit();
        }
    }
} else {
    // If the user is already logged in and the current file is login.php, redirect to index.php
    $current_file = basename($_SERVER['PHP_SELF']);
    if ($current_file === 'login.php') {
        header('Location: index.php');
        exit();
    }
}

if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
