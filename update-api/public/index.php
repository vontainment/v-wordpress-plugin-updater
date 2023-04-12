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
    <script src="/assets/scripts.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <header>
        <img src="./assets/logo.png" alt="Lego" width="200px" height="40px">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <button class="logout-btn" type="submit" name="logout">Logout</button>
        </form>
    </header>
    <?php require_once "../app/host-box.inc.php"; ?>
    <?php require_once "../app/plugins-box.inc.php"; ?>
    <div class="section">
        <h2>Access Logs</h2>
        <div class="log-box">
            <?php include '../log-status.php'; ?>
        </div>
    </div>
</body>

</html>