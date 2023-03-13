<?php
/*
index.php
Version: 1.1
Author: Vontainment
Author URI: https://vontainment.com
*/

// Display the content for logged in users
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>API Admin Page</title>
    <link rel="stylesheet" href="./static/css/index.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <header>
        <img src="./static/img/logo.png" alt="Lego" width="300px" height="60px">
        <button class="logout-btn" onclick="location.href='logout.php'">Logout</button>
    </header>
    <div class="section">
        <h2>Allowed Hosts</h2>
        <?php
        // Check if an entry was updated
        if (isset($_POST['update'])) {
            $hosts_file = '../HOSTS';
            $entries = file($hosts_file, FILE_IGNORE_NEW_LINES);
            $line_number = $_POST['id'];
            $domain = $_POST['domain'];
            $key = $_POST['key'];
            $entries[$line_number] = $domain . ' ' . $key;
            file_put_contents($hosts_file, implode("\n", $entries) . "\n");
        }

        // Check if an entry was deleted
        if (isset($_POST['delete'])) {
            $hosts_file = '../HOSTS';
            $entries = file($hosts_file, FILE_IGNORE_NEW_LINES);
            $line_number = $_POST['id'];
            unset($entries[$line_number]);
            file_put_contents($hosts_file, implode("\n", $entries) . "\n");
        }

        // Check if a new entry was added
        if (isset($_POST['add'])) {
            $hosts_file = '../HOSTS';
            $domain = $_POST['domain'];
            $key = $_POST['key'];
            $new_entry = $domain . ' ' . $key;
            file_put_contents($hosts_file, $new_entry . "\n", FILE_APPEND | LOCK_EX);
        }

        // Display the table of entries
        $hosts_file = '../HOSTS';
        $entries = file($hosts_file, FILE_IGNORE_NEW_LINES);
        ?>
        <div class="row">
            <div class="column">
                <table>
                    <thead>
                        <tr>
                            <th>Domain</th>
                            <th>Key</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($entries as $line_number => $entry) {
                            $fields = explode(' ', $entry);
                            $domain = $fields[0];
                            $key = $fields[1];
                            if ($i % 2 == 0) {
                        ?>
                                <tr>
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?php echo $line_number; ?>">
                                        <td><input type="text" name="domain" value="<?php echo $domain; ?>"></td>
                                        <td><input type="text" name="key" value="<?php echo $key; ?>"></td>
                                        <td>
                                            <input type="submit" name="update" value="Update">
                                            <input type="submit" name="delete" value="Delete">
                                        </td>
                                    </form>
                                </tr>
                        <?php
                            }
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="column">
                <table>
                    <thead>
                        <tr>
                            <th>Domain</th>
                            <th>Key</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($entries as $line_number => $entry) {
                            $fields = explode(' ', $entry);
                            $domain = $fields[0];
                            $key = $fields[1];
                            if ($i % 2 != 0) {
                        ?>
                                <tr>
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?php echo $line_number; ?>">
                                        <td><input type="text" name="domain" value="<?php echo $domain; ?>"></td>
                                        <td><input type="text" name="key" value="<?php echo $key; ?>"></td>
                                        <td>
                                            <input type="submit" name="update" value="Update">
                                            <input type="submit" name="delete" value="Delete">
                                        </td>
                                    </form>
                                </tr>
                        <?php
                            }
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="section">
            <h2>Add Entry</h2>
            <form method="post">
                <div class="form-group">
                    <label for="domain">Domain:</label>
                    <input type="text" name="domain" id="domain" required>
                </div>
                <div class="form-group">
                    <label for="key">Key:</label>
                    <input type="text" name="key" id="key" required>
                </div>
                <div class="form-group">
                    <input type="submit" name="add" value="Add Entry">
                </div>
            </form>
        </div>
    </div>
    <div class="section" id="delete">
        <h2>Plugins</h2>
        <div id="plugins_table">
            <?php include('plugins-table.php'); ?>
        </div>

        <div class="section">
            <h2>Upload Plugin</h2>
            <form method="post" enctype="multipart/form-data" name="upload_plugin_form" action="upload-plugin.php">
                <input type="file" name="plugin_file">
                <input type="submit" name="upload_plugin" value="Upload">
            </form>
            <div id="message"></div>
        </div>
        <script>
            function updatePluginsTable() {
                $.ajax({
                    url: 'plugins-table.php',
                    success: function(data) {
                        $('#plugins_table').html(data);
                    },
                    error: function() {
                        $('#plugins_table').html('<p>Error loading plugins table.</p>');
                    }
                });
            }

            $(document).ready(function() {

                updatePluginsTable();

                $('form[name="delete_plugin_form"]').submit(function(event) {
                    event.preventDefault();
                    var form = $(this);
                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        data: form.serialize(),
                        success: function(data) {
                            $('#message').html(data);
                            updatePluginsTable();
                        },
                        error: function() {
                            $('#message').html('<p>Error deleting plugin.</p>');
                        }
                    });
                    event.stopPropagation(); // Prevent any other event handlers from executing
                    return false; // Prevent default form submission behavior
                });


                $('form[name="upload_plugin_form"]').submit(function(event) {
                    event.preventDefault();
                    var form = $(this);
                    var formData = new FormData(form[0]);
                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            $('#message').html(data);
                            updatePluginsTable();
                        },
                        error: function() {
                            $('#message').html('<p>Error uploading plugin.</p>');
                        }
                    });
                    event.stopPropagation(); // Prevent any other event handlers from executing
                    return false; // Prevent default form submission behavior
                });
            });
        </script>
    </div>
    <div class="section">
        <h2>Access Logs</h2>
        <div class="log-box">
            <?php include '../log-status.php'; ?>
        </div>
    </div>
</body>

</html>