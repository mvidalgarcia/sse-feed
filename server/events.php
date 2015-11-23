<?php
date_default_timezone_set("Europe/Madrid");
header("Content-Type: text/event-stream\n\n");
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

$TIME_BETWEEN_EVENTS = 20; // in seconds

$arrayEvents = array(
  0 => array(
    "message" => "ISIS attack in Paris Le Bataclan",
    "alertLevel" => "high",
    "source" => "French Police",
    "sourceIcon" => "photo128x128",
  ),
  1 => array(
    "message" => "Terrorist threat of bomb in Madrid city center",
    "alertLevel" => "medium",
    "source" => "Spanish Police",
    "sourceIcon" => "photo128x128",
  ),
  2 => array(
    "message" => "Podemos huge demonstration against Government in Puerta del Sol",
    "alertLevel" => "low",
    "source" => "Spanish Police",
    "sourceIcon" => "photo128x128",
  )
);


function sendMsg($time, $msg) {
  global $arrayEvents;
  $msg['timestamp'] = $time;
  echo "data: " . json_encode($msg) . PHP_EOL;
  echo PHP_EOL;
  ob_flush();
  flush();
}


function run() {
  global $arrayEvents, $TIME_BETWEEN_EVENTS;
  while(1) {
    $serverTime = time();
    $randIdx = rand(0, count($arrayEvents)-1);
    sendMsg($serverTime, $arrayEvents[$randIdx]);
    sleep($TIME_BETWEEN_EVENTS);
  }
}

run();

?>
