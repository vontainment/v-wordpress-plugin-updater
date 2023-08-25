<?php
/*
Plugin Name: WP Plugin Updater MU
Plugin URI: https://vontainment.com
Description: This plugin updates your WordPress plugins.
Version: 1.0.0
Author: Vontainment
Author URI: https://vontainment.com
*/

// Schedule the update check to run every day
add_action('wp', 'vontmnt_plugin_updater_schedule_updates');

function vontmnt_plugin_updater_schedule_updates()
{
    if (!wp_next_scheduled('vontmnt_plugin_updater_check_updates')) {
        wp_schedule_event(time(), 'daily', 'vontmnt_plugin_updater_check_updates');
    }
}

add_action('vontmnt_plugin_updater_check_updates', 'vontmnt_plugin_updater_run_updates');

// Constructs the API endpoint URL for each plugin
function vontmnt_construct_plugin_api_url_multisite($plugin_path, $plugin)
{
    $plugin_slug = basename($plugin_path, '.php');
    $installed_version = $plugin['Version'];
    return add_query_arg(
        array(
            'action' => 'update-plugins',
            'domain' => urlencode(parse_url(site_url(), PHP_URL_HOST)),
            'plugin' => urlencode($plugin_slug),
            'version' => urlencode($installed_version),
            'key' => VONTMENT_KEY,
        ),
        VONTMENT_PLUGINS
    );
}

// Downloads and installs the plugin from the given API response
function vontmnt_download_and_install_plugin_multisite($response_data)
{
    if (isset($response_data['zip_url'])) {
        $download_url = $response_data['zip_url'];

        require_once ABSPATH . 'wp-admin/includes/file.php';
        $upload_dir = wp_upload_dir();
        $tmp_file = download_url($download_url);
        $plugin_zip_file = $upload_dir['path'] . '/' . basename($download_url);

        rename($tmp_file, $plugin_zip_file);
        WP_Filesystem();
        $unzipfile = unzip_file($plugin_zip_file, WP_PLUGIN_DIR);

        if (is_wp_error($unzipfile)) {
            error_log('Error unzipping plugin file: ' . $unzipfile->get_error_message());
        } else {
            unlink($plugin_zip_file);
            return true;
        }
    }
    return false;
}

// Main function to check and run updates for plugins on a multisite installation
function vontmnt_plugin_updater_run_updates()
{
    if (!is_main_site()) {
        return;
    }

    $plugins = get_plugins();
    foreach ($plugins as $plugin_path => $plugin) {
        $api_url = vontmnt_construct_plugin_api_url_multisite($plugin_path, $plugin);
        list($response, $http_code) = vontmnt_send_curl_request($api_url);

        $plugin_slug = basename($plugin_path, '.php');
        if ($http_code == 204) {
            error_log("$plugin_slug : has no updates");
        } elseif ($http_code == 401) {
            error_log("You are not authorized for the Vontainment API");
        } elseif (!empty($response)) {
            $response_data = json_decode($response, true);
            if (vontmnt_download_and_install_plugin_multisite($response_data)) {
                error_log("$plugin_slug : Was updated");
            } else {
                error_log("$plugin_slug : Is up-to-date");
            }
        }
    }
}
