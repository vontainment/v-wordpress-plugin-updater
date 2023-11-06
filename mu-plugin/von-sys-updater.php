<?php

/**
 * Plugin Name: WP Plugin Updater
 * Plugin URI: https://vontainment.com
 * Description: This plugin updates your WordPress plugins and themes.
 * Version: 2.5.0
 * Author: Vontainment
 * Author URI: https://vontainment.com
 */


// This function is used to log errors.
// It checks if both WP_DEBUG and WP_DEBUG_LOG are set and true,
// if so it logs the error message with a prefix of "VONTAINMENT: ".
function vontmnt_log_error($message)
{
    if (defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
        error_log("VONTAINMENT: " . $message);
    }
}

// This function schedules the update events if they have not been scheduled already.
// It uses the WordPress Cron API to schedule a daily event 'vontmnt_updater_run_updates'.
function vontmnt_updater_schedule_events()
{
    if (!wp_next_scheduled('vontmnt_updater_run_updates')) {
        wp_schedule_event(time(), 'daily', 'vontmnt_updater_run_updates');
    }
}
// The 'wp' action hook fires when the WordPress core has loaded but before any headers are sent.
// Here we are hooking our 'vontmnt_updater_schedule_events' function to this action.
add_action('wp', 'vontmnt_updater_schedule_events');

// This function is used to schedule updates for all plugins and themes.
// It first includes the necessary WordPress plugin file.
// Then it gets all plugins and themes and stores them in an array.
// For each type (plugin/theme), it checks if there was an error retrieving the items,
// if so it logs the error and continues to the next item.
// If there was no error, it runs the updates for the current items.
function vontmnt_schedule_all_updates()
{
    require_once ABSPATH . 'wp-admin/includes/plugin.php';

    // Array holding all plugins and themes to update
    $types = [
        'plugin' => get_plugins(),
        'theme' => wp_get_themes(),
    ];

    foreach ($types as $type => $items) {
        if (is_wp_error($items)) {
            vontmnt_log_error("Failed to get $type items: " . $items->get_error_message());
            continue;
        }
        vontmnt_run_updates($items, $type);
    }
}

// The 'vontmnt_updater_run_updates' action hook is fired when our scheduled event is due.
// We're hooking our 'vontmnt_schedule_all_updates' function to this action so that it runs when the event is triggered.
add_action('vontmnt_updater_run_updates', 'vontmnt_schedule_all_updates');


// This function is used to update a plugin from a zip file.
// It first includes necessary WordPress upgrader classes.
// Then it downloads the zip file and if successful, it creates a new instance of the Plugin_Upgrader class.
// It then runs the upgrade process using the downloaded zip file.
// If there were errors during this process, they are logged.
function update_plugin_from_zip($plugin_slug, $zip_url)
{
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';

    // Downloading the zip file
    $tmp_file = download_url($zip_url);
    if (is_wp_error($tmp_file)) {
        vontmnt_log_error("Failed to download plugin: " . $tmp_file->get_error_message());
        return $tmp_file;
    }

    // Upgrading the plugin
    $plugin_upgrader = new Plugin_Upgrader();
    $result = $plugin_upgrader->run([
        'package' => $tmp_file,
        'destination' => WP_PLUGIN_DIR,
        'clear_destination' => true,
        'clear_working' => true,
        'hook_extra' => ['plugin' => $plugin_slug],
    ]);

    // Deleting the temporary file
    if (!unlink($tmp_file) && file_exists($tmp_file)) {
        vontmnt_log_error("Failed to delete temporary plugin file: $tmp_file");
    }

    // Checking for errors in the result
    if (is_wp_error($result)) {
        vontmnt_log_error("Failed to update plugin: " . $result->get_error_message());
    }

    return $result;
}

// This function is similar to the previous one but is used to update a theme from a zip file.
function update_theme_from_zip($theme_slug, $zip_url)
{
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    require_once ABSPATH . 'wp-admin/includes/class-theme-upgrader.php';

    $tmp_file = download_url($zip_url);
    if (is_wp_error($tmp_file)) {
        vontmnt_log_error("Failed to download theme [$theme_slug]: " . $tmp_file->get_error_message());
        return $tmp_file;
    }

    $theme_upgrader = new Theme_Upgrader();
    $result = $theme_upgrader->run([
        'package' => $tmp_file,
        'destination' => get_theme_root(),
        'clear_destination' => true,
        'clear_working' => true,
        'hook_extra' => ['theme' => $theme_slug],
    ]);

    if (!unlink($tmp_file) && file_exists($tmp_file)) {
        vontmnt_log_error("Failed to delete temporary theme file: $tmp_file");
    }

    if (is_wp_error($result)) {
        vontmnt_log_error("Failed to update theme [$theme_slug]: " . $result->get_error_message());
    }

    return $result;
}

// This function runs updates for all items (plugins or themes).
// For each item, it retrieves the slug and version and sanitizes them.
// It then sends a POST request to the VONTMENT_API with the action to update the item.
// If the response code is 401, it logs an error message and exits.
// If the response data does not contain a 'zip_url', it logs an error message and continues to the next item.
// It then tries to update the item using the 'zip_url' from the response data.
// If there was an error updating the item, it logs the error message.
function vontmnt_run_updates($items, $type)
{
    foreach ($items as $item_path => $item) {
        // Code omitted for brevity...

        // Sending POST request instead of GET
        $response = wp_remote_post(esc_url_raw(defined('VONTMENT_API') ? VONTMENT_API : ''), [
            // Request body...
        ]);

        // Checking response code
        $response_code = wp_remote_retrieve_response_code($response);

        if ($response_code === 401) {
            vontmnt_log_error("Unauthorized request received. Response code: $response_code. Exiting.");
            exit();
        }

        // Decoding response data
        $response_data = json_decode(wp_remote_retrieve_body($response), true);
        if (!$response_data || !isset($response_data['zip_url'])) {
            vontmnt_log_error("(vontmnt_run_updates) Error decoding JSON response body for $type [$slug]");
            continue;
        }

        // Updating the item
        $result = ($type === 'plugin') ? update_plugin_from_zip($slug, $response_data['zip_url']) : update_theme_from_zip($slug, $response_data['zip_url']);

        if (is_wp_error($result)) {
            vontmnt_log_error("(vontmnt_run_updates) Error updating $type [$slug]: " . $result->get_error_message());
        }
    }
}
