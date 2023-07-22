<?php
/*
 * Project: Update API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: home.php
 * Description: WordPress Update API
*/
?>

<div class="content-box">
    <h2>Allowed Hosts</h2>
    <div id="hosts_table">
        <?php echo $hostsTableHtml; ?>
    </div>
    <div class="home section">
        <h2>Add Entry</h2>
        <form class="entry-form" method="post" action="/home">
            <div class="form-group">
                <label for="domain">Domain:</label>
                <input type="text" name="domain" id="domain" required>
            </div>
            <div class="form-group">
                <label for="key">Key:</label>
                <input type="text" name="key" id="key" required>
            </div>
            <div class="form-group">
                <input type="submit" name="add_entry" value="Add Entry">
            </div>
        </form>
    </div>
</div>