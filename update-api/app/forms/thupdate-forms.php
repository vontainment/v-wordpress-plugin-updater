<?php
/*
* Project: Update API
* Author: Vontainment
* URL: https://vontainment.com
* File: thupdate-form.php
* Description: WordPress Update API
*/
// Handle theme file uploads
if (isset($_FILES['theme_file'])) {
    $allowed_extensions = ['zip'];
    $total_files = count($_FILES['theme_file']['name']);

    for ($i = 0; $i < $total_files; $i++) {
        $file_name = $_FILES['theme_file']['name'][$i];
        $file_tmp = $_FILES['theme_file']['tmp_name'][$i];
        $file_size = $_FILES['theme_file']['size'][$i];
        $file_error = $_FILES['theme_file']['error'][$i];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Extract the unique theme slug
        $theme_slug = explode("_", $file_name)[0];

        // Find and delete any existing themes with the same slug
        $existing_themes = glob(THEMES_DIR . '/' . $theme_slug . '_*');
        foreach ($existing_themes as $theme) {
            if (is_file($theme)) {
                unlink($theme);
            }
        }

        if ($file_error !== UPLOAD_ERR_OK || !in_array($file_extension, $allowed_extensions)) {
            echo '<script>
                alert("Error uploading: ' . $file_name . '. Only .zip files are allowed.");
                window.location.href = "/thupdate";
            </script>';
            exit;
        }

        $theme_path = THEMES_DIR . '/' . $file_name;
        if (move_uploaded_file($file_tmp, $theme_path)) {
            echo '<script>
                alert("' . $file_name . ' uploaded successfully.");
                window.location.href = "/thupdate";
            </script>';
        } else {
            echo '<script>
                alert("Error uploading: ' . $file_name . '");
                window.location.href = "/thupdate";
            </script>';
        }
    }
}

// Check if a theme was deleted
if (isset($_POST['delete_theme'])) {
    $theme_name = $_POST['theme_name'];
    $theme_path = THEMES_DIR . '/' . $theme_name;

    if (file_exists($theme_path)) {
        if (unlink($theme_path)) {
            echo '<script>
                alert("theme deleted successfully!");
                window.location.href = "/thupdate";
            </script>';
        } else {
            echo '<script>
                alert("Failed to delete theme file. Please try again.");
                window.location.href = "/thupdate";
            </script>';
        }
    }
}
