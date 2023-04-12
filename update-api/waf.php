<?php

// Define the list of disallowed characters and patterns
$disallowed_chars = array("<?", "?>", "<%", "%>", "<script", "</script>");
$disallowed_patterns = array("/bin/sh", "exec(", "system(", "passthru(", "shell_exec(", "phpinfo(");

// Function to check if a string contains a disallowed character
function contains_disallowed_chars($str) {
    global $disallowed_chars;
    foreach ($disallowed_chars as $char) {
        if (strpos($str, $char) !== false) {
            return true;
        }
    }
    return false;
}

// Function to check if a string contains a disallowed pattern
function contains_disallowed_patterns($str) {
    global $disallowed_patterns;
    foreach ($disallowed_patterns as $pattern) {
        if (strpos($str, $pattern) !== false) {
            return true;
        }
    }
    return false;
}

function sanitize_input($data)
{
    // Remove any disallowed characters or patterns
    if (contains_disallowed_chars($data) || contains_disallowed_patterns($data)) {
        $data = "";
    }

    // Check if the IP is blacklisted
    $ip = $_SERVER['REMOTE_ADDR'];
    if (is_blacklisted($ip)) {
        http_response_code(403); // Forbidden
        exit("Access denied");
    }

    return $data;
}

function blacklist_ip($ip)
{
    $blacklist_file = "../blacklists/BLACKLIST";
    $content = file_get_contents($blacklist_file);
    $content .= "$ip," . strval(time()) . "\n";
    file_put_contents($blacklist_file, $content);
}


function is_blacklisted($ip)
{
    $blacklist_file = "../storage/waf/BLACKLIST";
    $blacklist = file($blacklist_file, FILE_IGNORE_NEW_LINES);
    foreach ($blacklist as $line) {
        list($blacklisted_ip, $timestamp) = explode(",", $line); // Get the IP address and timestamp
        if ($ip == $blacklisted_ip) {
            // Check if the timestamp is older than three days
            if (time() - $timestamp > (3 * 24 * 60 * 60)) {
                // Remove the IP address from the blacklist
                $blacklist = array_diff($blacklist, [$line]);
                file_put_contents($blacklist_file, implode("\n", $blacklist));
            } else {
                return true;
            }
        }
    }
    return false;
}

// Sanitize GET, POST, and COOKIE data
foreach ($_GET as $key => $value) {
    $_GET[$key] = sanitize_input($value);
}
foreach ($_POST as $key => $value) {
    $_POST[$key] = sanitize_input($value);
}
foreach ($_COOKIE as $key => $value) {
    $_COOKIE[$key] = sanitize_input($value);
}

// Check if the user failed to log in three times
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    $ip = $_SERVER['REMOTE_ADDR'];
    $ip_filename = str_replace(':', '_', $ip);
    $login_attempts_file = "../blacklists/$ip_filename";
    if (file_exists($login_attempts_file)) {
        $login_attempts = (int) file_get_contents($login_attempts_file);
        if ($login_attempts >= 3 && !is_blacklisted($ip)) {
            blacklist_ip($ip);
        } else {
            file_put_contents($login_attempts_file, $login_attempts + 1);
        }
    } else {
        file_put_contents($login_attempts_file, 1);
    }
}
