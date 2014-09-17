//91.196.5.133 - office
function start() {
    var AsteriskAmi = require('./lib/AsteriskAmi'),
            AMI = new AsteriskAmi({host: '91.196.5.133', port: '5038', username: 'admin2', password: 'admin2'}),
    express = require('express');
    var storage = require('node-persist');
    var mysql = require('mysql');

    

//    var db_config = {
//        host: 'localhost',
//        user: 'root',
//        password: '11235813',
//        database: 'DialogWebCRM'
//    };
//    //connection.connect();
//    var connection;

    function handleDisconnect() {
        //connection = mysql.createConnection(db_config); // Recreate the connection, since
        connection = mysql.createConnection({
        host: 'localhost',
        user: 'root',
        password: '11235813',
        database: 'DialogWebCRM'
    });
        // the old one cannot be reused.

        connection.connect(function(err) {              // The server is either down
            if (err) {                                     // or restarting (takes a while sometimes).
                console.log('error when connecting to db:', err);
                setTimeout(handleDisconnect, 2000); // We introduce a delay before attempting to reconnect,
            }                                     // to avoid a hot loop, and to allow our node script to
        });                                     // process asynchronous requests in the meantime.
        // If you're also serving http, display a 503 error.
        connection.on('error', function(err) {
            console.log('db error', err);
            if (err.code === 'PROTOCOL_CONNECTION_LOST') { // Connection to the MySQL server is usually
                handleDisconnect();                         // lost due to either server restart, or a
            } else {                                      // connnection idle timeout (the wait_timeout
                throw err;                                  // server variable configures this)
            }
        });
    }

    handleDisconnect();

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

    AMI.on('ami_data', function(data) {
        if (data.event) {
            console.info(data); //All Data

            if (data.event === 'Dial' && data.subevent === "Begin") {
                // Create row, using the insert id of the first query
                // as the exhibit_id foreign key.
                var sql = 'INSERT INTO cdr (src,clid,dst,channel,dstchannel,uniqueid) ' +
                        'VALUES("' + data.calleridnum + '", "' + data.calleridname + '", "' + data.dialstring + '", "' + data.channel + '", "' + data.destination + '", "' + data.uniqueid + '")';
                connection.query(sql);
            }

            var checkBegin;
            if (data.event === 'Newchannel' && data.exten === "*8") {
                console.info('Перехват вызова !!!');
                storage.setItem('interception', 'call_interception');
            }

            if (data.event === 'Bridge' && data.bridgestate === "Link") {
                var answer = getDateTime();

                if (storage.getItem("interception") === 'call_interception') {
                    var insertBeginRecord = 'INSERT INTO cdr (src,clid,dst,channel,dstchannel,uniqueid,answer) ' +
                            'VALUES("' + data.callerid1 + '", "' + data.callerid1 + '", "' + data.callerid2 + '", "' + data.channel1 + '", "' + data.channel2 + '", "' + data.uniqueid2 + '","' + answer + '")';
                    connection.query(insertBeginRecord);
                    console.info(insertBeginRecord);
                }

                var updateLinkCall = 'UPDATE cdr SET answer="' + answer + '" where uniqueid="' + data.uniqueid1 + '" and dstchannel="' + data.channel2 + '"';
                connection.query(updateLinkCall);
                console.info(updateLinkCall);

                var channel = data.channel2;
                var re = /(.*\/)(\d*)(-.*)/;

                var getNumber = channel.replace(re, "$2");

                var updateEndCall = 'UPDATE cdr SET disposition="ANSWER_BY", answerext="' + getNumber + '" where billsec="0" and cause="16" and uniqueid="' + data.uniqueid1 + '"';
                connection.query(updateEndCall);
                console.info(updateEndCall);

                var updateEndCall_26 = 'UPDATE cdr SET disposition="ANSWER_BY", answerext="' + getNumber + '" where billsec="0" and cause="26" and uniqueid="' + data.uniqueid1 + '"';
                connection.query(updateEndCall_26);
                console.info(updateEndCall_26);

                var regXfer = new RegExp('xfer', 'ig');
                var result_xfer = channel.match(regXfer);  // поиск шаблона в юрл

                if (result_xfer) {

                    var channel_xfer = data.channel2;

                    var re = /(.*\/)(\d*)(@.*)/;
                    var re2 = /(.*\/)(\d*)(@[a-z]*)(-[a-z]*)(-[a-z]*)(-[a-zA-Z0-9_]*)(;\d)/;

                    var getNumber = channel_xfer.replace(re, "$2");
                    var getChannel = channel_xfer.replace(re2, "$1$2$3$4$5$6");

                    var updateEndCall_xfer = 'UPDATE cdr SET src="' + data.callerid1 + '", dst="' + getNumber + '" where channel like "' + getChannel + '%"';
                    connection.query(updateEndCall_xfer);
                    console.info(updateEndCall_xfer);
                }
                storage.removeItem("interception");
            }

            if (data.event === 'Hangup' && data.cause === "16") {
                // Create row, using the insert id of the first query
                // as the exhibit_id foreign key.

                var channel = data.channel;
                var template = /(.*\/)(\d*)(-.*)(<ZOMBIE>)/;
                var getChannelWithoutZombie = channel.replace(template, "$1$2$3");
                var checkZombie = channel.replace(template, "$4");
                //Проверка перехвата и наличия в канале <ZOMBIE>
                if (checkZombie === '<ZOMBIE>') {
                    console.info("Тута Зомбаки!!!!!!");

                    connection.query('SELECT start as start, answer as answer from cdr where dstchannel="' + getChannelWithoutZombie + '"', function(err, rows, fields) {
                        if (err)
                            throw err;

                        for (var i in rows) {
                            var end = getDateTime();
                            var duration_seconds = duration(rows[i].start);

                            if (rows[i].answer !== "0000-00-00 00:00:00") {

//                            var billsec_seconds = billsec(rows[i].answer);
//
//                            var updateEndCall = 'UPDATE cdr SET end="' + end + '", disposition="ANSWERED", cause="16" , duration="' + duration_seconds + '", billsec="' + billsec_seconds + '" where dstchannel="' + data.channel + '"';
//                            connection.query(updateEndCall);
//                            console.info(updateEndCall);
                            } else {
                                var updateEndCall = 'UPDATE cdr SET end="' + end + '", disposition="CALL INTERCEPTION", cause="16" , duration="' + duration_seconds + '", billsec="0" where dstchannel="' + getChannelWithoutZombie + '"';
                                connection.query(updateEndCall);
                                console.info(updateEndCall);
                            }
                        }
                    });
                }

                connection.query('SELECT start as start, answer as answer from cdr where dstchannel="' + data.channel + '"', function(err, rows, fields) {
                    if (err)
                        throw err;

                    for (var i in rows) {
                        var end = getDateTime();
                        var duration_seconds = duration(rows[i].start);

                        if (rows[i].answer !== "0000-00-00 00:00:00") {

                            var billsec_seconds = billsec(rows[i].answer);

                            var updateEndCall = 'UPDATE cdr SET end="' + end + '", disposition="ANSWERED", cause="16" , duration="' + duration_seconds + '", billsec="' + billsec_seconds + '" where dstchannel="' + data.channel + '"';
                            connection.query(updateEndCall);
                            console.info(updateEndCall);
                        } else {
                            var updateEndCall = 'UPDATE cdr SET end="' + end + '", disposition="NO ANSWER", cause="16" , duration="' + duration_seconds + '", billsec="0" where dstchannel="' + data.channel + '"';
                            connection.query(updateEndCall);
                            console.info(updateEndCall);
                        }
                    }
                });
            }

            if (data.event === 'Hangup' && data.cause === "17") {
                // Create row, using the insert id of the first query
                // as the exhibit_id foreign key.
                connection.query('SELECT start as start, answer as answer from cdr where dstchannel="' + data.channel + '"', function(err, row, fields) {
                    if (err)
                        throw err;

                    for (var i in row) {
                        var end = getDateTime();
                        var duration_seconds = duration(row[i].start);
                        if (row[i].answer !== "0000-00-00 00:00:00") {

                            var billsec_seconds = billsec(row[i].answer);
                            var updateEndCall = 'UPDATE cdr SET end="' + end + '", disposition="BUSY", cause="17", duration="' + duration_seconds + '", billsec="' + billsec_seconds + '" where dstchannel="' + data.channel + '"';
                            connection.query(updateEndCall);
                            console.info(updateEndCall);
                        } else {
                            var updateEndCall = 'UPDATE cdr SET end="' + end + '", disposition="BUSY", cause="17", duration="' + duration_seconds + '", billsec="0" where dstchannel="' + data.channel + '"';
                            connection.query(updateEndCall);
                            console.info(updateEndCall);
                        }
                    }
                });
            }

            if (data.event === 'Hangup' && data.cause === "18") {
                // Create row, using the insert id of the first query
                // as the exhibit_id foreign key.
                connection.query('SELECT start as start, answer as answer from cdr where dstchannel="' + data.channel + '"', function(err, row, fields) {
                    if (err)
                        throw err;

                    for (var i in row) {
                        var end = getDateTime();
                        var duration_seconds = duration(row[i].start);
                        if (row[i].answer !== "0000-00-00 00:00:00") {

                            var billsec_seconds = billsec(row[i].answer);
                            var updateEndCall = 'UPDATE cdr SET end="' + end + '", disposition="FAILED", cause="18", duration="' + duration_seconds + '", billsec="' + billsec_seconds + '" where dstchannel="' + data.channel + '"';
                            connection.query(updateEndCall);
                            console.info(updateEndCall);
                        } else {
                            var updateEndCall = 'UPDATE cdr SET end="' + end + '", disposition="FAILED", cause="18", duration="' + duration_seconds + '", billsec="0" where dstchannel="' + data.channel + '"';
                            connection.query(updateEndCall);
                            console.info(updateEndCall);
                        }
                    }
                });
            }

            if (data.event === 'Hangup' && data.cause === "19") {
                // Create row, using the insert id of the first query
                // as the exhibit_id foreign key.

                connection.query('SELECT start as start, answer as answer from cdr where dstchannel="' + data.channel + '"', function(err, row, fields) {
                    if (err)
                        throw err;

                    for (var i in row) {

                        var end = getDateTime();
                        var duration_seconds = duration(row[i].start);
                        if (row[i].answer !== "0000-00-00 00:00:00") {

                            var billsec_seconds = billsec(row[i].answer);

                            var updateEndCall = 'UPDATE cdr SET end="' + end + '", disposition="NO ANSWER", cause="19", duration="' + duration_seconds + '", billsec="' + billsec_seconds + '" where dstchannel="' + data.channel + '"';
                            connection.query(updateEndCall);
                            console.info(updateEndCall);
                        } else {
                            var updateEndCall = 'UPDATE cdr SET end="' + end + '", disposition="NO ANSWER", cause="19", duration="' + duration_seconds + '", billsec="0" where dstchannel="' + data.channel + '"';
                            connection.query(updateEndCall);
                            console.info(updateEndCall);
                        }
                    }
                });
            }

            if (data.event === 'Hangup' && data.cause === "34") {
                // Create row, using the insert id of the first query
                // as the exhibit_id foreign key.

                var end = getDateTime();

                var updateEndCall = 'UPDATE cdr SET end="' + end + '", disposition="FAILED", cause="34" where dstchannel="' + data.channel + '"';
                connection.query(updateEndCall);
                console.info(updateEndCall);
            }

            if (data.event === 'Hangup' && data.cause === "21") {
                // Create row, using the insert id of the first query
                // as the exhibit_id foreign key.

                var end = getDateTime();

                var updateEndCall = 'UPDATE cdr SET end="' + end + '", disposition="FAILED", cause="21" where dstchannel="' + data.channel + '"';
                connection.query(updateEndCall);
                console.info(updateLinkCall);
            }

            if (data.event === 'Hangup' && data.cause === "26") {
                // Create row, using the insert id of the first query
                // as the exhibit_id foreign key.

                var end = getDateTime();

                var updateEndCall = 'UPDATE cdr SET end="' + end + '", disposition="NO ANSWER", cause="26" where dstchannel="' + data.channel + '"';
                connection.query(updateEndCall);
                console.info(updateLinkCall);
            }

            if (data.event === 'Hangup' && data.cause === "1") {
                // Create row, using the insert id of the first query
                // as the exhibit_id foreign key.

                var end = getDateTime();

                var updateEndCall = 'UPDATE cdr SET end="' + end + '", disposition="FAILED", cause="1" where dstchannel="' + data.channel + '"';
                connection.query(updateEndCall);
                console.info(updateLinkCall);
            }

        } else {
            //will be a weird response where you dont get an event back, just a success message for example
            //console.log('Data:', data);

        }
    });
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

function getDateTime() {

    var date = new Date();

    var hour = date.getHours();
    hour = (hour < 10 ? "0" : "") + hour;

    var min = date.getMinutes();
    min = (min < 10 ? "0" : "") + min;

    var sec = date.getSeconds();
    sec = (sec < 10 ? "0" : "") + sec;

    var year = date.getFullYear();

    var month = date.getMonth() + 1;
    month = (month < 10 ? "0" : "") + month;

    var day = date.getDate();
    day = (day < 10 ? "0" : "") + day;

    return year + "-" + month + "-" + day + " " + hour + ":" + min + ":" + sec;

}

function duration(start) {
    var end = getDateTime();
    var dateFormat = require('dateformat');
    var start_duration_formated = new Date(start);
    var end_duration_formated = new Date(end);
    var duration_seconds = Math.round((end_duration_formated - start_duration_formated) / 1000);

    console.info(start_duration_formated);
    console.info(end_duration_formated);

    return duration_seconds;
}

function billsec(answer) {
    var end = getDateTime();
    var dateFormat = require('dateformat');
    var start_billsec_formated = new Date(answer);
    var end_billsec_formated = new Date(end);
    var billsec = Math.round((end_billsec_formated - start_billsec_formated) / 1000);

    console.info(start_billsec_formated);
    console.info(end_billsec_formated);

    return billsec;
}