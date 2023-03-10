<?php

// Define the path to the HOSTS file

$hosts_path = 'HOSTS';

// Get the domain, key, and file from the query string
$domain = isset($_GET['domain']) ? $_GET['domain'] : '';

$key = isset($_GET['key']) ? $_GET['key'] : '';

$file = isset($_GET['file']) ? $_GET['file'] : '';



// Validate the domain and key against the HOSTS file

$allowed = false;

if ($handle = fopen($hosts_path, 'r')) {

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

    $file_path = 'plugins/' . $file;

    if (file_exists($file_path)) {

        header('Content-Type: application/octet-stream');

        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');

        header('Content-Length: ' . filesize($file_path));

        readfile($file_path);

        exit;

    } else {

        die('File not found');

    }

} else {

    die('Unauthorized');

}

