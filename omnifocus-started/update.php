<?php
  putenv("HOME=/Users/brain");
  $tasks['Inbox'] = `. /Users/brain/.bashrc && /usr/local/bin/ofexport -E -a done=any -I -p name='Inbox' --tasks -T html-lite`;
  $tasks['Started'] = `. /Users/brain/.bashrc && /usr/local/bin/ofexport -E -a done=any -I -t "started='to today'" --tasks -T html-lite`;
  foreach ($tasks as $type => $list)
  {
    if (strpos($list, 'li') !== false)
    {
      print "<h3>".$type."</h3>";
      print $list;
    }
  }