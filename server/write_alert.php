<?php

/* Validations */

if (empty($_POST['msg']) || strlen(trim($_POST['msg'])) === 0) {
  echo "Alert message cannot be empty";
  die();
}

if (empty($_POST['level'])) {
  echo "Alert level cannot be empty";
  die();
}

$file = fopen("alerts.txt", "a") or die ("Unable to open file!");
$serverTime = time();
$source = "Marco's alert";

$alert = array(
  "message" => $_POST['msg'],
  "alertLevel" => $_POST['level'],
  "source" => "Marco's alert",
  "timestamp" => time()
);

fwrite($file, json_encode($alert) . "\t" . time() . "\n");
fclose($file);

echo "New alert created: " . json_encode($alert);

?>
