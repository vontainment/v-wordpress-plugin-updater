<?php
/*
Plugin Name: WP Plugin Updater
Plugin URI: https://vontainment.com
Description: This plugin updates your WordPress plugins, mu-plugins and themes.
Version: 1.2.0
Author: Vontainment
Author URI: https://vontainment.com
*/

function vontmnt_updater_schedule_updates($action_name, $interval = 'daily')
{
    if (!wp_next_scheduled($action_name)) {
        wp_schedule_event(time(), $interval, $action_name);
    }
}

add_action('wp', function () {
    vontmnt_updater_schedule_updates('vontmnt_updater_run_updates', 'plugin');
    vontmnt_updater_schedule_updates('vontmnt_updater_run_updates', 'theme');
    vontmnt_updater_schedule_updates('vontmnt_updater_run_updates', 'muplugin', 'monthly');
});

function vontmnt_download_and_install_package($response_data, $destination_dir, $type = 'plugin')
{
    if (!isset($response_data['zip_url'])) {
        return false;
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    $tmp_file = download_url($response_data['zip_url']);

    if (is_wp_error($tmp_file)) {
        error_log("Error downloading {$type} file: " . $tmp_file->get_error_message());
        return false;
    }

    $upload_dir = wp_upload_dir();
    $zip_file = $upload_dir['path'] . '/' . basename($response_data['zip_url']);
    if (!rename($tmp_file, $zip_file)) {
        error_log("Failed to move downloaded file to {$zip_file}");
        return false;
    }

    WP_Filesystem();
    $unzipfile = unzip_file($zip_file, $destination_dir);

    if (is_wp_error($unzipfile)) {
        error_log("Error unzipping {$type} file: " . $unzipfile->get_error_message());
        return false;
    }

    unlink($zip_file);
    return true;
}

function vontmnt_api_url($slug, $version, $type)
{
    return add_query_arg(
        array(
            'action' => "update-{$type}s",
            'domain' => urlencode(parse_url(site_url(), PHP_URL_HOST)),
            $type => urlencode($slug),
            'version' => urlencode($version),
            'key' => VONTMENT_KEY,
        ),
        constant("VONTMENT_" . strtoupper($type) . "S")
    );
}

function vontmnt_run_updates($items, $dir, $type)
{
    foreach ($items as $item_path => $item) {
        // Use WordPress's plugin_basename function
        $slug = plugin_basename($item_path);
        $version = $item['Version'];

        // Validation for themes
        if ($type === 'theme') {
            if (!is_a($item, 'WP_Theme')) {
                error_log('Invalid theme object');
                continue;
            }
            $slug = $item->get_stylesheet();
            $version = $item->get('Version');
        }

        // Sanitize and validate
        $slug = sanitize_file_name($slug);
        $version = sanitize_text_field($version);

        $api_url = vontmnt_api_url($slug, $version, $type);

        // Validate API URL
        if (filter_var($api_url, FILTER_VALIDATE_URL) === false) {
            error_log('Invalid API URL');
            continue;
        }

        $response = wp_remote_get(esc_url_raw($api_url));
        $http_code = wp_remote_retrieve_response_code($response);

        // Validate HTTP response code
        if ($http_code === false) {
            error_log('Failed to get HTTP response code');
            continue;
        }

        if ($http_code == 204) {
            error_log("$slug : has no updates");
            continue;
        } elseif ($http_code == 401) {
            error_log("You are not authorized for the Vontainment API");
            break;
        }

        $response_data = json_decode(wp_remote_retrieve_body($response), true);

        // Validate JSON response
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('Invalid JSON response');
            continue;
        }

        if (vontmnt_download_and_install_package($response_data, $dir, $type)) {
            error_log("$slug : Was updated");
        } else {
            error_log("$slug : Is up-to-date");
        }
    }
}

function vontmnt_updater_run_updates($type)
{
    switch ($type) {
        case 'plugin':
            $items = get_plugins();
            $dir = WP_PLUGIN_DIR;
            break;
        case 'theme':
            $items = wp_get_themes();
            $dir = get_theme_root();
            break;
        case 'muplugin':
            $items = glob(WPMU_PLUGIN_DIR . '/*.php');
            $dir = WPMU_PLUGIN_DIR;
            break;
        default:
            return;
    }

    vontmnt_run_updates($items, $dir, $type);
}
