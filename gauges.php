<?php 

$gaugeID = 'YOURGAUGEID';
$APIurl = 'https://secure.gaug.es/gauges/' . $gaugeID;
$auth = array(
"X-Gauges-Token: YOURAUTHTOKEN"
);

function get_data($url, $headers) {
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

$trafficURL = $APIurl.'/traffic';
$json = get_data($trafficURL, $auth);
$j = json_decode($json);

$days_ago = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 7, date("Y")));

foreach ($j->traffic as $day) {
  if ($day->date > $days_ago) {
     $people_data[] = array(
      "title" => date('D', strtotime($day->date)),
       "value" => $day->people
    );
  }
}

$people = array(
    "title" => "People",
    "color" => "lightGray",
    "datapoints" => $people_data
    );
    
foreach ($j->traffic as $day) {
  if ($day->date > $days_ago) {
    $views_data[] = array(
      "title" => date('D', strtotime($day->date)),
      "value" => $day->views
    );
  }
}

$views = array(
    "title" => "Views",
    "color" => "green",
    "datapoints" => $views_data
    );

$data_sequences = array($people,$views);

$data = array(
  "graph" => array(
    "title" => "Hack/Make — Traffic",
    "type" => "bar",
    "xAxis" => array(
      "showEveryLabel" => true
    ),
    "datasequences" => $data_sequences
  )
);

$json_data = json_encode($data);
echo $json_data;