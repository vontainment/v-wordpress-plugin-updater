<?php
if ($_FILES['plugin_file']['error'] !== UPLOAD_ERR_OK) {
    echo '<p class="error">Error uploading file.</p>';
} else {
    $plugin_path = '../plugins/' . $_FILES['plugin_file']['name'];
    if (file_exists($plugin_path)) {
        echo '<p class="error">File already exists.</p>';
    } else {
        move_uploaded_file($_FILES['plugin_file']['tmp_name'], $plugin_path);
        echo '<p class="success">File uploaded successfully.</p>';
        include 'plugins-table.php'; // replace with actual file name that generates plugins table
    }
}
