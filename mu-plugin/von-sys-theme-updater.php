<?php
/*
Theme Name: WP Theme Updater
Theme URI: https://vontainment.com
Description: This theme updates your WordPress themes.
Version: 1.0.0
Author: Vontainment
Author URI: https://vontainment.com
*/

define('VONTMENT_THEMES', 'https://api.vontainment.com/api.php');
define('VONTMENT_KEY', '123');

// Schedule the update check to run every day
add_action('wp', 'vontmnt_theme_updater_schedule_updates');

function vontmnt_theme_updater_schedule_updates()
{
    if (!wp_next_scheduled('vontmnt_theme_updater_check_updates')) {
        wp_schedule_event(time(), 'daily', 'vontmnt_theme_updater_check_updates');
    }
}

add_action('vontmnt_theme_updater_check_updates', 'vontmnt_theme_updater_run_updates');

function vontmnt_theme_updater_run_updates()
{
    // Get the list of installed themes
    $themes = wp_get_themes();

    // Loop through each installed theme and check for updates
    foreach ($themes as $theme) {
        // Get the theme slug
        $theme_slug = $theme->get_stylesheet();
        // Get the installed theme version
        $installed_version = $theme->get('Version');

        // Construct the API endpoint URL with the query parameters
        $api_url = add_query_arg(
            array(
                'domain' => urlencode(parse_url(site_url(), PHP_URL_HOST)),
                'theme' => urlencode($theme_slug),
                'version' => urlencode($installed_version),
                'key' => VONTMENT_KEY,
            ),
            VONTMENT_THEMES
        );

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

        // Check if the API returned a theme update
        if ($http_code == 204) {
            error_log("$theme_slug : has no updates");
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
                $theme_zip_file = $upload_dir['path'] . '/' . basename($download_url);

                // Move the downloaded file to the themes directory
                rename($tmp_file, $theme_zip_file);

                // Unzip the theme zip file
                WP_Filesystem();
                $unzipfile = unzip_file($theme_zip_file, get_theme_root());

                // Check if the unzip was successful
                if (is_wp_error($unzipfile)) {
                    error_log('Error unzipping theme file: ' . $unzipfile->get_error_message());
                } else {
                    // Delete the theme zip file
                    unlink($theme_zip_file);
                    error_log("$theme_slug : Was updated");
                }
            } else {
                error_log("$theme_slug : Is up-to-date");
            }
        }
    }
}
