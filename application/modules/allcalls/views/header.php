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
        <script src="/assets/js/jquery.dataTables.js"></script>
        <script src="/assets/js/bootstrap-progressbar.js"></script>
        <script src="/assets/js/bootstrap-tagsinput.js"></script>
        <script type="text/javascript" src="/assets/js/notifIt.js"></script>
        <script type="text/javascript" src="/assets/js/jquery.total-storage.min.js"></script>
        <script type="text/javascript" src="/assets/js/jquery.scrollpanel.js"></script>
        <script type="text/javascript" src="/assets/js/jquery.datetimepicker.js"></script>
        <script type="text/javascript" src="/assets/js/date.format.js"></script>

        <link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="/assets/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="/assets/css/bootstrap-button.css" rel="stylesheet">
        <link href="/assets/css/bootstrap-fileupload.css" rel="stylesheet">
        <link href="/assets/css/jquery.dataTables.css" rel="stylesheet">
        <link href="/assets/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/assets/css/uploadify.css" />
        <link rel="stylesheet" href="/assets/css/bootstrap-tagsinput.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/notifIt.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/jquery.datetimepicker.css">

        <script src="http://localhost:8580/socket.io/socket.io.js"></script>
        <script type="text/javascript">
            // javascript code
            function setTask(id_call) {
                $('input#id_call').val(id_call);
            }

            function setContactItem() {
                alert("setContactItem function");
            }

            function taskWindow() {

            }
            
            function getCRMUsers(){
                $.post('<?php echo site_url('/tasks/getCRMUsers'); ?>',
                    function(data) {
                        $.each(data, function(i,val) { 
                           $("#selectAssigned").append('<option value="'+data[i].id+'">'+ data[i].first_name +' '+ data[i].last_name +'</option>');
                        });
                    },'json');
            };
            // /project_dir/index.html
            $(document).ready(function() {
                getCRMUsers();
                $("button#button1id").click(function() {

                    $.post('<?php echo site_url('/tasks/addTask'); ?>', $('form#formTask').serialize(),
                    function(data) {
                        $('#taskWindow').modal("hide");
                        var type = "success";
                        var message = "Задача создана";
                        msg_system(message, type);
                    });


                });

                $('#checkboxes-reminder').change(function() {
                    if (this.checked)
                        $('#reminder_block').fadeIn('fast');

                    else
                        $('#reminder_block').fadeOut('fast');
                    $('#reminder_date').val('');

                });

                $("#reminder_date").datetimepicker({
                    format: 'd.m.Y H:i:s',
                    lang: 'ru',
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
                });

                function getContactDetail(phone_number) {
                    $.post('<?php echo site_url('/core/getContactDetail'); ?>', {'phone_number': phone_number},
                    function(data) {
                        if (data !== "") {

                            $("div#ui_notifIt").append("Звонит " + data);
                            $('div#ui_notifIt').css('text-align', 'center');
                        }
                    });
                }

                $("#dst_block").css('display', 'none');
                $("#src_block").css('display', 'none');
                $("#number_block").css('display', 'block');

                $('#type_call').on('change', function() {
                    if (this.value === 'allcall') {
                        $("#dst_block").css('display', 'none');
                        $("#src_block").css('display', 'none');

                        $("#dst_block").css('display', 'none');
                        $("#src_block").css('display', 'none');
                        $("#number_block").css('display', 'block');
                    }
                    if (this.value === 'outcall') {
                        $("#src").val($('#hidden_phone_number').val());
                        $("#src").attr('readonly', true);
                        $("#dst").val('');
                        $("#dst").attr('readonly', false);
                        $("#dst_block").css('display', 'block');
                        $("#src_block").css('display', 'block');
                        $("#number_block").css('display', 'none');
                    }
                    if (this.value === 'incall') {
                        $("#dst").val($('#hidden_phone_number').val());
                        $("#dst").attr('readonly', true);
                        $("#src").val('');
                        $("#src").attr('readonly', false);
                        $("#dst_block").css('display', 'block');
                        $("#src_block").css('display', 'block');
                        $("#number_block").css('display', 'none');
                    }
                });

                $("#date_time").datetimepicker({
                    format: 'd.m.Y H:i:s',
                    value: new Date().format('dd.mm.yyyy 00:00:00'),
                    lang: 'ru',
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
                });
                $('#date_time2').datetimepicker({
                    format: 'd.m.Y H:i:s',
                    value: new Date().format('dd.mm.yyyy 23:59:00'),
                    lang: 'ru',
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
                });
                function getListAction() {
                    // Print hello on the console.
                    $.post('<?php echo site_url('/allcalls/actionList'); ?>', function(data) {
                        console.info(data);
                    });
                }

                $("button#submit").click(function() {

                    $.post('<?php echo site_url('/allcalls/getFilteredCalls'); ?>', $('#form_filter_call').serialize(),
                            function(data) {
                                $('#table_all_calls').empty();
                                $('#table_all_calls').append('<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="allcalls"><thead><tr><th>Дата/Время</th><th>Тип звонка</th><th>Вызывающая сторона</th><th>Принимающая сторона</th><th>Длительность</th><th>Статус</th><th>Действия по звонку</th></tr></thead>');
                                console.log(data); //  2pm
                                $.each(data, function() {
                                    // this = object in array
                                    // access attributes: this.Id, this.Name, etc
                                    if (this.src === $('#hidden_phone_number').val()) {
                                        $('#allcalls').append('<tr><td>' + this.end + '</td><td>Исходящий</td><td>' + this.src + '</td><td>' + this.dst + '</td><td>' + this.billsec + '</td><td>' + this.disposition + '</td><td>' + getListAction() + '</td></tr>');
                                    }
                                    if (this.dst === $('#hidden_phone_number').val()) {
                                        $('#allcalls').append('<tr><td>' + this.end + '</td><td>Входящий</td><td>' + this.src + '</td><td>' + this.dst + '</td><td>' + this.billsec + '</td><td>' + this.disposition + '</td><td>' + getListAction() + '</td></tr>');
                                    }
                                });
                                $('#allcalls').dataTable({
                                    "sPaginationType": "full_numbers",
                                    "oLanguage": {
                                        "sUrl": "/assets/js/dataTables.russian.txt"
                                    },
                                    "aaSorting": [[0, "desc"]]
                                });
                                $.extend($.fn.dataTableExt.oStdClasses, {
                                    "sWrapper": "dataTables_wrapper form-inline"
                                });
                                $('#form-content').modal('hide');
                            }, "json");
                });

                $('#allcalls').dataTable({
                    "sPaginationType": "full_numbers",
                    "oLanguage": {
                        "sUrl": "/assets/js/dataTables.russian.txt"
                    },
                    "aaSorting": [[0, "desc"]]
                });
                $.extend($.fn.dataTableExt.oStdClasses, {
                    "sWrapper": "dataTables_wrapper form-inline"
                });

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

                $('#scrollCall').scrollpanel();

                socket.on('event', function(data) {

                    console.info(data);
                    $('.msg system').empty();

                    var phone_number = $('#hidden_phone_number').val();

                    if (data.event === "Dial" && data.subevent === "Begin") {

                        var calleridnum = data.calleridnum;
                        var dialstring = data.dialstring;
                        var string = data.channel; // юрл в котором происходит поиск


                        var regXfer = new RegExp('xfer', 'ig');
                        var result_xfer = string.match(regXfer);  // поиск шаблона в юрл

                        var regV = new RegExp(phone_number, 'ig'); ///102/gi;     // шаблон
                        var result = string.match(regV);  // поиск шаблона в юрл
                        var dialstring_rep = dialstring.replace("trunk/", "");
                        var destination = data.destination;
                        var re = /(.*\/)(\d*)(-.*)/;

                        var getNumberFromChannel = destination.replace(re, "$2");

                        $.totalStorage("destuniqueid_begin", data.destuniqueid);
                        $.totalStorage("uniqueid", data.uniqueid);
                        $.totalStorage("calleridnum", data.calleridnum);
                        $.totalStorage('destination' + data.destination, data.destination);
                        $.totalStorage('dialstring' + data.destination, data.dialstring);


                        if (parseInt(result) === parseInt(phone_number)) {

                            var text = "Исходящий звонок на номер: " + dialstring_rep;
                            var type = 'success';
                            $.totalStorage('call', 'Out');
                            msg_system(text, type);
                        }

                        if (dialstring === phone_number) {
                            //client.emit('event', "Входящий звонок с номера: " + calleridnum);

                            getContactDetail(calleridnum);

                            var text = "Входящий звонок с номера: " + calleridnum;
                            var type = 'success';
                            $.totalStorage('call', 'In');

                            msg_system(text, type);
                        }

                        if (dialstring === phone_number && result_xfer) {
                            //client.emit('event', "Входящий звонок с номера: " + calleridnum);
                            var text = "Переведенный входящий звонок с номера: " + calleridnum;
                            var type = 'success';
                            $.totalStorage('call', 'In');
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
                        console.info($.totalStorage('call'));
                        if (getNumber === phone_number && $.totalStorage('call') === 'Out') {
                            var text = "Номер занят.";
                            var type = "error";
                            msg_system(text, type);
                        }
                    }

                    if (data.event === "Hangup" && data.cause === "18") {
                        //client.emit('event', "Пользователь занят");
                        var channel = data.channel;
                        var re = /(.*\/)(\d*)(-.*)/;

                        var getNumber = channel.replace(re, "$2");
                        console.info($.totalStorage('call'));
                        if (getNumber === phone_number && $.totalStorage('call') === 'Out') {
                            var text = "Нет адресата.";
                            var type = "error";
                            msg_system(text, type);
                        }
                    }

                    if (data.event === "Hangup" && data.cause === "19") {
                        //client.emit('event', "Пропущенный вызов с номера: " + data.calleridnum);
                        console.info($.totalStorage('call'));
                        var channel = data.channel;
                        var re = /(.*\/)(\d*)(-.*)/;

                        var getNumber = channel.replace(re, "$2");

                        if (getNumber === phone_number && $.totalStorage('call') === 'In') {
                            var text = "Пропущенный вызов с номера: " + $.totalStorage('calleridnum');
                            var type = "error";
                            msg_system(text, type);
                        }

                        if (getNumber === phone_number && $.totalStorage('call') === 'Out') {
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
                        data: {data: data},
                        success: function(data) {
                            console.info(data);
                        }
                    });
                }

                function updateLinkCallData(data) {
                    $.ajax({
                        url: '<?php echo site_url('/core/updateLinkCallData'); ?>',
                        type: "POST",
                        data: {data: data},
                        success: function(data) {
                            console.info(data);
                        }
                    });
                }

                function updateEndCallData(data) {
                    $.ajax({
                        url: '<?php echo site_url('/core/updateEndCallData'); ?>',
                        type: "POST",
                        data: {data: data},
                        success: function(data) {
                            console.info(data);
                        }
                    });
                }

            });
            function play() {
                alert("Выбрано воспроизведение");
            }
        </script>
        <style>
            #scrollCall {
                width: 140px;
                height: 200px;
                border: 2px solid #ccc;
                padding-left:4px;
            }
            .sp-scrollbar {
                width: 10px;
                margin: 4px;
                background-color: #ccc;
                cursor: pointer;
            } 
            .sp-thumb {
                background-color: #aaa;
            }

            .sp-scrollbar.active
            .sp-thumb {
                background-color: #999;
            }
            address{
                padding-left: 4px;
            }

            #allcalls{
                font-size: 12px;
            }
            #actionList, #selectAction {
                vertical-align: middle;
            }
            #allcalls_info, #allcalls_paginate{
                margin-top: 10px;
                font-size: 12px;
            }
            .dataTables_length select {
                width: auto !important;
                font-size: 12px;
            }
            .dataTables_length label{
                font-size: 12px;
            }
            .dataTables_filter input{
                width: 120px;
                font-size: 12px;
            }
            .dataTables_filter label{
                font-size: 12px;
            }
            #form_filter_call select{
                width: auto !important;  
            }
            #form_filter_call{
                margin: 10px;
            }
            #form-content{
                width: 550px!important;
            }
        </style>
    </head>

    <body>
        <div class="modal hide fade" id="taskWindow" style="width:600px; ">
            <div class="modal-header">
                <a href="#" class="pull-right" data-dismiss="modal">×</a>
                <h4>Новая задача</h4>
            </div>
            <div class="modal-body" style="max-height: 600px;">
                <form action="task/addTask" class="form-horizontal" id="formTask">
                    <fieldset>
                        <!-- Form Name -->
                        <input type="hidden" name="id_call" id="id_call" value=""/>
                        <!-- Text input-->
                        <div class="control-group">
                            <label class="control-label" for="selectStatus">Статус</label>
                            <div class="controls">
                                <select id="selectStatus" name="selectStatus" class="input-medium">
                                    <option>В работе</option>
                                    <option>Завершена</option>
                                </select>
                            </div>
                        </div>
                        <!-- Select Basic -->
                        <div class="control-group">
                            <label class="control-label" for="selectPriority">Приоритет</label>
                            <div class="controls">
                                <select id="selectPriority" name="selectPriority" class="input-medium">
                                    <option>Низкий</option>
                                    <option>Нормальный</option>
                                    <option>Высокий</option>
                                    <option>Срочный</option>
                                    <option>Немедленный</option>
                                </select>
                            </div>
                        </div>

                        <!-- Select Basic -->
                        <div class="control-group">
                            <label class="control-label" for="selectAssigned">Назначена</label>
                            <div class="controls">
                                <select id="selectAssigned" name="selectAssigned" class="input-medium">
                                    <option></option>
                                    <?php
                                    
//                                    foreach ($users as $value) {
//                                        echo "<option value='" . $value->id . "'>" . $value->first_name . " " . $value->last_name . "</option>";
//                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Select Basic -->
                        <div class="control-group">
                            <label class="control-label" for="selectCategory">Категория</label>
                            <div class="controls">
                                <select id="selectCategory" name="selectCategory" class="input-medium">
                                    <option>Личные</option>
                                    <option>Работа</option>
                                </select>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="control-group">
                            <label class="control-label" for="task_name">Заголовок</label>
                            <div class="controls">
                                <input id="task_name" name="task_name" type="text" placeholder="" class="input-xlarge" value="">

                            </div>
                        </div>

                        <!-- Textarea -->
                        <div class="control-group">
                            <label class="control-label" for="task_description">Описание</label>
                            <div class="controls">                     
                                <textarea id="task_description" name="task_description" class="input-xlarge" cols="10" rows="10"></textarea>
                            </div>
                        </div>
                        <!-- Multiple Checkboxes -->
                        <div class="control-group">

                            <div class="controls">
                                <label class="checkbox" for="checkboxes-reminder">
                                    <input type="checkbox" name="checkboxes-reminder" id="checkboxes-reminder" >
                                    Напомнить
                                </label>
                            </div>
                        </div>
                        <div id="reminder_block" style="display:none;">
                            <!-- Text input-->
                            <div class="control-group">
                                <label class="control-label" for="reminder_date">Дата/время</label>
                                <div class="controls">
                                    <input id="reminder_date" name="reminder_date" type="text" class="input-medium" value="">

                                </div>
                            </div>
                            <!--                                 Select Basic 
                                                            <div class="control-group">
                                                                <label class="control-label" for="selectReminder">Напомнить за</label>
                                                                <div class="controls">
                                                                    <select id="selectReminder" name="selectReminder" class="input-medium">
                                                                        <option></option>
                                                                        <option>5 минут</option>
                                                                        <option>10 минут</option>
                                                                        <option>15 минут</option>
                                                                        <option>30 минут</option>
                                                                        <option>60 минут</option>
                                                                    </select>
                                                                </div>
                                                            </div>-->
                        </div>


                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <!-- Button (Double) -->
                <div class="control-group">
                    <label class="control-label" for="button1id"></label>
                    <div class="controls">
                        <button id="button1id" name="button1id" class="btn btn-success">Сохранить</button>
                        <button id="button2id" name="button2id" class="btn btn-danger" data-dismiss="modal">Отменить</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <?php
            echo $menu;
            ?>
        </div><!--/.nav-collapse -->
