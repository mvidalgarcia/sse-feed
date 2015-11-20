var source = new EventSource('server/events.php')

var eventsSubscribed = {
  "police": true,
  "firefighter": false,
}


$.each(eventsSubscribed, function(eventType, subscribed) {
  if (subscribed) {
    source.addEventListener(eventType, function(e) {
      if (e.origin != 'http://localhost') {
        alert('Origin was not http://localhost')
        return
      }
      var obj = JSON.parse(e.data);
      addFeedItem(obj.info + " at " + obj.time)
    }, false)
  }
  else {
    console.info("Not subscribed to " + eventType + " events.")
  }

})


function addFeedItem(data) {
  $("#list").append("<li>" + data + "</li>")
}


$(function() {

})
