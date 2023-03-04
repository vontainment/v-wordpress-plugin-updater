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
    // Get the list of installed plugins
    $plugins = get_plugins();

    // Loop through each installed plugin and check for updates
    foreach ($plugins as $plugin_path => $plugin) {
        // Get the plugin slug
        $plugin_slug = basename($plugin_path, '.php');
        // Get the installed plugin version
        $installed_version = $plugin['Version'];

        // Construct the API endpoint URL
        $api_url = 'https://updates.vontainment.com/api.php';
        $api_url .= '?domain=' . urlencode(parse_url(site_url(), PHP_URL_HOST));
        $api_url .= '&plugin=' . urlencode($plugin_slug);
        $api_url .= '&version=' . urlencode($installed_version);

        // Send the request to the API endpoint
        $response = wp_remote_get($api_url);

        // Get the response body
        $response_body = wp_remote_retrieve_body($response);

        // Check if the API returned a plugin update
        if (!empty($response_body)) {
            $response_data = json_decode($response_body, true);

            if (isset($response_data['zip_url'])) {
                $download_url = $response_data['zip_url'];

                // Download the zip file to the upload directory
                $upload_dir = wp_upload_dir();
                $tmp_file = download_url($download_url);
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
                }
            }
        }
    }
}