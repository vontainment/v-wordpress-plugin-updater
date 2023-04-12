<?php
/*
 * Project: Update API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: plugins-box.inc.php
 * Description: WordPress Update API
 */
?>

<div class="section">
    <h2>Plugins</h2>
    <div id="plugins_table">
        <?php
        $plugins_dir = "../plugins";
        $plugins = glob($plugins_dir . "/*.zip");

        function generateTableRow($plugin, $plugin_name)
        {
            return '<tr>
        <td>' . $plugin_name . '</td>
        <td>
            <form name="delete_plugin_form" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="POST">
                <input type="hidden" name="plugin_name" value="' . $plugin . '">
                <input type="submit" name="delete_plugin" value="Delete">
            </form>
        </td>
    </tr>';
        }

        // Reverse the plugins array
        $plugins = array_reverse($plugins);

        if (count($plugins) > 0) {
            // Split plugins array into two halves
            $half_count = ceil(count($plugins) / 2);
            $plugins_column1 = array_slice($plugins, 0, $half_count);
            $plugins_column2 = array_slice($plugins, $half_count);

            $table_html = '<div class="row"><div class="column">
    <table>
        <thead>
            <tr>
                <th>Plugin Name</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>';
            foreach ($plugins_column1 as $plugin) {
                $plugin_name = basename($plugin);
                $table_html .= generateTableRow($plugin, $plugin_name);
            }
            $table_html .= '</tbody></table></div><div class="column"><table>
        <thead>
            <tr>
                <th>Plugin Name</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>';
            foreach ($plugins_column2 as $plugin) {
                $plugin_name = basename($plugin);
                $table_html .= generateTableRow($plugin, $plugin_name);
            }
            $table_html .= '</tbody></table></div></div>';
        } else {
            $table_html = "No plugins found.";
        }

        echo $table_html;

        ?>

    </div>

    <div class="section">
        <h2>Upload Plugin</h2>
        <form name="upload_plugin_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <input type="file" name="plugin_file">
            <input type="submit" name="upload_plugin" value="Upload">
        </form>
        <?php
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['upload_messages'])) {
            echo '<div class="upload-messages">';
            foreach ($_SESSION['upload_messages'] as $message) {
                echo '<p>' . $message . '</p>';
            }
            echo '</div>';
            unset($_SESSION['upload_messages']);
        }
        ?>
    </div>
</div>