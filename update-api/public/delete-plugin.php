<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$plugin_name = $_POST['plugin_name'];
$plugin_path = '../plugins/' . $plugin_name;

if (file_exists($plugin_path) && is_file($plugin_path)) {
    unlink($plugin_path);
    echo '<p class="success">Plugin deleted successfully.</p>';
    include 'plugins-table.php'; // replace with actual file name that generates plugins table
    die(); // stop further execution
} else {
    echo '<p class="error">Plugin not found.</p>';
    die(); // stop further execution
}
