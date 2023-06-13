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


// PluginsBox
if (isset($_FILES['plugin_file'])) {
    $allowed_extensions = ['zip'];

    $files = $_FILES['plugin_file'];
    $total_files = count($files['name']);

    for ($i = 0; $i < $total_files; $i++) {
        $file_extension = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
        $plugin_path = '../plugins/' . $files['name'][$i];

        // Extract the unique plugin slug
        $plugin_slug = explode("_", $files['name'][$i])[0];

        // Find and delete any existing plugins with the same slug
        $existing_plugins = glob('../plugins/' . $plugin_slug . '_*');
        foreach ($existing_plugins as $plugin) {
            if (is_file($plugin)) {
                unlink($plugin);
            }
        }

        if ($files['error'][$i] !== UPLOAD_ERR_OK || !in_array($file_extension, $allowed_extensions)) {
            echo '<script>
                alert("Error uploading: ' . $files['name'][$i] . '. Only .zip files are allowed.");
                window.location.href = "index.php#PluginsBox";
            </script>';
        } else {
            if (move_uploaded_file($files['tmp_name'][$i], $plugin_path)) {
                echo '<script>
                    alert("' . $files['name'][$i] . ' uploaded successfully.");
                    window.location.href = "index.php#PluginsBox";
                </script>';
            } else {
                echo '<script>
                    alert("Error uploading: ' . $files['name'][$i] . '");
                    window.location.href = "index.php#PluginsBox";
                </script>';
            }
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
                window.location.href = "index.php#PluginsBox";
            </script>';
        } else {
            echo '<script>
                alert("Failed to delete plugin file. Please try again.");
                window.location.href = "index.php#PluginsBox";
            </script>';
        }
    }
}

// ThemeBox
if (isset($_FILES['theme_file'])) {
    $allowed_extensions = ['zip'];

    $files = $_FILES['theme_file'];
    $total_files = count($files['name']);

    for ($i = 0; $i < $total_files; $i++) {
        $file_extension = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
        $theme_path = '../themes/' . $files['name'][$i];

        // Extract the unique theme slug
        $theme_slug = explode("_", $files['name'][$i])[0];

        // Find and delete any existing themes with the same slug
        $existing_themes = glob('../themes/' . $theme_slug . '_*');
        foreach ($existing_themes as $theme) {
            if (is_file($theme)) {
                unlink($theme);
            }
        }
        if ($files['error'][$i] !== UPLOAD_ERR_OK || !in_array($file_extension, $allowed_extensions)) {
            echo '<script>
                alert("Error uploading: ' . $files['name'][$i] . '. Only .zip files are allowed.");
                window.location.href = "index.php#ThemesBox";
            </script>';
        } else {
            if (move_uploaded_file($files['tmp_name'][$i], $theme_path)) {
                echo '<script>
                    alert("' . $files['name'][$i] . ' uploaded successfully.");
                    window.location.href = "index.php#ThemesBox";
                </script>';
            } else {
                echo '<script>
                    alert("Error uploading: ' . $files['name'][$i] . '");
                    window.location.href = "index.php#ThemesBox";
                </script>';
            }
        }
    }
}

// Check if a theme was deleted
if (isset($_POST['delete_theme'])) {
    $theme_name = $_POST['theme_name'];

    if (file_exists($theme_name)) {
        if (unlink($theme_name)) {
            echo '<script>
                alert("theme deleted successfully!");
                window.location.href = "index.php#ThemesBox";
            </script>';
        } else {
            echo '<script>
                alert("Failed to delete theme file. Please try again.");
                window.location.href = "index.php#ThemesBox";
            </script>';
        }
    }
}
