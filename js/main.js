var source = new EventSource('server/events.php')

source.addEventListener('message', function(e) {
  if (e.origin != 'http://localhost') {
    alert('Origin was not http://localhost');
    return;
  }
  console.log(e.data)
  addFeedItem(e.data)
}, false)

function addFeedItem(data) {
  $("#list").append("<li>" + data + "</li>")
}
