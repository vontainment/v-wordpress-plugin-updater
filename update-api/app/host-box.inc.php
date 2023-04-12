<?php
/*
 * Project: Update API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: host-box.inc.php
 * Description: WordPress Update API
*/
?>

    <div class="section">
        <h2>Allowed Hosts</h2>
        <?php
        // Display the table of entries
        $hosts_file = '../HOSTS';
        $entries = file($hosts_file, FILE_IGNORE_NEW_LINES);
        ?>
        <div class="row">
            <div class="column">
                <table>
                    <thead>
                        <tr>
                            <th>Domain</th>
                            <th>Key</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($entries as $line_number => $entry) {
                            $fields = explode(' ', $entry);
                            $domain = isset($fields[0]) ? $fields[0] : '';
                            $key = isset($fields[1]) ? $fields[1] : '';
                            if ($i % 2 == 0) {
                        ?>
                                <tr>
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?php echo $line_number; ?>">
                                        <td><input type="text" name="domain" value="<?php echo $domain; ?>"></td>
                                        <td><input type="text" name="key" value="<?php echo $key; ?>"></td>
                                        <td>
                                            <input type="submit" name="update" value="Update">
                                            <input type="submit" name="delete" value="Delete">
                                        </td>
                                    </form>
                                </tr>
                        <?php
                            }
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="column">
                <table>
                    <thead>
                        <tr>
                            <th>Domain</th>
                            <th>Key</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($entries as $line_number => $entry) {
                            $fields = explode(' ', $entry);
                            $domain = isset($fields[0]) ? $fields[0] : '';
                            $key = isset($fields[1]) ? $fields[1] : '';
                            if ($i % 2 != 0) {
                        ?>
                                <tr>
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?php echo $line_number; ?>">
                                        <td><input type="text" name="domain" value="<?php echo $domain; ?>"></td>
                                        <td><input type="text" name="key" value="<?php echo $key; ?>"></td>
                                        <td>
                                            <input type="submit" name="update" value="Update">
                                            <input type="submit" name="delete" value="Delete">
                                        </td>
                                    </form>
                                </tr>
                        <?php
                            }
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="section">
            <h2>Add Entry</h2>
            <form method="post">
                <div class="form-group">
                    <label for="domain">Domain:</label>
                    <input type="text" name="domain" id="domain" required>
                </div>
                <div class="form-group">
                    <label for="key">Key:</label>
                    <input type="text" name="key" id="key" required>
                </div>
                <div class="form-group">
                    <input type="submit" name="add" value="Add Entry">
                </div>
            </form>
        </div>
    </div>