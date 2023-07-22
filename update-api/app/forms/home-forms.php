<?php
/*
* Project: Update API
* Author: Vontainment
* URL: https://vontainment.com
* File: home-form.php
* Description: WordPress Update API
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_entry'])) {
        $hosts_file = HOSTS_ACL . '/HOSTS';
        $domain = $_POST['domain'];
        $key = $_POST['key'];
        $new_entry = $domain . ' ' . $key;
        file_put_contents($hosts_file, $new_entry . "\n", FILE_APPEND | LOCK_EX);
        header('Location: /home');
        exit();
    } elseif (isset($_POST['update_entry'])) {
        $hosts_file = HOSTS_ACL . '/HOSTS';
        $entries = file($hosts_file, FILE_IGNORE_NEW_LINES);
        $line_number = $_POST['id'];
        $domain = $_POST['domain'];
        $key = $_POST['key'];
        $entries[$line_number] = $domain . ' ' . $key;
        file_put_contents($hosts_file, implode("\n", $entries) . "\n");
        header('Location: /home');
        exit();
    } elseif (isset($_POST['delete_entry'])) {
        $hosts_file = HOSTS_ACL . '/HOSTS';
        $entries = file($hosts_file, FILE_IGNORE_NEW_LINES);
        $line_number = $_POST['id'];
        unset($entries[$line_number]);
        file_put_contents($hosts_file, implode("\n", $entries) . "\n");
        header('Location: /home');
        exit();
    }
}
