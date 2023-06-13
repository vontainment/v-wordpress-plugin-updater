<?php
/*
 * Project: Update API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: index.php
 * Description: WordPress Update API
*/

session_start();
require_once "../app/auth-helper.php";
require_once "../app/form-helper.php";
?>


<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no" />
    <meta name="robots" content="noindex, nofollow">
    <title>API Admin Page</title>
    <link rel="stylesheet" href="./assets/index.css">
    <!-- Dropzone.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.css" rel="stylesheet" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Dropzone.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.js"></script>
</head>
<script src="/assets/scripts.js"></script>
</head>

<body>
    <header>
        <img src="./assets/logo.png" alt="Lego" width="200px" height="40px">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <button class="logout-btn" type="submit" name="logout">Logout</button>
        </form>
    </header>

    <!-- Tab links -->
    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'HostBox')">Manage Sites</button>
        <button class="tablinks" onclick="openTab(event, 'PluginsBox')">Manage Plugins</button>
        <button class="tablinks" onclick="openTab(event, 'ThemesBox')">Manage Themes</button>
        <button class="tablinks" onclick="openTab(event, 'AccessLogs')">Access Logs</button>
    </div>

    <!-- Tab content -->
    <div id="HostBox" class="tabcontent">
        <?php require_once "../app/host-box.inc.php"; ?>
    </div>
    <div id="PluginsBox" class="tabcontent">
        <?php require_once "../app/plugins-box.inc.php"; ?>
    </div>
    <div id="ThemesBox" class="tabcontent">
        <?php require_once "../app/themes-box.inc.php"; ?>
    </div>
    <div id="AccessLogs" class="tabcontent">
        <div class="section">
            <h2>Plugin Logs</h2>
            <div class="log-box">
                <?php include '../plugin-log.php'; ?>
            </div>
            <h2>Theme Logs</h2>
            <div class="log-box">
                <?php include '../theme-log.php'; ?>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy;
            <?php echo date("Y"); ?> Vontainment. All Rights Reserved.
        </p>
    </footer>
</body>

</html>