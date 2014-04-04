var http = require("http");

function start() {
        function onRequest(request, response) {
                var body = '<html>'+
                        '<head>'+
                '<meta http-equiv="Content-Type" content="text/html; '+
                'charset=UTF-8" />'+
                '<script src="http://code.jquery.com/jquery-1.10.2.js"></script>'+
                '<script type="text/javascript">'+
                '$.getScript("/socket.io/socket.io.js", function(){'+
                'var socket = new io.Socket(localhost, {port: 8580, rememberTransport: false});'+
                'socket.connect();'+
                'var auto_refresh = setInterval( function() {'+
                'socket.on("news", function(data) {'+
                '$("#container").prepend(data.event);'+
                '});'+
                '}, 2000);'+
                        '});'+
                        '</script>'+
                '</head>'+
                '<body>'+
                '<b>Some event = <div id="container"></div></b>'+
                        '</body>'+
                        '</html>';
                response.writeHead(200, {"Content-Type": "text/html"});
                response.write(body);
                response.end();
        }

        var server = http.createServer(onRequest).listen(8580);
        console.log("Server has started.");
}

exports.start = start;
