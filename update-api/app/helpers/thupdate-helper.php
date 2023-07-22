<?php
/*
 * Project: Update API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: thupdate-helper.php
 * Description: WordPress Update API
 */

$themes = glob(THEMES_DIR . "/*.zip");

function generateThemeTableRow($theme, $theme_name)
{
    return '<tr>
         <td>' . $theme_name . '</td>
         <td>
             <form name="delete_theme_form" action="/thupdate" method="POST">
                 <input type="hidden" name="theme_name" value="' . $theme . '">
                 <input class="th-submit" type="submit" name="delete_theme" value="Delete">
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

    $themesTableHtml = '<div class="row"><div class="column">
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
        $themesTableHtml .= generateThemeTableRow($theme, $theme_name);
    }
    $themesTableHtml .= '</tbody></table></div><div class="column"><table>
         <thead>
             <tr>
                 <th>Theme Name</th>
                 <th>Delete</th>
             </tr>
         </thead>
         <tbody>';
    foreach ($themes_column2 as $theme) {
        $theme_name = basename($theme);
        $themesTableHtml .= generateThemeTableRow($theme, $theme_name);
    }
    $themesTableHtml .= '</tbody></table></div></div>';
} else {
    $themesTableHtml = "No themes found.";
}
