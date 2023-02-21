<?php
// Define the path to the HOSTS file and the plugins directory
define('HOSTS_PATH', './HOSTS');
define('PLUGINS_PATH', './plugins');

// Get the domain name, plugin slug, and plugin version from the request
$domain = isset($_GET['domain']) ? $_GET['domain'] : '';
$plugin = isset($_GET['plugin']) ? $_GET['plugin'] : '';
$plugin_version = isset($_GET['version']) ? $_GET['version'] : '';

// Check if the domain exists in the HOSTS file
if ($host_file = @fopen(HOSTS_PATH, 'r')) {
    while (!feof($host_file)) {
        $host = trim(fgets($host_file));
        if ($host === $domain) {
            // The domain exists in the HOSTS file, so check for an updated plugin version
            $plugins = scandir(PLUGINS_PATH);
            foreach ($plugins as $filename) {
                if (strpos($filename, $plugin) === 0) {
                    // The plugin slug matches the beginning of the filename
                    $filename_parts = explode('_', $filename);
                    if (isset($filename_parts[1]) && version_compare($filename_parts[1], $plugin_version, '>')) {
                        // The plugin version is higher than the installed version, so send the link to the zip file
                        $zip_path = PLUGINS_PATH . '/' . $filename;
                        $zip_url = 'http://' . $_SERVER['HTTP_HOST'] . '/plugins/' . $filename;
                        header('Content-Type: application/json');
                        echo json_encode(['zip_url' => $zip_url]);
                        exit();
                    }
                }
            }
            // The plugin version is not higher than the installed version, so return an empty response
            http_response_code(204);
            header('Content-Type: application/json');
            header('Content-Length: 0');
            exit();
        }
    }
    fclose($host_file);
}

// The domain does not exist in the HOSTS file, so return an empty response
http_response_code(204);
header('Content-Type: application/json');
header('Content-Length: 0');
exit();
?>