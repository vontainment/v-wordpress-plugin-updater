<?php
/*
WP Plugin Update API
Version: 1.1
Author: Vontainment
Author URI: https://vontainment.com
*/

// Check if the user is logged in
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Validate the login
        $username = $_POST['username'];
        $password = $_POST['password'];
        $valid_username = 'vontainment';
        $valid_password = 'password';
        if ($username === $valid_username && $password === $valid_password) {
            $_SESSION['logged_in'] = true;
            header('Location: index.php');
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
?>

        <html>

        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />
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
            </div>
        </body>

        </html>
<?php
    }
} else {
    // User is already logged in, redirect them to the homepage
    header('Location: index.php');
    exit();
}
?>