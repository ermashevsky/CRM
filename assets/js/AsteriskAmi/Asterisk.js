function start() {
    var AsteriskAmi = require('./lib/AsteriskAmi'),
        AMI = new AsteriskAmi( { host: '91.196.5.148', port:'5038', username: 'admin2', password: 'admin2' } ),
        http = require('http'),
        io = require('socket.io');

    var event = new (require('events').EventEmitter);
    var socket = io.listen(5038);

    AMI.connect(function(){
    });

    AMI.on('ami_data', function(data){
        if (data.event){
            //console.log('Data:', data);
            console.info(data); //All Data
            event.emit(data.event.toLowerCase(), data);

        } else {
        //will be a weird response where you dont get an event back, just a success message for example
            console.log('Data:', data);
        }
        
        if(data.event === "Dial" && data.subevent === "Begin"){
            console.info("Набор номера");
            console.info(data.calleridnum);
            console.info(data.dialstring);
            //socket.json.send({'event': 'connected'});
        }
        
        if(data.event === "Newstate" && data.channelstate === "5"){
            console.info("Звонок");
            console.info(data.channelstatedesc);
            
        }
        
        if(data.event === "Hangup" && data.cause==="16"){
            console.info("Звонок состоялся!");
            console.info(data.cause);
            console.info(data.causetxt);
        }
        
        if(data.event === "Dial" && data.subevent === "End"){
            console.info("Звонок завершен!");
            console.info(data.dialstatus);
        }
        
    });

    AMI.on('peerstatus', function(data) {
       socket.broadcast({event:data.event});
    });
}
exports.start = start;
