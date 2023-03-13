<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$allowed_extensions = ['zip'];
$file_extension = strtolower(pathinfo($_FILES['plugin_file']['name'], PATHINFO_EXTENSION));

if ($_FILES['plugin_file']['error'] !== UPLOAD_ERR_OK) {
    echo '<p class="error">Error uploading file.</p>';
} elseif (!in_array($file_extension, $allowed_extensions)) {
    echo '<p class="error">Invalid file type. Only .zip files are allowed.</p>';
} else {
    $plugin_path = '../plugins/' . $_FILES['plugin_file']['name'];
    if (file_exists($plugin_path)) {
        echo '<p class="error">File already exists.</p>';
    } else {
        move_uploaded_file($_FILES['plugin_file']['tmp_name'], $plugin_path);
        echo '<p class="success">File uploaded successfully.</p>';
    }
}

// Output messages within the upload-messages div
if (isset($_SESSION['upload_messages'])) {
    echo '<div class="upload-messages">';
    foreach ($_SESSION['upload_messages'] as $message) {
        echo '<p>' . $message . '</p>';
    }
    echo '</div>';
    unset($_SESSION['upload_messages']);
}
