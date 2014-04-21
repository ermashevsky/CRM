<!-- /project_dir/index.html -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex,nofollow"/>
        <title></title>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
        <script src="/assets/js/bootstrap-button.js"></script>
        <script src="/assets/js/bootstrap-fileupload.js"></script>
        <script src="/assets/js/bootstrap-notify.js"></script>
        <script src="/assets/js/jquery.uploadify.min.js"></script>
        <script src="/assets/js/bootbox.min.js"></script>
        <script src="/assets/js/bootstrap-progressbar.js"></script>
        <script src="/assets/js/bootstrap-tagsinput.js"></script>
        <script type="text/javascript" src="/assets/js/notifIt.js"></script>
        <script type="text/javascript" src="/assets/js/jquery.cookie.js"></script>

        <link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="/assets/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="/assets/css/bootstrap-button.css" rel="stylesheet">
        <link href="/assets/css/bootstrap-fileupload.css" rel="stylesheet">
        <link href="/assets/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/assets/css/uploadify.css" />
        <link rel="stylesheet" href="/assets/css/bootstrap-tagsinput.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/notifIt.css">

        <script src="http://localhost:8580/socket.io/socket.io.js"></script>
        <script type="text/javascript">
            // javascript code

            // /project_dir/index.html
            $(document).ready(function() {
                var socket = io.connect('http://localhost:8580');
                var messages = $("#messages");

                function msg_system(message, type) {

                    var m = notif({
                        msg: message,
                        type: type,
                        width: 300,
                        height: 300,
                        opacity: 1,
                        autohide: false,
                        position: "center",
                        multiline: true
                    });

                    //console.log(message);
                    messages
                            .append(m)
                            .scrollTop(messages[0].scrollHeight);
                }

                socket.on('connecting', function() {
                    $('#server_status').empty();
                    $('#server_status').append("Соединение ...");
                    $('#server_status').removeClass("label label-important").addClass("label label-success");
                });

                socket.on('connect', function() {
                    $('#server_status').empty();
                    $('#server_status').append("Соединение установлено");
                    $('#server_status').removeClass("label label-important").addClass("label label-success");
                });

                socket.on('disconnect', function() {
                    $('#server_status').empty();
                    $('#server_status').append("Соединение разорвано");
                    $('#server_status').removeClass("label label-success").addClass("label label-important");
                });

                socket.on('event', function(data) {

                    console.info(data);
                    $('.msg system').empty();

                    var phone_number = $('#hidden_phone_number').val();

                    if (data.event === "Dial" && data.subevent === "Begin") {

                        var calleridnum = data.calleridnum;
                        var dialstring = data.dialstring;
                        var string = data.channel; // юрл в котором происходит поиск
                        var regV = new RegExp(phone_number, 'ig'); ///102/gi;     // шаблон
                        var result = string.match(regV);  // поиск шаблона в юрл
                        var dialstring_rep = dialstring.replace("trunk/", "");
                        var destination = data.destination;
                        var re = /(.*\/)(\d*)(-.*)/;
                        
                        var getNumberFromChannel = destination.replace(re, "$2");
                        
                        $.cookie("destuniqueid_begin", data.destuniqueid);
                        $.cookie("uniqueid", data.uniqueid);
                        $.cookie("calleridnum", data.calleridnum);
                        $.cookie('destination'+data.destination, data.destination);
                        $.cookie('dialstring'+data.destination, data.dialstring);

                        if (parseInt(result) === parseInt(phone_number)) {

                            var text = "Исходящий звонок на номер: " + dialstring_rep;
                            var type = 'success';
                            $.cookie('call','Out');
                            msg_system(text, type);
                        }

                        if (dialstring === phone_number) {
                            //client.emit('event', "Входящий звонок с номера: " + calleridnum);
                            var text = "Входящий звонок с номера: " + calleridnum;
                            var type = 'success';
                            $.cookie('call','In');
                            msg_system(text, type);
                        }

                    }

                    if (data.event === "Bridge" && data.bridgestate === "Link") {
                        //client.emit('event', "Разговор ...");
                        var channel2 = data.channel2;
                        var re = /(.*\/)(\d*)(-.*)/;
                        
                        var getNumber2 = channel2.replace(re, "$2");
                        
                        var channel1 = data.channel1;
                        var re = /(.*\/)(\d*)(-.*)/;
                        
                        var getNumber1 = channel1.replace(re, "$2");
                        
                        if (getNumber2 === phone_number || getNumber1 === phone_number) {
                            var text = "Разговор ...";
                            var type = "success";
                            msg_system(text, type);
                        }
                    }

                    if (data.event === "Hangup" && data.cause === "16") {
                        //client.emit('event', "Повесили трубку");
                        //uniquniqueid_begin почему-то undefined
                        var channel = data.channel;
                        var re = /(.*\/)(\d*)(-.*)/;
                        
                        var getNumber = channel.replace(re, "$2");
                        
                        if (getNumber === phone_number) {

                            var text = "Повесили трубку";
                            var type = "success";
                            msg_system(text, type);

                        }
                    }
                    
                    if (data.event === "Hangup" && data.cause === "26") {
                        //client.emit('event', "Повесили трубку");
                        //uniquniqueid_begin почему-то undefined
                        var channel = data.channel;
                        var re = /(.*\/)(\d*)(-.*)/;
                        
                        var getNumber = channel.replace(re, "$2");
                        
                        if (getNumber === phone_number) {

                            var text = "Ответил другой абонент";
                            var type = "error";
                            msg_system(text, type);
                        }
                    }

                    if (data.event === "Hangup" && data.cause === "17") {
                        //client.emit('event', "Пользователь занят");
                        var channel = data.channel;
                        var re = /(.*\/)(\d*)(-.*)/;
                        
                        var getNumber = channel.replace(re, "$2");
                        
                        if (getNumber === phone_number) {
                            var text = "Номер занят.";
                            var type = "error";
                            msg_system(text, type);
                        }
                    }
                    if (data.event === "Hangup" && data.cause === "19") {
                        //client.emit('event', "Пропущенный вызов с номера: " + data.calleridnum);
                        console.info($.cookie('call'));
                        var channel = data.channel;
                        var re = /(.*\/)(\d*)(-.*)/;
                        
                        var getNumber = channel.replace(re, "$2");
                        
                        if (getNumber === phone_number && $.cookie('call') === 'In') {
                            var text = "Пропущенный вызов с номера: " + $.cookie('calleridnum');
                            var type = "error";
                            msg_system(text, type);
                        }
                        
                        if(getNumber === phone_number && $.cookie('call') === 'Out'){
                            var text = "Не берут трубку";
                            var type = "error";
                            msg_system(text, type);
                        }
                    }
                    if (data.event === "Hangup" && data.cause === "34") {
                        //client.emit('event', "Пропущенный вызов с номера: " + data.calleridnum);

                        var channel = data.channel;
                        var re = /(.*\/)(\d*)(-.*)/;
                        
                        var getNumber = channel.replace(re, "$2");
                        
                        if (getNumber === phone_number) {
                            var text = "Ошибка вызова";
                            var type = "error";
                            msg_system(text, type);
                        }
                    }
                    if (data.event === "Hangup" && data.cause === "1") {
                        //client.emit('event', "Пропущенный вызов с номера: " + data.calleridnum);

                        var channel = data.channel;
                        var re = /(.*\/)(\d*)(-.*)/;
                        
                        var getNumber = channel.replace(re, "$2");
                        
                        if (getNumber === phone_number) {
                            var text = "Несуществующий номер";
                            var type = "error";
                            msg_system(text, type);
                        }
                    }
                    if (data.event === "Hangup" && data.cause === "21") {
                        //client.emit('event', "Пропущенный вызов с номера: " + data.calleridnum);

                        var channel = data.channel;
                        var re = /(.*\/)(\d*)(-.*)/;
                        
                        var getNumber = channel.replace(re, "$2");
                        
                        if (getNumber === phone_number) {
                            var text = "Вызов отклонен";
                            var type = "error";
                            msg_system(text, type);
                        }
                    }

                });

                function safe(str) {
                    return str.replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;');
                }

                function insertCallData(data) {
                    $.ajax({
                        url: '<?php echo site_url('/core/insertCallData'); ?>',
                        type: "POST",
                        data: {data:data},
                        success: function(data){ 
                        console.info(data); 
                        }
                    });
                }
                
                function updateLinkCallData(data) {
                    $.ajax({
                        url: '<?php echo site_url('/core/updateLinkCallData'); ?>',
                        type: "POST",
                        data: {data:data},
                        success: function(data){ 
                        console.info(data); 
                        }
                    });
                }
                
                function updateEndCallData(data) {
                    $.ajax({
                        url: '<?php echo site_url('/core/updateEndCallData'); ?>',
                        type: "POST",
                        data: {data:data},
                        success: function(data){ 
                        console.info(data); 
                        }
                    });
                }
                
            });
        </script>
    </head>
    <body>

        <div class="container-fluid">
            <ul class="nav nav-pills">
                <li class="active"><a href="#">Главная</a></li>
                <li><a href="#">Задачи</a></li>
                <li><a href="#">Клиенты</a></li>
                <li class="nav pull-right"><a href="auth/logout">Выйти</a></li>
            </ul>
        </div><!--/.nav-collapse -->
