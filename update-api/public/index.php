<?php
/*
 * Project: Update API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: index.php
 * Description: WordPress Update API
*/

session_start();
require_once '../config.php';
require_once '../lib/waf-lib.php';
require_once '../lib/load-lib.php';
?>


<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no" />
    <meta name="robots" content="noindex, nofollow">
    <title>API Admin Page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.css" rel="stylesheet" />
    <script src="/assets/js/header-scripts.js"></script>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/pages.css">
    <link rel="stylesheet" href="/assets/css/mobile.css">

    <title>Dashboard</title>
</head>

<body>
    <header>
        <div class="logo">
            <a href="/home">
                <img src="/assets/images/logo.png" alt="Logo">
            </a>
        </div>

        <div class="logout-button">
            <form action="/login.php" method="POST">
                <button class="orange-button" type="submit" name="logout">Logout</button>
            </form>
        </div>
    </header>

    <!-- Tab links -->
    <div class="tab">
        <a href="/home"><button class="tablinks <?php if ($_SERVER['REQUEST_URI'] === '/home') echo 'active'; ?>">Manage Hosts</button></a>
        <a href="/plupdate"><button class="tablinks <?php if ($_SERVER['REQUEST_URI'] === '/plupdate') echo 'active'; ?>">Manage Plugins</button></a>
        <a href="/thupdate"><button class="tablinks <?php if ($_SERVER['REQUEST_URI'] === '/thupdate') echo 'active'; ?>">Manage Themes</button></a>
        <a href="/logs"><button class="tablinks <?php if ($_SERVER['REQUEST_URI'] === '/logs') echo 'active'; ?>">View Logs</button></a>
    </div>

    <!-- Tab links -->
    <!-- Tab content -->
    <?php
    if (isset($pageOutput)) {
        require_once $pageOutput;
    }
    ?>
    <!-- Tab content -->
    <footer>
        <p>&copy;
            <?php echo date("Y"); ?> Vontainment. All Rights Reserved.
        </p>
    </footer>
    <script src="/assets/js/footer-scripts.js"></script>
</body>

</html>