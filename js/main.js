var myFeeds = {
  mine: {
    url: "server/events.php",
    eventSource: null
  },
  david: {
    url: "http://156.35.95.69/server-sent-events/server/server.php",
    eventSource: null
  }
};


function handleEventSubscription(e) {
  if (e.checked)
    newSubscription(e.name);
  else
    closeSubscription(e.name);
}


function newSubscription(name) {
  myFeeds[name].eventSource = new EventSource(myFeeds[name].url);
  var source = myFeeds[name].eventSource;

  source.addEventListener('message', function(e) {
    if (e.origin != 'http://localhost' &&
        e.origin != 'http://156.35.95.75' &&
        e.origin != 'http://156.35.95.69') {
      alert('Danger! Origin ' + e.origin + ' unknown!');
      return;
    }
    var data = JSON.parse(e.data);
    console.info("New alert from '" + name + "': " + e.data);
    addFeedItem(data);
    raiseNotification(data);
  }, false);
  console.info("New alert subscription to '" + name + "' source.");
}


function closeSubscription(name) {
  if (myFeeds[name].eventSource) {
    myFeeds[name].eventSource.close();
    console.info("Alert source '" + name + "' was closed.");
  }
  else {
    console.info("Couldn't close alert source '" + name + "' :(");
  }
}


function addFeedItem(data) {
  var alertLevelClass;
  switch (data.alertLevel) {
    case "low":
      alertLevelClass = "success";
      break;
    case "medium":
      alertLevelClass = "warning";
      break;
    case "high":
      alertLevelClass = "danger";
      break;
  }
  $("#list").prepend("<li><div class='alert alert-"+alertLevelClass+"'>" + data.message + "</div></li>");
}


function raiseNotification(data) {
  if (window.Notification && Notification.permission === "granted") {
    var icon;
    switch (data.alertLevel) {
      case "low":
        icon = "icons/green-alert.png";
        break;
      case "medium":
        icon = "icons/yellow-alert.png";
        break;
      case "high":
        icon = "icons/red-alert.png";
        break;
    }

    var n = new Notification(data.source, {body: data.message, icon: icon});
    setTimeout(n.close.bind(n), 10000);
  }
}

/* On load */
$(function() {

  // Ask for notification permission
  if (window.Notification && Notification.permission !== "granted") {
    Notification.requestPermission(function (status) {
      if (Notification.permission !== status) {
        Notification.permission = status;
      }
    });
  }

})
