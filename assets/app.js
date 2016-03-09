var socket = new WebSocket("ws://americano.adamwilliams.host:8765");
socket.onopen = function (event) {
    document.querySelector("#debug").appendChild(document.createTextNode("Connected to americano.adamwilliams.host!"));
    socket.send("SplitBill");
};

socket.onmessage = function (event) {
    document.querySelector("#debug").appendChild(document.createElement("br"));
    document.querySelector("#debug").appendChild(document.createTextNode("Message from americano! " + event.data));
};
