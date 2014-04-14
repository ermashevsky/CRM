function start() {
    var AsteriskAmi = require('./lib/AsteriskAmi'),
            AMI = new AsteriskAmi({host: '91.196.5.148', port: '5038', username: 'admin2', password: 'admin2'}),
    express = require('express');
    


    AMI.connect(function() {
    });

    //var event = new (require('events').EventEmitter);

    var app = express();

    // Создаем HTTP-сервер с помощью модуля HTTP, входящего в Node.js. 
    // Связываем его с Express и отслеживаем подключения к порту 8580. 
    var server = require('http').createServer(app).listen(8580);

    // Инициализируем Socket.IO так, чтобы им обрабатывались подключения 
    // к серверу Express/HTTP
    var io = require('socket.io').listen(server, {log: false});

//    app.use('/static', express.static(__dirname + '/static'));
//
//    app.get('/', function(req, res) {
//        res.sendfile(__dirname + '/index.html');
//    });

    //подписываемся на событие соединения нового клиента
    io.sockets.on('connection', function(client) {
        //подписываемся на событие message от клиента

        
        AMI.on('ami_data', function(data) {
            if (data.event) {
                //console.info(data); //All Data
                //event.emit(data.event.toLowerCase(), data);
                client.emit('event', data);


            } else {
                //will be a weird response where you dont get an event back, just a success message for example
                //console.log('Data:', data);

            }
        });
    });



}
exports.start = start;
