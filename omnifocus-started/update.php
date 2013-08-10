<?php

  $script_path = dirname(__FILE__);
  $output = shell_exec('osascript ' . $script_path . '/started.applescript');
  $j = json_decode($output, TRUE);

foreach ($j as $group)
{
  print "<h3>" . $group[0] . ":</h3>";
  print "<ul>";
  foreach ($group[1] as $item)
  {
    print "<li>&#9633;&nbsp;" . $item . "</li>";
  }
  print "</ul>";
}
