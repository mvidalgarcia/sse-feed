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
      alert('Danger! Origin ' + e.origin + ' unknown!');
      return
    }
    console.log(e.data)
    addFeedItem(e.data)
  }, false)
  console.info("New event subscription with '" + name + "' source.")
}


function closeSubscription(name) {
  if (myFeeds[name].eventSource) {
    myFeeds[name].eventSource.close()
    console.info("Event source '" + name + "' was closed.")
  }
  else {
    console.info("Couldn't close event source '" + name + "' :(")
  }
}


function addFeedItem(data) {
  $("#list").append("<li>" + data + "</li>")
}


$(function() {

})
