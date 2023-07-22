<?php
/*
 * Project: Update API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: waf-lib.php
 * Description: WordPress Update API
*/

// Sanitize and validate input data
function sanitize_input($data)
{
    // Trim whitespace and remove HTML tags
    $data = trim(strip_tags($data));

    // Filter the input using appropriate filters
    $data = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    // Add additional security measures
    $data = str_replace(array("<?", "?>", "<%", "%>"), "", $data);
    $data = str_replace(array("<script", "</script"), "", $data);
    $data = str_replace(array("/bin/sh", "exec(", "system(", "passthru(", "shell_exec(", "phpinfo("), "", $data);

    return $data;
}

// Function to check if a string contains a disallowed character
function contains_disallowed_chars($str)
{
    global $disallowed_chars;
    foreach ($disallowed_chars as $char) {
        if (strpos($str, $char) !== false) {
            return true;
        }
    }
    return false;
}

// Function to check if a string contains a disallowed pattern
function contains_disallowed_patterns($str)
{
    global $disallowed_patterns;
    foreach ($disallowed_patterns as $pattern) {
        if (strpos($str, $pattern) !== false) {
            return true;
        }
    }
    return false;
}


function update_failed_attempts($ip)
{
    $blacklist_file = BLACKLIST_DIR . "/BLACKLIST.json";
    $content = json_decode(file_get_contents($blacklist_file), true);

    if (isset($content[$ip])) {
        $content[$ip]['login_attempts'] += 1;

        if ($content[$ip]['login_attempts'] >= 3) {
            $content[$ip]['blacklisted'] = true;
            $content[$ip]['timestamp'] = time();
        }
    } else {
        $content[$ip] = ['login_attempts' => 1, 'blacklisted' => false, 'timestamp' => time()];
    }

    file_put_contents($blacklist_file, json_encode($content));
}
function is_blacklisted($ip)
{
    $blacklist_file = BLACKLIST_DIR . "/BLACKLIST.json";
    $blacklist = json_decode(file_get_contents($blacklist_file), true);

    if (isset($blacklist[$ip]) && $blacklist[$ip]['blacklisted']) {
        // Check if the timestamp is older than three days
        if (time() - $blacklist[$ip]['timestamp'] > (3 * 24 * 60 * 60)) {
            // Remove the IP address from the blacklist
            $blacklist[$ip]['blacklisted'] = false;
            file_put_contents($blacklist_file, json_encode($blacklist));
        } else {
            return true;
        }
    }
    return false;
}
