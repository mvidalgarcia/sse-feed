<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

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

}

else if ($_SERVER['REQUEST_METHOD'] == 'GET') {

?>

<!DOCTYPE html>
<html>
  <head>
    <title>New alert</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  </head>
  <body>
    <form>
      Alert message: <input type="text" name="msg" required><br>
      Alert level: <input type="radio" name="level" value="low" checked>Low
      <input type="radio" name="level" value="medium">Medium
      <input type="radio" name="level" value="high">High<br>
      <input type="submit">
      <p id="feedback"></p>
    </form>
  </body>
  <script>
    $('form').submit(function(e) {
      e.preventDefault()
      var message = $('[name="msg"]').val()
      var alertLevel = $('[name="level"]:checked').val()
      $.post("<?php echo $_SERVER['PHP_SELF']; ?>",
        {
          msg: message,
          level: alertLevel
        },
        function (data, status) {
          if (status == "success"){
            $("#feedback").html(data)
            $('[name="msg"]').val("")
          }
        }
      )
    })
  </script>
</html>

<?php
}
?>
