<?php
/*
Theme Name: WP Theme Updater
Theme URI: https://vontainment.com
Description: This theme updates your WordPress themes.
Version: 1.2.0
Author: Vontainment
Author URI: https://vontainment.com
*/

// Schedule the update check to run every day
add_action('wp', 'vontmnt_theme_updater_schedule_updates');

function vontmnt_theme_updater_schedule_updates()
{
    if (!wp_next_scheduled('vontmnt_theme_updater_check_updates')) {
        wp_schedule_event(time(), 'daily', 'vontmnt_theme_updater_check_updates');
    }
}

add_action('vontmnt_theme_updater_check_updates', 'vontmnt_theme_updater_run_updates');

function vontmnt_construct_theme_api_url($theme)
{
    $theme_slug = $theme->get_stylesheet();
    $installed_version = $theme->get('Version');

    return add_query_arg(
        array(
            'action' => 'update-themes',
            'domain' => urlencode(parse_url(site_url(), PHP_URL_HOST)),
            'theme' => urlencode($theme_slug),
            'version' => urlencode($installed_version),
            'key' => VONTMENT_KEY,
        ),
        VONTMENT_THEMES
    );
}

function vontmnt_send_curl_request($api_url)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYHOST => false, // Consider enabling this in a production environment
        CURLOPT_SSL_VERIFYPEER => false  // Consider enabling this in a production environment
    ));

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return array($response, $http_code);
}

function vontmnt_download_and_install_theme($response_data)
{
    if (isset($response_data['zip_url'])) {
        $download_url = $response_data['zip_url'];

        require_once ABSPATH . 'wp-admin/includes/file.php';
        $upload_dir = wp_upload_dir();
        $tmp_file = download_url($download_url);
        $theme_zip_file = $upload_dir['path'] . '/' . basename($download_url);

        rename($tmp_file, $theme_zip_file);

        WP_Filesystem();
        $unzipfile = unzip_file($theme_zip_file, get_theme_root());

        if (is_wp_error($unzipfile)) {
            error_log('Error unzipping theme file: ' . $unzipfile->get_error_message());
        } else {
            unlink($theme_zip_file);
            return true;
        }
    }
    return false;
}

function vontmnt_theme_updater_run_updates()
{
    $themes = wp_get_themes();

    foreach ($themes as $theme) {
        $api_url = vontmnt_construct_theme_api_url($theme);
        list($response, $http_code) = vontmnt_send_curl_request($api_url);

        $theme_slug = $theme->get_stylesheet();
        if ($http_code == 204) {
            error_log("$theme_slug : has no updates");
        } elseif ($http_code == 401) {
            error_log("You are not authorized for the Vontainment API");
        } elseif (!empty($response)) {
            $response_data = json_decode($response, true);
            if (vontmnt_download_and_install_theme($response_data)) {
                error_log("$theme_slug : Was updated");
            } else {
                error_log("$theme_slug : Is up-to-date");
            }
        }
    }
}
