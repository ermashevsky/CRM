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
        <script src="/assets/js/bootstrap-wysiwyg.js"></script>
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
        <script type="text/javascript" src="/assets/js/select2.js"></script>
        <script type="text/javascript" src="/assets/js/select2_locale_ru.js"></script>
        <script type="text/javascript" src="/assets/js/jquery.form-validator.min.js"></script>

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
        <link rel="stylesheet" type="text/css" href="/assets/css/select2.css">

        <script src="http://dialog.crm64.ru:8580/socket.io/socket.io.js"></script>
        <script type="text/javascript">
            // javascript code
            
            var address = "dialog.crm64.ru";
            
            function getContactDetail(phone_number) {
                  $.post('<?php echo site_url('/core/getContactDetail'); ?>', {'phone_number': phone_number},
                    function(data) {
                        if(data !== ""){
                        $("div#ui_notifIt").append("Звонит " + data);
                        $('div#ui_notifIt').css('text-align','center');
                    }
                  });
                }
            function insertDataOrganization() {

                var MyRows = $('table#contact_list').find('tbody').find('tr');
                for (var i = 0; i < MyRows.length; i++) {
                    var MyIndexValue = $(MyRows[i]).find('td:eq(0)').html();
                    console.info(MyIndexValue);
                }
            }

            function deleteFromOrganization(id) {
                bootbox.confirm("Действительно хотите исключить из организации?", function(result) {
                    if (result) {
                        $.post('<?php echo site_url('/addressbook/deleteFromOrganization'); ?>', {'id': id},
                        function(data) {
                            location.reload();
                        }, 'json');
                    } else {
                        console.log("User declined dialog");
                    }
                });
            }

            function newContactRefererModalForm() {
                var organization_id = $("input[name='organization_id']").val();
                var organization_name = $("input[name='organization_name']").val();
                $('#organization_name')
                        .append($("<option></option>")
                                .attr("value", organization_id)
                                .text(organization_name));
                $('#myNewContactForm').modal();
            }
            
            function saveNewContact(){
                
                var organization_id = $("#organization_name").val();
                var contact_name = $("#contact_name").val();
                var job_position = $("#job_position").val();
                var private_phone_number = $("#private_phone_number").val();
                var mobile_number = $("#mobile_number").val();
                var email = $("#email").val();
                var address = $("#address").val();
                var birthday = $("#birthday").val();
                var comment = $("#comment").val();
                
                $.post('<?php echo site_url('/addressbook/insertNewContactRow'); ?>',
                {'organization_id': organization_id, 'contact_name':contact_name, 'job_position':job_position,
                'private_phone_number':private_phone_number, 'mobile_number':mobile_number, 'email':email, 'address':address,
                'birthday':birthday, 'comment':comment},
                function(data) {
                    location.reload();
                });
                
            }

            function deleteOrganization(id) {
                $.post('<?php echo site_url('/addressbook/getContactsOrganization'); ?>', {'id': id},
                function(data) {
                    if (data > 0) {
                        bootbox.dialog("В организации существуют контакты.<br/>Удалить организацию вместе с контактами?", [{
                                "label": "Да",
                                "class": "btn-default",
                                "callback": function() {
                                    $.post('<?php echo site_url('/addressbook/deleteOrganizationWithContacts'); ?>', {'id': id},
                                    function(data) {
                                        window.location.replace("/");
                                    }, 'json');
                                }
                            }, {
                                "label": "Нет",
                                "class": "btn-default",
                                "callback": function() {
                                    $.post('<?php echo site_url('/addressbook/deleteOrganizationWithoutContacts'); ?>', {'id': id},
                                    function(data) {
                                        window.location.replace("/");
                                    }, 'json');
                                }
                            }]);
                    }
                    if (data === 0) {
                        bootbox.dialog("Удалить организацию ?", [{
                                "label": "Да",
                                "class": "btn-default",
                                "callback": function() {
                                    $.post('<?php echo site_url('/addressbook/deleteOrganization'); ?>', {'id': id},
                                    function(data) {

                                    });
                                }
                            }, {
                                "label": "Нет",
                                "class": "btn-default",
                                "callback": function() {

                                }
                            }]);


                    }
                }, 'json');
            }

            // javascript code
            function getval(sel) {
                if (sel.value !== '') {
                    $.post('<?php echo site_url('/addressbook/getContactById'); ?>', {'id': sel.value}, function(data) {
                        console.info(data);
                        var counter = 0;
                        $.each(data, function(i, val) {
                            $('#contact_list').append('<tr><td class="nr">' + data[i].contact_name +
                                    '</td><td>' + data[i].job_position +
                                    '</td><td>' + data[i].private_phone_number +
                                    '</td><td>' + data[i].email +
                                    '</td><td>' + data[i].address +
                                    '</td></tr>');
                            $('#organizationData').append('<input type="hidden" name="token[]" value="' + data[i].contact_name + '" />');
                            $('#contact_list').append('<input type="hidden" name="token[]" value="' + data[i].contact_name + '" />');
                        });
                        $('option:selected', sel).remove();
                    }, 'json');

                }
            }

// /project_dir/index.html
            $(document).ready(function() {
            
                $('#checkboxes-reminder').change(function(){
                    if(this.checked)
                        $('#reminder_block').fadeIn('fast');
                        
                    else
                        $('#reminder_block').fadeOut('fast');
                        $('#reminder_date').val('');

                });
                $('#contactList').dataTable({
                    "sPaginationType": "full_numbers",
                    "oLanguage": {
                        "sUrl": "/assets/js/dataTables.russian.txt"
                    },
                    "aaSorting": [[0, "asc"]]
                });
                $.extend($.fn.dataTableExt.oStdClasses, {
                    "sWrapper": "dataTables_wrapper form-inline"
                });

                $('#allContactsTable').dataTable({
                    "sPaginationType": "full_numbers",
                    "oLanguage": {
                        "sUrl": "/assets/js/dataTables.russian.txt"
                    },
                    "aaSorting": [[0, "asc"]]
                });
                $.extend($.fn.dataTableExt.oStdClasses, {
                    "sWrapper": "dataTables_wrapper form-inline"
                });

                $.post('<?php echo site_url('/addressbook/getAllContacts'); ?>', function(data) {
                    console.info(data);
                    var select = $('#selectContact');
                    $.each(data, function(i, val) {
                        select.append('<option value="' + data[i].id + '">' + data[i].contact_name + '</option>');

                    });
                }, 'json');

                var datas = [];

                $.post('<?php echo site_url('/addressbook/getAllOrganizations'); ?>', function(data) {
                    console.info(data);
                    $.each(data, function(i, value) {
                        datas.push({id: data[i].id, text: data[i].organization_name});
                    });//Тут запихиваю в data то что приходит с аякса.
                    $('.select2field').select2({
                        placeholder: "Поиск организации",
                        minimumInputLength: 3,
                        allowClear: true,
                        width: 280,
                        createSearchChoice: function(term, data) {
                            if ($(data).filter(function() {
                                return this.text.localeCompare(term) === 0;
                            }).length === 0) {
                                return {
                                    id: term,
                                    text: term
                                };
                            }
                        },
                        data: datas

//                            $.each(data, function(i, val) {
//                                "id:" + data[i].id +"text:"+data[i].organization_name;
//                            })
                    });
                }, 'json');

                $("#selectContact").select2({
                    placeholder: "Поиск контакта",
                    minimumInputLength: 3,
                    allowClear: true
                });

//                 $("#selectOrganization").select2({
//                     
//                    placeholder: "Поиск организации",
//                    minimumInputLength: 3,
//                    allowClear: true
//                });

                $("#reminder_date").datetimepicker({
                    format: 'd.m.Y H:i:s',
                    lang: 'ru',
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
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

                var socket = io.connect('http://'+address+':8580');

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
            #allContactsTable_info, #allContactsTable_paginate, #contactList_info, #contactList_paginate{
                margin-top: 10px;
                font-size: 12px;
            }
            .dataTables_length select {
                width: auto !important;
                font-size: 12px;
                margin-top:10px;
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
            #allContactsTable, #organizationDetails, #contactList{
                font-size: 12px;
            }
            .bootbox{
                width: 380px;/* your width */
            }
            #myNewContactForm .modal-body{
                max-height: 650px;
            }

        </style>
    </head>
    <body>

        <div class="container-fluid">
            <?php
            echo $menu;
            ?>
        </div><!--/.nav-collapse -->
