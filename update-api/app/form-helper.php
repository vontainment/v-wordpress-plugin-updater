<?php
/*
* Project: Update API
* Author: Vontainment
* URL: https://vontainment.com
* File: form-helper.php
* Description: WordPress Update API
*/

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

if (isset($_POST['upload_plugin'])) {
    $allowed_extensions = ['zip'];
    $file_extension = strtolower(pathinfo($_FILES['plugin_file']['name'], PATHINFO_EXTENSION));
    if ($_FILES['plugin_file']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['upload_messages'][] = '<p class="error">Error uploading file.</p>';
    } elseif (!in_array($file_extension, $allowed_extensions)) {
        $_SESSION['upload_messages'][] = '<p class="error">Invalid file type. Only .zip files are allowed.</p>';
    } else {
        $plugin_path = '../plugins/' . $_FILES['plugin_file']['name'];
        if (file_exists($plugin_path)) {
            $_SESSION['upload_messages'][] = '<p class="error">File already exists.</p>';
        } else {
            // ...
            $_SESSION['upload_messages'][] = '<p class="success">File uploaded successfully.</p>';
        }
    }
}

// Check if a plugin was deleted
if (isset($_POST['delete_plugin'])) {
    $plugin_name = $_POST['plugin_name'];

    if (file_exists($plugin_name)) {
        if (unlink($plugin_name)) {
            echo '<script>
        alert("Plugin deleted successfully!");
    </script>';
        } else {
            echo '<script>
        alert("Failed to delete plugin file. Please try again.");
    </script>';
        }
    } else {
        echo '<script>
        alert("Plugin file not found. Please try again.");
    </script>';
    }
}