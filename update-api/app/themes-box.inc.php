<?php
/*
 * Project: Update API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: themes-box.inc.php
 * Description: WordPress Update API
 */
?>

<div class="section">
    <h2>Themes</h2>
    <div id="themes_table">
        <?php
        $themes_dir = "../themes";
        $themes = glob($themes_dir . "/*.zip");

        function generateThemeTableRow($theme, $theme_name)
        {
            return '<tr>
        <td>' . $theme_name . '</td>
        <td>
            <form name="delete_theme_form" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="POST">
                <input type="hidden" name="theme_name" value="' . $theme . '">
                <input type="submit" name="delete_theme" value="Delete">
            </form>
        </td>
    </tr>';
        }

        // Reverse the themes array
        $themes = array_reverse($themes);

        if (count($themes) > 0) {
            // Split themes array into two halves
            $half_count = ceil(count($themes) / 2);
            $themes_column1 = array_slice($themes, 0, $half_count);
            $themes_column2 = array_slice($themes, $half_count);

            $table_html = '<div class="row"><div class="column">
    <table>
        <thead>
            <tr>
                <th>Theme Name</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>';
            foreach ($themes_column1 as $theme) {
                $theme_name = basename($theme);
                $table_html .= generateThemeTableRow($theme, $theme_name);
            }
            $table_html .= '</tbody></table></div><div class="column"><table>
        <thead>
            <tr>
                <th>theme Name</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>';
            foreach ($themes_column2 as $theme) {
                $theme_name = basename($theme);
                $table_html .= generateThemeTableRow($theme, $theme_name);
            }
            $table_html .= '</tbody></table></div></div>';
        } else {
            $table_html = "No themes found.";
        }

        echo $table_html;

        ?>

    </div>

    <div class="section">
        <h2>Upload Theme</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="upload_theme_form">
            <div class="fallback">
                <input name="theme_file[]" type="file" multiple />
            </div>
        </form>
        <button class="reload-btn" onclick="window.location.hash = 'ThemesBox'; window.location.reload();">Reload Page</button>
    </div>


    <script type="text/javascript">
        Dropzone.autoDiscover = false;

        $(document).ready(function() {
            var myDropzone = new Dropzone("#upload_theme_form", {
                paramName: "theme_file[]", // This must match the name attribute of your input tag
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