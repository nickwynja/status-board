<?php
define("transmissionhost", "localhost");
define("transmissionport", "9091");
define("transmissionlocation", "transmission/rpc");
define("transmissionrpc", "http://".transmissionhost.":".transmissionport."/".transmissionlocation);

define("downloadlocation", "/data2/Torrents/Download/");
define("completelocation", "/data2/Torrents/Complete/");

function get_transmission_session_id()
{
  $fp = @fsockopen(transmissionhost, transmissionport, $errno, $errstr, 30);
  
  if (!$fp)
  {
    throw new Exception("Can not connect to transmission: $errstr ($errno)");
  }
  
  $out = "GET /".transmissionlocation." HTTP/1.1\r\n";
  $out .= "Host: ".transmissionhost."\r\n";
  $out .= "Connection: Close\r\n\r\n";
  fwrite($fp, $out);
  $info = stream_get_contents($fp);
  fclose($fp);
  
  $info = explode("\r\n\r\n", $info);
  $info = explode("\r\n", $info[0]);
  
  $headers = array();
  foreach ($info as $i)
  {
    $i = explode(": ", $i);
    $headers[$i[0]] = $i[1];
  }
  
  return $headers["X-Transmission-Session-Id"];
}

try
{
  define("transmissionsessionid", get_transmission_session_id());
} catch (Exception $e)
{
  printf("   *** Exception: %s\n", $e->getMessage());
  exit();
}

function do_post_request($url, $data)
{
  $params = array();
  $params["http"] = array();
  $params["http"]["method"] = "POST";
  $params["http"]["content"] = $data;
  $params["http"]["header"] = "X-Transmission-Session-Id: ".transmissionsessionid."\r\n";
    
  $ctx = stream_context_create($params);
  $fp = @fopen($url, "rb", false, $ctx);
  if (!$fp)
  {
    throw new Exception("Problem with $url, $php_errormsg");
  }

  $response = @stream_get_contents($fp);
  if ($response === false)
  {
    throw new Exception("Problem reading data from $url, $php_errormsg");
  }

  return $response;
}

function roundUpTo($number, $increments) { 
    $increments = 1 / $increments; 
    return (ceil($number * $increments) / $increments); 
}

$request = array();
$request["method"] = "torrent-get";
$request["arguments"] = array();
$request["arguments"]["fields"] = array("id", "name", "doneDate", "haveValid", "totalSize");

try
{
  $reply = json_decode(do_post_request(transmissionrpc, json_encode($request)));
} catch (Exception $e)
{
  printf("   *** Exception: %s\n", $e->getMessage());
  exit();
}

$arr = $reply->arguments->torrents;
$arr = array_reverse($arr);
$now = time();

echo "<html>
      <body>
      <table id='projects'>";

$total = count($arr);
$count = 0;

foreach ($arr as $tor)
{
  if (($tor->haveValid < $tor->totalSize) || (($now - $tor->doneDate) < 360000))
  {
    $percent = round(($tor->haveValid / $tor->totalSize)*100);
    $value = roundUpTo((($percent / 10) / 1.25), 1);
    
    echo "<tr>";
    echo "<td class='title'>{$tor->name}</td>";
    echo "<td class='projectsBars'>";
    for($i=1; $i <= $value; $i++){
      echo "<div class='barSegment value{$i}'></div>";
    }
    echo "</td>";
    echo "<td class='percent' style='width: 20%; text-align: center;'>{$percent}%</td>";    
    echo "</tr>";
  } else {
  $count += 1;
  }
}

if ($count == $total) {
  echo "<tr>";
  echo "<td class='title'>No recent downloads</td>";
  echo "</tr>";
}

echo "</table></body></html>";

?>