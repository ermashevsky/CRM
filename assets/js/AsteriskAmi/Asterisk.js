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
                //console.log('Data:', data);
                //console.info(data); //All Data
                //event.emit(data.event.toLowerCase(), data);
                //client.emit('event', data);


            } else {
                //will be a weird response where you dont get an event back, just a success message for example
                //console.log('Data:', data);

            }

            if (data.event === "Dial" && data.subevent === "Begin") {
                
                var calleridnum = data.calleridnum;
                var dialstring = data.dialstring;
                
                var string = data.channel; // юрл в котором происходит поиск
                var regV = /103/gi;     // шаблон
                var result = string.match(regV);  // поиск шаблона в юрл
                var dialstring_rep = dialstring.replace("trunk/", "");
                console.info(dialstring_rep);
                
                    // вывод результата
                    if (result) {
                        client.emit('event', "Исходящий звонок на номер: " + dialstring_rep);
                    } else {
                        client.emit('event', "Входящий звонок с номера: " + calleridnum);
                    }

            }

//            if (data.event === "Newstate" && data.channelstate === "5") {
//                
//                
//                client.emit('event', "");
//            }
//            
//            if (data.event === "Newstate" && data.channelstate === "4") {
//                client.emit('event', "Исходящий звонок с номера: ");
//            }

            if (data.event === "Bridge" && data.bridgestate === "Link") {
                client.emit('event', "Разговор ...");
            }

            if (data.event === "Hangup" && data.cause === "16") {
                client.emit('event', "Повесили трубку!");
            }

            if (data.event === "Hangup" && data.cause === "17") {
                client.emit('event', "Занято!");
            }

        });
    });



}
exports.start = start;
