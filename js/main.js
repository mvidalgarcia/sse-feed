var myFeeds = {
  mine: {
    url: "server/events.php",
    eventSource: null
  },
  david: {
    url: "http://156.35.95.69/server-sent-events/server/server.php",
    eventSource: null
  }
}


function handleEventSubscription(e) {
  if (e.checked)
    newSubscription(e.name)
  else
    closeSubscription(e.name)
}


function newSubscription(name) {
  myFeeds[name].eventSource = new EventSource(myFeeds[name].url)
  var source = myFeeds[name].eventSource

  source.addEventListener('message', function(e) {
    if (e.origin != 'http://localhost' && e.origin != 'http://156.35.95.69') {
    // if (e.origin != 'http://156.35.95.75' && e.origin != 'http://156.35.95.69') {
      alert('Danger! Origin ' + e.origin + ' unknown!')
      return
    }
    var data = JSON.parse(e.data)
    console.info("New alert from '" + name + "': " + e.data)
    addFeedItem(data)
    raiseNotification(data.message)
  }, false)
  console.info("New alert subscription with '" + name + "' source.")
}


function closeSubscription(name) {
  if (myFeeds[name].eventSource) {
    myFeeds[name].eventSource.close()
    console.info("Alert source '" + name + "' was closed.")
  }
  else {
    console.info("Couldn't close alert source '" + name + "' :(")
  }
}


function addFeedItem(data) {
  $("#list").prepend("<li>" + JSON.stringify(data) + "</li>")
}

function raiseNotification(msg) {
  if (window.Notification && Notification.permission === "granted") {
    new Notification(msg)
  }
}


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
