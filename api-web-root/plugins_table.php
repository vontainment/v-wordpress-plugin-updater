<style>
    .row {
        display: flex;
    }

    .column {
        flex: 50%;
        padding: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
    }

    form {
        display: inline-block;
    }

    input[type=submit] {
        background-color: #4CAF50;
        color: white;
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type=submit]:hover {
        background-color: #45a049;
    }
</style>

<?php
$plugins_dir = "./plugins";
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