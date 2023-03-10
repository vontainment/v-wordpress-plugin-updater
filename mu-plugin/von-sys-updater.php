<?php
/*
Plugin Name: WP Plugin Updater
Plugin URI: https://vontainment.com
Description: This plugin updates your WordPress plugins.
Version: 1.1
Author: Vontainment
Author URI: https://vontainment.com
*/

// Schedule the update check to run every 5 minutes
add_action('wp', 'wp_plugin_updater_schedule_check');

function wp_plugin_updater_schedule_check()
{
    if (!wp_next_scheduled('wp_plugin_updater_check_plugins')) {
        wp_schedule_event(time(), 'daily', 'wp_plugin_updater_check_plugins');
    }
}

// Add a custom endpoint for plugin updates
add_action('init', 'wp_plugin_updater_add_endpoint');

function wp_plugin_updater_add_endpoint()
{
    add_rewrite_rule('^v/update$', 'index.php?wp_plugin_updater_update=1', 'top');
    add_rewrite_tag('%wp_plugin_updater_update%', '1');
}

// Check for plugin updates on the custom endpoint
add_action('template_redirect', 'wp_plugin_updater_check_updates_on_endpoint');

function wp_plugin_updater_check_updates_on_endpoint()
{
    if (get_query_var('wp_plugin_updater_update')) {
        wp_plugin_updater_check_plugin_updates();
        exit;
    }
}

function wp_plugin_updater_check_plugin_updates()
{
    // Define the endpoint URL and key
    define('ENDPOINT', 'https://api.vontainment.com/api.php');
    define('KEY', '123');

    // Get the list of installed plugins
    $plugins = get_plugins();

    // Loop through each installed plugin and check for updates
    foreach ($plugins as $plugin_path => $plugin) {
        // Get the plugin slug
        $plugin_slug = basename($plugin_path, '.php');
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
        $response = curl_exec($curl);
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

                // Download the zip file to the plugins directory
                require_once ABSPATH . 'wp-admin/includes/file.php';
                WP_Filesystem();
                global $wp_filesystem;
                $download_path = WP_PLUGIN_DIR . '/' . basename($download_url);
                $wp_filesystem->put_contents($download_path, fopen($download_url, 'r'));

                // Unzip the plugin zip file
                $unzipfile = unzip_file($download_path, WP_PLUGIN_DIR);

                // Check if the unzip was successful
                if (is_wp_error($unzipfile)) {
                    error_log('Error unzipping file: ' . $unzipfile->get_error_message());
                } else {
                    // Delete the plugin zip file
                    unlink($download_path);
                    error_log("$plugin_slug updated");
                }
            } else {
                error_log("$plugin_slug : has no updates");
            }
        }
    }
}
