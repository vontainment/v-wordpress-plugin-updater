<?php

/**
 * WP Theme Update API
 * Version: 1.1
 * Author: Vontainment
 * Author URI: https://vontainment.com
 */

// Include the config file
require_once('../../config.php');

// Get the request parameters
$domain       = filter_input(INPUT_GET, 'domain', FILTER_SANITIZE_URL);
$key          = filter_input(INPUT_GET, 'key');
$theme_slug   = filter_input(INPUT_GET, 'theme');
$theme_version = filter_input(INPUT_GET, 'version');

// Check if the domain and key exist in the HOSTS file
if ($host_file = @fopen(HOSTS_ACL, 'r')) {
    while (!feof($host_file)) {
        $line = trim(fgets($host_file));
        list($host, $host_key) = explode(' ', $line);
        if ($host === $domain && $host_key === $key) {
            // The domain and key pair exists in the HOSTS file, so check for an updated theme version
            $themes = scandir(THEMES_DIR);
            foreach ($themes as $filename) {
                if (strpos($filename, $theme_slug) === 0) {
                    // The theme slug matches the beginning of the filename
                    $filename_parts = explode('_', $filename);
                    if (isset($filename_parts[1]) && version_compare($filename_parts[1], $theme_version, '>')) {
                        // The theme version is higher than the installed version, so send the link to the zip file
                        $zip_path = THEMES_DIR . '/' . $filename;
                        $zip_url = 'http://' . $_SERVER['HTTP_HOST'] . '/themes/download.php?domain=' . $domain . '&key=' . $key . '&file=' . $filename;
                        header('Content-Type: application/json');
                        echo json_encode(['zip_url' => $zip_url]);
                        $log_message = $domain . ' ' . date('Y-m-d,h:i:sa') . ' Successful';
                        file_put_contents('../../theme.log', $log_message . PHP_EOL, LOCK_EX | FILE_APPEND);
                        exit();
                    }
                }
            }
            // The theme version is not higher than the installed version, so return an empty response
            http_response_code(204);
            header('Content-Type: application/json');
            header('Content-Length: 0');
            $log_message = $domain . ' ' . date('Y-m-d,h:i:sa') . ' Successful';
            file_put_contents('../../theme.log', $log_message . PHP_EOL, LOCK_EX | FILE_APPEND);
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
file_put_contents('../../theme.log', $log_message . PHP_EOL, LOCK_EX | FILE_APPEND);
exit();
