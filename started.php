<!DOCTYPE html >
<html>
<head>
  <style>
    body {
      padding: 0; margin: 0;
    }

    #header {
      background-color: #222;
      position: fixed;
      width: 100%;
    }

    #content {
      padding-top: 50px;
    }

    h2 {
      border-bottom: 2px solid #111;
      color: #555;
      font-family: "Roadgeek 2005 Series C";
      font-weight: normal;
      font-size: 26px;
      padding: 13px 0 10px 0;
      margin: 0;
      text-align: center;
      text-transform: uppercase;
    }

    h3 {
      color: #808080;
      font-weight: normal;
      margin: 10px 0 10px 6px;
    }

    ul {
      font-size: 20px;
      list-style: none;
      padding-left: 20px;
      margin: 0;
    }

    li {
      list-style-type: none;
      padding: 0;
    }
  </style>
</head>

<?php 
  $script_path = dirname(__FILE__);
  $output = shell_exec('osascript ' . $script_path . '/started.applescript');
  $j = json_decode($output, TRUE);
?>

<body>
  <div id='header'>
    <h2>OmniFocus &mdash; Started</h2>
  </div>
  <div id='content'></div>
    <?php foreach ($j as $group): ?>    
      <h3><?php echo $group[0]?>:</h3>
      <ul>
        <?php foreach ($group[1] as $item): ?>
          <li>&#9633;&nbsp; <?php echo $item?></li>
        <?php endforeach; ?>
      </ul>
    <?php endforeach; ?>
  </div>
</body>
</html>