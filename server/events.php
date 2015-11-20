<?php
date_default_timezone_set("Europe/Madrid");
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

$arrayEvents = array(
  "police" => "ISIS attack in Paris Le Bataclan",
  "firefighter" => "Fire in Madrid El Retiro",
);

$rand = rand(0, count($arrayEvents)-1);

/**
 * Constructs the SSE data format and flushes that data to the client.
 *
 * @param string $id Timestamp/id of this connection.
 * @param string $msg Line of text that should be transmitted.
 */
function sendMsg($event, $id, $msg) {
  global $arrayEvents;
  echo "event: $event" . PHP_EOL;
  echo "id: $id" . PHP_EOL;
  echo "data: {{$msg}, \"info\": \"$arrayEvents[$event]\"}" . PHP_EOL;
  echo PHP_EOL;
  ob_flush();
  flush();
}

$event = array_keys($arrayEvents)[$rand];
$serverTime = time();

sendMsg($event, $serverTime, '"time": ' . '"' . date("H:i:s", time()) . '"' );
