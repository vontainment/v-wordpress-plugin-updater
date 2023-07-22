<?php
/*
 * Project: Update API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: plupdate-helper.php
 * Description: WordPress Update API
 */
$plugins = glob(PLUGINS_DIR . "/*.zip");

function generatePluginTableRow($plugin, $pluginName)
{
    return '<tr>
        <td>' . $pluginName . '</td>
        <td>
            <form class="delete-plugin-form" action="/plupdate" method="POST">
                <input type="hidden" name="plugin_name" value="' . $pluginName . '">
                <button class="pl-submit" type="submit" name="delete_plugin">Delete</button>
            </form>
        </td>
    </tr>';
}

// Reverse the plugins array
$plugins = array_reverse($plugins);

if (count($plugins) > 0) {
    // Split plugins array into two halves
    $halfCount = ceil(count($plugins) / 2);
    $pluginsColumn1 = array_slice($plugins, 0, $halfCount);
    $pluginsColumn2 = array_slice($plugins, $halfCount);

    $pluginsTableHtml = '<div class="row"><div class="column">
        <table>
            <thead>
                <tr>
                    <th>Plugin Name</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($pluginsColumn1 as $plugin) {
        $pluginName = basename($plugin);
        $pluginsTableHtml .= generatePluginTableRow($plugin, $pluginName);
    }

    $pluginsTableHtml .= '</tbody></table></div><div class="column"><table>
        <thead>
            <tr>
                <th>Plugin Name</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($pluginsColumn2 as $plugin) {
        $pluginName = basename($plugin);
        $pluginsTableHtml .= generatePluginTableRow($plugin, $pluginName);
    }

    $pluginsTableHtml .= '</tbody></table></div></div>';
} else {
    $pluginsTableHtml = "No plugins found.";
}
