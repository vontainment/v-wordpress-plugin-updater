<?php
/*
* Project: Update API
* Author: Vontainment
* URL: https://vontainment.com
* File: plupdate-form.php
* Description: WordPress Update API
*/
// Handle plugin file uploads
if (isset($_FILES['plugin_file'])) {
    $allowed_extensions = ['zip'];
    $total_files = count($_FILES['plugin_file']['name']);

    for ($i = 0; $i < $total_files; $i++) {
        $file_name = $_FILES['plugin_file']['name'][$i];
        $file_tmp = $_FILES['plugin_file']['tmp_name'][$i];
        $file_size = $_FILES['plugin_file']['size'][$i];
        $file_error = $_FILES['plugin_file']['error'][$i];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Extract the unique plugin slug
        $plugin_slug = explode("_", $file_name)[0];

        // Find and delete any existing plugins with the same slug
        $existing_plugins = glob(PLUGINS_DIR . '/' . $plugin_slug . '_*');
        foreach ($existing_plugins as $plugin) {
            if (is_file($plugin)) {
                unlink($plugin);
            }
        }

        if ($file_error !== UPLOAD_ERR_OK || !in_array($file_extension, $allowed_extensions)) {
            echo '<script>
                alert("Error uploading: ' . $file_name . '. Only .zip files are allowed.");
                window.location.href = "/plupdate";
            </script>';
            exit;
        }

        $plugin_path = PLUGINS_DIR . '/' . $file_name;
        if (move_uploaded_file($file_tmp, $plugin_path)) {
            echo '<script>
                alert("' . $file_name . ' uploaded successfully.");
                window.location.href = "/plupdate";
            </script>';
        } else {
            echo '<script>
                alert("Error uploading: ' . $file_name . '");
                window.location.href = "/plupdate";
            </script>';
        }
    }
}

// Check if a plugin was deleted
if (isset($_POST['delete_plugin'])) {
    $plugin_name = $_POST['plugin_name'];
    $plugin_path = PLUGINS_DIR . '/' . $plugin_name;

    if (file_exists($plugin_path)) {
        if (unlink($plugin_path)) {
            echo '<script>
                alert("Plugin deleted successfully!");
                window.location.href = "/plupdate";
            </script>';
        } else {
            echo '<script>
                alert("Failed to delete plugin file. Please try again.");
                window.location.href = "/plupdate";
            </script>';
        }
    }
}
