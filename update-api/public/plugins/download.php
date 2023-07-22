<?php

/**
 * WP Plugin Update API
 * Version: 1.1
 * Author: Vontainment
 * Author URI: https://vontainment.com
 */

// Include the config file
require_once('../../config.php');
require_once '../../lib/waf-lib.php';

$ip = $_SERVER['REMOTE_ADDR'];
if (is_blacklisted($ip)) {
    // Stop the script and show an error if the IP is blacklisted
    http_response_code(403); // Optional: Set HTTP status code to 403 Forbidden
    echo "Your IP address has been blacklisted. If you believe this is an error, please contact us.";
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    foreach ($_GET as $postvalue => $value) {
        $_GET[$postvalue] = sanitize_input($value);
    }

    // Get the domain, key, and file from the query string
    $domain = $_GET['domain'] ?? '';
    $key = $_GET['key'] ?? '';
    $file = $_GET['file'] ?? '';

    // Validate the domain and key against the HOSTS file
    $allowed = false;
    if ($handle = fopen(HOSTS_ACL . 'HOSTS', 'r')) {
        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if (!empty($line)) {
                list($host, $host_key) = explode(' ', $line, 2);
                if ($domain == $host && $key == $host_key) {
                    $allowed = true;
                    break;
                }
            }
        }
        fclose($handle);
    }

    // If the domain and key are valid, send the file for download
    if ($allowed) {
        $file_path = PLUGINS_DIR . '/' . $file;
        if (file_exists($file_path)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit;
        } else {
            header('HTTP/1.1 404 Not Found');
            echo 'File not found';
            error_log('File not found: ' . $file_path);
            exit;
        }
    } else {
        update_failed_attempts($ip);
        header('HTTP/1.1 401 Unauthorized');
        echo 'Unauthorized';
        error_log('Unauthorized access: ' . $_SERVER['REMOTE_ADDR']);
        exit;
    }
} else {
    exit();
}
