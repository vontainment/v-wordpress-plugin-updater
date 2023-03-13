<?php
/*
Plugin Name: WP Plugin Updater
Plugin URI: https://vontainment.com
Description: This plugin updates your WordPress plugins.
Version: 1.0.0
Author: Vontainment
Author URI: https://vontainment.com
*/

define('ABSPATH', dirname(__FILE__) . '/');

// Schedule the update check to run every day
add_action('wp', 'vontmnt_updater_schedule_check');

function vontmnt_updater_schedule_check()
{
    if (!wp_next_scheduled('vontmnt_updater_check_plugins')) {
        wp_schedule_event(time(), 'daily', 'vontmnt_updater_check_plugins');
    }
}

add_action('vontmnt_updater_check_plugins', 'vontmnt_updater_run_updates');


function vontmnt_updater_run_updates()
{
    // Define the endpoint URL and key
    define('ENDPOINT', 'https://api.vontainment.com/api.php');
    define('KEY', '123');

    // Get the list of installed plugins
    $plugins = get_plugins();

    // Loop through each installed plugin and check for updates
    foreach ($plugins as $plugin_path => $plugin) {
        // Get the plugin slug
        $plugin_slug       = basename($plugin_path, '.php');
        // Get the installed plugin version
        $installed_version = $plugin['Version'];

        // Construct the API endpoint URL with the key inline
        $api_url = ENDPOINT . '?domain=' . urlencode(parse_url(site_url(), PHP_URL_HOST)) . '&key=' . urlencode(KEY) . '&plugin=' . urlencode($plugin_slug) . '&version=' . urlencode($installed_version);

        // Send the request to the API endpoint
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false
        ));
        $response  = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        // Get the response body
        $response_body = $response;

        // Check if the API returned a plugin update
        if ($http_code == 204) {
            error_log("$plugin_slug : has no updates");
        } elseif ($http_code == 401) {
            error_log("You are not authorized for the Vontainment API");
        } elseif (!empty($response_body)) {
            $response_data = json_decode($response_body, true);

            if (isset($response_data['zip_url'])) {
                $download_url = $response_data['zip_url'];

                // Download the zip file to the upload directory
                require_once ABSPATH . 'wp-admin/includes/file.php';
                $upload_dir      = wp_upload_dir();
                $tmp_file        = download_url($download_url);
                $plugin_zip_file = $upload_dir['path'] . '/' . basename($download_url);

                // Move the downloaded file to the plugins directory
                rename($tmp_file, $plugin_zip_file);

                // Unzip the plugin zip file
                WP_Filesystem();
                $unzipfile = unzip_file($plugin_zip_file, WP_PLUGIN_DIR);

                // Check if the unzip was successful
                if (is_wp_error($unzipfile)) {
                    error_log('Error unzipping plugin file: ' . $unzipfile->get_error_message());
                } else {
                    // Delete the plugin zip file
                    unlink($plugin_zip_file);
                    error_log("$plugin_slug : Was updated");
                }
            } else {
                error_log("$plugin_slug : Is up-to-date");
            }
        }
    }
}
