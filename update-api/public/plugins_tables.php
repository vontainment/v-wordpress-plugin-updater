<?php
$plugins_dir = "../plugins";
$plugins = glob($plugins_dir . "/*.zip");
if (count($plugins) > 0) {
    $table_html = '<div class="row"><div class="column">
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
            $table_html .= '<tr>
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
    $table_html .= '</tbody></table></div><div class="column"><table>
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
            $table_html .= '<tr>
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
    $table_html .= '</tbody></table></div></div>';
} else {
    $table_html = "No plugins found.";
}

echo $table_html;
