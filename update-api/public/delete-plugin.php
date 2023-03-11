<?php
$plugin_name = $_POST['plugin_name'];
$plugin_path = '../plugins/' . $plugin_name;

if (file_exists($plugin_path)) {
    unlink($plugin_path);
    echo '<p class="success">Plugin deleted successfully.</p>';
    include 'plugins-table.php'; // replace with actual file name that generates plugins table
} else {
    echo '<p class="error">Plugin not found.</p>';
}
