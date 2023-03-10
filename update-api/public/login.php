<?php
/*
WP Plugin Update API
Version: 1.1
Author: Vontainment
Author URI: https://vontainment.com
*/

// Include the config file
require_once '../config.php';

// Check if the user is logged in
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Validate the login
        $username = $_POST['username'];
        $password = $_POST['password'];
        if ($username === VALID_USERNAME && $password === VALID_PASSWORD) {
            $_SESSION['logged_in'] = true;
            header('Location: index.php');
            exit();
        } else {
            $error_msg = "Invalid username or password.";
        }
    }
?>

        <!DOCTYPE html>
        <html lang="en-US">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="robots" content="noindex, nofollow">
            <title>API Update Admin Login</title>
            <link rel="stylesheet" href="./static/css/login.css">
        </head>

        <body>
            <div class="login-box">
                <img src="./static/img/logo.png" alt="Logo" class="logo">
                <h2>API Admin Login</h2>
                <form method="post">
                    <label>Username:</label>
                    <input type="text" name="username"><br><br>
                    <label>Password:</label>
                    <input type="password" name="password"><br><br>
                    <input type="submit" value="Log In">
                </form>
                <?php if (isset($error_msg)) : ?>
                    <div id="error-msg"><?php echo $error_msg; ?></div>
                <?php endif; ?>
            </div>
        </body>

        </html>
<?php
} else {
    // User is already logged in, redirect them to the homepage
    header('Location: index.php');
    exit();
}