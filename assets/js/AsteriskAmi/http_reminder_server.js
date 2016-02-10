var express = require('express');
var app = express();
var schedule = require('node-schedule');
var mysql      = require('mysql');

var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : '11235813',
  database : 'office_crm_db'
});

connection.connect();

var server = require('http').createServer(app).listen(3010);
var io = require('socket.io').listen(server);

//app.use(express.static(__dirname + '/'));
//console.log('Express server started on port %s', app);

io.sockets.on('connection', function (socket) {

connection.query('SELECT id,reminder_description, reminder_date, user_id from reminders where status != "1" order by reminder_date DESC', function(err, rows, fields) {
  if (err) throw err;
    
        var datetime = new Array();
	var day = new Array();
	var month = new Array();
	var year = new Array();
	var hours = new Array();
	var minutes = new Array();
	var seconds = new Array();
	var reminder = new Array();
	var id = new Array();
        var userid = new Array();
    
    for (var i = 0; i < rows.length; i++) {

	datetime = new Date(rows[i].reminder_date);
	day = datetime.getDate();
	month = datetime.getMonth();
	year = datetime.getFullYear();
	hours = datetime.getHours();
	minutes = datetime.getMinutes();
	seconds = datetime.getSeconds();
	reminder = rows[i].reminder_description;
	id = rows[i].id;
        userid = rows[i].user_id;

	schedule.scheduleJob("Task-"+i, new Date(year, month, day, hours, minutes, seconds), function(params) {	
		socket.emit('news', { datetime: datetime, description:reminder,userid:userid });
		console.info('OK');
                
                schedule.cancelJob("Task-"+i);
                
		var updateReminder = 'UPDATE reminders SET status="1" where id="'+id+'"';
		connection.query(updateReminder);
                
	});
	
    }

});




});
