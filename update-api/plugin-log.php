<?php

$log_file = '../plugin.log'; // path to the log file

if (file_exists($log_file)) {
  // read the log file into an array
  $log_array = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

  // group the log entries by domain name and date
  $log_by_domain_date = [];
  foreach ($log_array as $entry) {
    list($domain, $date, $status) = explode(' ', $entry);
    $log_by_domain_date[$domain][$date] = $status;
  }

  // sort the domains alphabetically
  ksort($log_by_domain_date);

  // keep only the latest dated entry for each domain
  $log_by_domain = [];
  foreach ($log_by_domain_date as $domain => $entries) {
    krsort($entries); // sort by date in descending order
    $latest_date = key($entries);
    $latest_status = $entries[$latest_date];
    $log_by_domain[$domain] = ['date' => $latest_date, 'status' => $latest_status];
  }

  // display the log entries in a single row
  echo '<div class="log-row">';

  foreach ($log_by_domain as $domain => $entry) {
    // display the latest dated entry for the domain
    echo '<div class="log-sub-box">';
    echo '<h3>' . $domain . '</h3>';
    if ($entry['status'] == 'Failed') {
      echo '<p class="log-entry" style="color:red;">' . $entry['date'] . ' ' . $entry['status'] . '</p>';
    } else {
      echo '<p class="log-entry" style="color:green;">' . $entry['date'] . ' ' . $entry['status'] . '</p>';
    }
    echo '</div>';
  }

  echo '</div>';

  // write the updated log array back to the file
  $log_array_new = [];
  foreach ($log_by_domain_date as $domain => $entries) {
    krsort($entries); // sort by date in descending order
    $latest_date = key($entries);
    $latest_entry = $domain . ' ' . $latest_date . ' ' . $entries[$latest_date];
    $log_array_new[] = $latest_entry;
  }
  file_put_contents($log_file, implode("\n", $log_array_new));
} else {
  echo 'Log file not found.';
}