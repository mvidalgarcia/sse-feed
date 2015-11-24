<?php
date_default_timezone_set("Europe/Madrid");
header("Content-Type: text/event-stream\n\n");
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

$TIME_BETWEEN_EVENTS = 10; // in seconds
$lastTsRead = time();

$arrayEvents = array(
  0 => array(
    "message" => "ISIS attack in Paris Le Bataclan",
    "alertLevel" => "high",
    "source" => "Marco's alert"
  ),
  1 => array(
    "message" => "Terrorist threat of bomb in Madrid city center",
    "alertLevel" => "medium",
    "source" => "Marco's alert"
  ),
  2 => array(
    "message" => "Podemos huge demonstration against Government in Puerta del Sol",
    "alertLevel" => "low",
    "source" => "Marco's alert"
  ),
  3 => array(
    "message" => "Traffic jam in every access to Barcelona. Retentions of 2 hours.",
    "alertLevel" => "low",
    "source" => "Marco's alert"
  ),
  4 => array(
    "message" => "Major cocaine dealer in the world busted in Tarifa, Cádiz.",
    "alertLevel" => "medium",
    "source" => "Marco's alert"
  ),
  5 => array(
    "message" => "Brussels arrest five suspected members of ISIS",
    "alertLevel" => "high",
    "source" => "Marco's alert"
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

function newAlertsInFile() {
  global $lastTsRead;
  if ($lines = file("alerts.txt")) {
    $line = $lines[count($lines) - 1]; // last index, most recent
    $lineArray = preg_split("/[\t]/", $line);
    $alert = $lineArray[0];
    $ts = $lineArray[1];
    if ($lastTsRead < intval($ts)) {
      $lastTsRead = intval($ts);
      return $alert;
    }
    else // no new alerts
      return false;
  }
  else // no alerts in file
    return false;
}

function run() {
  global $arrayEvents, $TIME_BETWEEN_EVENTS;
  while (1) {
    if ($alert = newAlertsInFile()) {
      echo "data: " . $alert . PHP_EOL;
      echo PHP_EOL;
      ob_flush();
      flush();
    }
    else {
      $serverTime = time();
      $randIdx = rand(0, count($arrayEvents)-1);
      sendMsg($serverTime, $arrayEvents[$randIdx]);
    }
    sleep($TIME_BETWEEN_EVENTS);
  }
}

run();

?>
