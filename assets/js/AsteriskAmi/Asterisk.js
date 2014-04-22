
function start() {
    var AsteriskAmi = require('./lib/AsteriskAmi'),
            AMI = new AsteriskAmi({host: '91.196.5.133', port: '5038', username: 'admin2', password: 'admin2'}),
    express = require('express');
    var mysql = require('mysql');
    var connection = mysql.createConnection({
        host: 'localhost',
        user: 'root',
        password: '11235813',
        database: 'DialogWebCRM'
    });

    connection.connect();

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
            if (data.event === 'Bridge' && data.bridgestate === "Link") {

                var answer = getDateTime();

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


            }

            if (data.event === 'Hangup' && data.cause === "16") {
                // Create row, using the insert id of the first query
                // as the exhibit_id foreign key.

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