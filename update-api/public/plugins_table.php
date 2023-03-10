<?php
/*
WP Plugin Update API
Version: 1.1
Author: Vontainment
Author URI: https://vontainment.com
*/

$plugins_dir = "../plugins";
$plugins = glob($plugins_dir . "/*.zip");
if (count($plugins) > 0) {
    echo '<div class="row"><div class="column">
        <table>
            <thead>
                <tr>
                    <th>Plugin Name</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>';
    $i = 0;
    foreach ($plugins as $plugin) {
        $plugin_name = basename($plugin);
        if ($i % 2 == 0) {
            echo '<tr>
                <td>' . $plugin_name . '</td>
                <td>
                    <form method="post">
                        <input type="hidden" name="plugin_name" value="' . $plugin_name . '">
                        <input type="submit" name="delete_plugin" value="Delete">
                    </form>
                </td>
            </tr>';
        }
        $i++;
    }
    echo '</tbody></table></div><div class="column"><table>
            <thead>
                <tr>
                    <th>Plugin Name</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>';
    $i = 0;
    foreach ($plugins as $plugin) {
        $plugin_name = basename($plugin);
        if ($i % 2 != 0) {
            echo '<tr>
                <td>' . $plugin_name . '</td>
                <td>
                    <form method="post">
                        <input type="hidden" name="plugin_name" value="' . $plugin_name . '">
                        <input type="submit" name="delete_plugin" value="Delete">
                    </form>
                </td>
            </tr>';
        }
        $i++;
    }
    echo '</tbody></table></div></div>';
} else {
    echo "No plugins found.";
}
?>