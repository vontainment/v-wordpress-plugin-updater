<?php

$log_file = '../accesslog.log'; // path to the log file

if (file_exists($log_file)) {
  // read the log file into an array
  $log_array = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

  // group the log entries by domain name
  $log_by_domain = [];
  foreach ($log_array as $entry) {
    list($domain, $date, $status) = explode(' ', $entry);
    $log_by_domain[$domain][] = ['date' => $date, 'status' => $status];
  }

  // sort the domains alphabetically
  ksort($log_by_domain);

  // display the log entries in five columns
  $total_domains = count($log_by_domain);
  $domains_per_column = $total_domains > 0 ? ceil($total_domains / 5) : 0;
  $current_column = 1;
  $current_domain = 1;

  echo '<div class="log-columns">';

  foreach ($log_by_domain as $domain => $entries) {
    // display the domain name
    echo '<div class="log-sub-box">';
    echo '<h3>' . $domain . '</h3>';

    // display the most recent entry for the domain
    $last_entry = end($entries);
    echo '<p class="log-entry">' . $last_entry['date'] . ' ' . $last_entry['status'] . '</p>';
    echo '</div>';

    // if this is the last domain in the column, close the column div and start a new one
    if (($current_domain % $domains_per_column == 0) || ($current_column == 5 && $current_domain == $total_domains)) {
      echo '</div><div class="log-columns">';
      $current_column++;
      $domains_left = $total_domains - $current_domain;
      $domains_per_column = $domains_left > 0 ? ceil($domains_left / (5 - $current_column + 1)) : 0;
    }

    $current_domain++;
  }

  echo '</div>';
} else {
  echo 'Log file not found.';
}
