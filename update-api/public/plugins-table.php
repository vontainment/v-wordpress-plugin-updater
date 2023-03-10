<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Define plugins directory
$plugins_dir = "../plugins";

// Check if delete plugin form was submitted
if (isset($_POST['delete_plugin'])) {
    // Get plugin name from hidden input field
    $plugin_name = $_POST['plugin_name'];

    // Check if plugin exists in plugins directory
    if (file_exists($plugin_name)) {
        // Attempt to delete the plugin file
        if (unlink($plugin_name)) {
            // Success message
            $message = "Plugin deleted successfully!";
        } else {
            // Error message
            $message = "Failed to delete plugin file. Please try again.";
        }
    } else {
        // Error message
        $message = "Plugin file not found. Please try again.";
    }
}

// Get all plugin files from plugins directory
$plugins = glob($plugins_dir . "/*.zip");

// Check if any plugins were found
if (count($plugins) > 0) {
    $table_html = '<div class="row"><div class="column">
    <table>
        <thead>
            <tr>
                <th>Plugin Name</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>';
    $i = 0;
    foreach ($plugins as $plugin) {
        $plugin_name = basename($plugin);
        if ($i % 2 == 0) {
            $table_html .= '<tr>
            <td>' . $plugin_name . '</td>
            <td>
                <form method="post" name="delete_plugin_form" action="index.php#delete">
                    <input type="hidden" name="plugin_name" value="' . $plugin . '">
                    <input type="submit" name="delete_plugin" value="Delete">
                </form>
            </td>
        </tr>';
        }
        $i++;
    }
    $table_html .= '</tbody></table></div><div class="column"><table>
        <thead>
            <tr>
                <th>Plugin Name</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>';
    $i = 0;
    foreach ($plugins as $plugin) {
        $plugin_name = basename($plugin);
        if ($i % 2 != 0) {
            $table_html .= '<tr>
            <td>' . $plugin_name . '</td>
            <td>
                <form method="post" name="delete_plugin_form" action="index.php#delete">
                    <input type="hidden" name="plugin_name" value="' . $plugin . '">
                    <input type="submit" name="delete_plugin" value="Delete">
                </form>
            </td>
        </tr>';
        }
        $i++;
    }
    $table_html .= '</tbody></table></div></div>';
} else {
    $table_html = "No plugins found.";
}

// Output table HTML
echo $table_html;
