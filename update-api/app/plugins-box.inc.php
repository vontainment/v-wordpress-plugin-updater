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

        function generatePluginTableRow($plugin, $plugin_name)
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
                $table_html .= generatePluginTableRow($plugin, $plugin_name);
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
                $table_html .= generatePluginTableRow($plugin, $plugin_name);
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="upload_plugin_form">
            <div class="fallback">
                <input name="plugin_file[]" type="file" multiple />
            </div>
        </form>
        <button class="reload-btn" onclick="window.location.hash = 'PluginsBox'; window.location.reload();">Reload Page</button>
    </div>


    <script type="text/javascript">
        Dropzone.autoDiscover = false;

        $(document).ready(function() {
            var myDropzone = new Dropzone("#upload_plugin_form", {
                paramName: "plugin_file[]", // This must match the name attribute of your input tag
                maxFilesize: 1024, // Size in MB
                acceptedFiles: '.zip',
                init: function() {
                    this.on("addedfile", function(file) {
                        if (!file.name.match(/\.zip$/)) { // checks file extension
                            this.removeFile(file);
                            alert("Only .zip files are allowed.");
                        }
                    });
                }
            });
        });
    </script>

</div>