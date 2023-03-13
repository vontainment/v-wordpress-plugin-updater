<?php

/**
 * WP Plugin Update API
 * Version: 1.1
 * Author: Vontainment
 * Author URI: https://vontainment.com
 */

// Include the config file
require_once('../config.php');

// Get the domain name, key, plugin slug, and plugin version from the request
$domain = isset($_GET['domain']) ? $_GET['domain'] : '';
$key = isset($_GET['key']) ? $_GET['key'] : '';
$plugin = isset($_GET['plugin']) ? $_GET['plugin'] : '';
$plugin_version = isset($_GET['version']) ? $_GET['version'] : '';

// Check if the domain and key exist in the HOSTS file
if ($host_file = @fopen(HOSTS_ACL, 'r')) {
    while (!feof($host_file)) {
        $line = trim(fgets($host_file));
        list($host, $host_key) = explode(' ', $line);
        if ($host === $domain && $host_key === $key) {
            // The domain and key pair exists in the HOSTS file, so check for an updated plugin version
            $plugins = scandir(PLUGINS_DIR);
            foreach ($plugins as $filename) {
                if (strpos($filename, $plugin) === 0) {
                    // The plugin slug matches the beginning of the filename
                    $filename_parts = explode('_', $filename);
                    if (isset($filename_parts[1]) && version_compare($filename_parts[1], $plugin_version, '>')) {
                        // The plugin version is higher than the installed version, so send the link to the zip file
                        $zip_path = PLUGINS_DIR . '/' . $filename;
                        $zip_url = 'http://' . $_SERVER['HTTP_HOST'] . '/download.php?domain=' . $domain . '&key=' . $key . '&file=' . $filename;
                        header('Content-Type: application/json');
                        echo json_encode(['zip_url' => $zip_url]);
                        $log_message = $domain . ' ' . date('Y-m-d,h:i:sa') . ' Successful';
                        file_put_contents('../accesslog.log', $log_message . PHP_EOL, LOCK_EX | FILE_APPEND);
                        exit();
                    }
                }
            }
            // The plugin version is not higher than the installed version, so return an empty response
            http_response_code(204);
            header('Content-Type: application/json');
            header('Content-Length: 0');
            $log_message = $domain . ' ' . date('Y-m-d,h:i:sa') . ' Successful';
            file_put_contents('../accesslog.log', $log_message . PHP_EOL, LOCK_EX | FILE_APPEND);
            exit();
        }
    }
    fclose($host_file);
}

// The domain and key pair does not exist in the HOSTS file, log the unauthorized access and return a 401 Unauthorized response
header('HTTP/1.1 401 Unauthorized');
echo 'Unauthorized';
error_log('Unauthorized access: ' . $_SERVER['REMOTE_ADDR']);
$log_message = $domain . ' ' . date('Y-m-d,h:i:sa') . ' Failed';
file_put_contents('../accesslog.log', $log_message . PHP_EOL, LOCK_EX | FILE_APPEND);
exit();
