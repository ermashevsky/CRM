<!-- /project_dir/index.html -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex,nofollow"/>
        <title>Office WebCRM </title>
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
        <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <script src="<?php echo $this->config->item('listner_socket_address'); ?>"></script>
        <script type="text/javascript">

            function addRecord(phone_num) {

                var phone_number = $('#phone_num_hide' + phone_num).val();
                var id_call = $('#id_call' + phone_num).val();

                $.post('<?php echo site_url('/core/getContactDetail'); ?>', {'phone_number': phone_number},
                function (data) {
                    $('#phone_num').val(phone_number);
                    $('#selectContact').val(data);
                    $('#id_call').val(id_call);
                    $('#source_records').val('Модуль - История звонков');
                });

                $('#taskWindow').modal('show');
            }

            // javascript code
            function setTask(id_call, phone_num) {
                $('input#id_call').val(id_call);
                console.info(phone_num);
                $.post('<?php echo site_url('/core/getContactDetail'); ?>', {'phone_number': phone_num},
                function (data) {
                    $('#phone_num').val(phone_num);
                    $('#selectContact').val(data);
                });
            }

            function notify(message, type) {
                notif({
                    msg: message,
                    type: type,
                    width: 300,
                    height: 300,
                    opacity: 1,
                    autohide: true,
                    position: "center",
                    multiline: true
                });
            }

            function setContactItem(call_id, dst) {
                //panel with buttons

                $('#modalContactItem').modal('show');

                $("button#setOrganizationItem").click(function () {

                    $.post('<?php echo site_url('/core/getContactDetail'); ?>', {'phone_number': dst},
                    function (data) {

                        if (data !== "") {
                            $('#modalContactItem').modal('hide');

                            var message = "Контакт с номером " + dst + " существует.";
                            var type = "success";
                            notify(message, type);

                        } else {
                            $("input#phone_number").val(dst);

                            $('#modalContactItem').modal('hide');
                            $("#modalOrganizationContactItem").modal("show");

                        }

                    });
                });

                $("button#setContactItem").click(function () {

                    $.post('<?php echo site_url('/core/getContactDetail'); ?>', {'phone_number': dst},
                    function (data) {
                        if (data !== "") {
                            $('#modalContactItem').modal('hide');

                            var message = "Контакт существует.";
                            var type = "success";
                            notify(message, type);

                        } else {
                            $("input#private_phone_number").val(dst);

                            $('#modalContactItem').modal('hide');
                            $("#modalContactItemForm").modal("show");

                        }
                    });
                });
            }

            function getCRMUsers() {
                $.post('<?php echo site_url('/records/getCRMUsers'); ?>',
                        function (data) {
                            $.each(data, function (i, val) {
                                $("#selectAssigned").append('<option value="' + data[i].id + '">' + data[i].first_name + ' ' + data[i].last_name + '</option>');
                            });
                        }, 'json');
            }
            ;
            // /project_dir/index.html
            $(document).ready(function () {
                var url = window.location.href;

                // passes on every "a" tag 
                $(".navbar  a").each(function () {
                    // checks if its the same on the address bar
                    if (url === (this.href)) {
                        $(this).closest("li").addClass("active");
                    }
                });

                $.extend(true, $.fn.dataTable.defaults, {
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                    "sPaginationType": "bootstrap",
                    "oLanguage": {
                        "sUrl": "http://www.sprymedia.co.uk/dataTables/lang.txt"
                    }
                });


                /* Default class modification */
                $.extend($.fn.dataTableExt.oStdClasses, {
                    "sWrapper": "dataTables_wrapper form-inline"
                });


                /* API method to get paging information */
                $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
                {
                    return {
                        "iStart": oSettings._iDisplayStart,
                        "iEnd": oSettings.fnDisplayEnd(),
                        "iLength": oSettings._iDisplayLength,
                        "iTotal": oSettings.fnRecordsTotal(),
                        "iFilteredTotal": oSettings.fnRecordsDisplay(),
                        "iPage": oSettings._iDisplayLength === -1 ?
                                0 : Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        "iTotalPages": oSettings._iDisplayLength === -1 ?
                                0 : Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };


                /* Bootstrap style pagination control */
                $.extend($.fn.dataTableExt.oPagination, {
                    "bootstrap": {
                        "fnInit": function (oSettings, nPaging, fnDraw) {
                            var oLang = oSettings.oLanguage.oPaginate;
                            var fnClickHandler = function (e) {
                                e.preventDefault();
                                if (oSettings.oApi._fnPageChange(oSettings, e.data.action)) {
                                    fnDraw(oSettings);
                                }
                            };

                            $(nPaging).addClass('pagination').append(
                                    '<ul>' +
                                    '<li class="prev disabled"><a href="#">&larr; ' + oLang.sPrevious + '</a></li>' +
                                    '<li class="next disabled"><a href="#">' + oLang.sNext + ' &rarr; </a></li>' +
                                    '</ul>'
                                    );
                            var els = $('a', nPaging);
                            $(els[0]).bind('click.DT', {action: "previous"}, fnClickHandler);
                            $(els[1]).bind('click.DT', {action: "next"}, fnClickHandler);
                        },
                        "fnUpdate": function (oSettings, fnDraw) {
                            var iListLength = 5;
                            var oPaging = oSettings.oInstance.fnPagingInfo();
                            var an = oSettings.aanFeatures.p;
                            var i, ien, j, sClass, iStart, iEnd, iHalf = Math.floor(iListLength / 2);

                            if (oPaging.iTotalPages < iListLength) {
                                iStart = 1;
                                iEnd = oPaging.iTotalPages;
                            }
                            else if (oPaging.iPage <= iHalf) {
                                iStart = 1;
                                iEnd = iListLength;
                            } else if (oPaging.iPage >= (oPaging.iTotalPages - iHalf)) {
                                iStart = oPaging.iTotalPages - iListLength + 1;
                                iEnd = oPaging.iTotalPages;
                            } else {
                                iStart = oPaging.iPage - iHalf + 1;
                                iEnd = iStart + iListLength - 1;
                            }

                            for (i = 0, ien = an.length; i < ien; i++) {
                                // Remove the middle elements
                                $('li:gt(0)', an[i]).filter(':not(:last)').remove();

                                // Add the new list items and their event handlers
                                for (j = iStart; j <= iEnd; j++) {
                                    sClass = (j == oPaging.iPage + 1) ? 'class="active"' : '';
                                    $('<li ' + sClass + '><a href="#">' + j + '</a></li>')
                                            .insertBefore($('li:last', an[i])[0])
                                            .bind('click', function (e) {
                                                e.preventDefault();
                                                oSettings._iDisplayStart = (parseInt($('a', this).text(), 10) - 1) * oPaging.iLength;
                                                fnDraw(oSettings);
                                            });
                                }

                                // Add / remove disabled classes from the static elements
                                if (oPaging.iPage === 0) {
                                    $('li:first', an[i]).addClass('disabled');
                                } else {
                                    $('li:first', an[i]).removeClass('disabled');
                                }

                                if (oPaging.iPage === oPaging.iTotalPages - 1 || oPaging.iTotalPages === 0) {
                                    $('li:last', an[i]).addClass('disabled');
                                } else {
                                    $('li:last', an[i]).removeClass('disabled');
                                }
                            }
                        }
                    }
                });


                /*
                 * TableTools Bootstrap compatibility
                 * Required TableTools 2.1+
                 */
                if ($.fn.DataTable.TableTools) {
                    // Set the classes that TableTools uses to something suitable for Bootstrap
                    $.extend(true, $.fn.DataTable.TableTools.classes, {
                        "container": "DTTT btn-group",
                        "buttons": {
                            "normal": "btn",
                            "disabled": "disabled"
                        },
                        "collection": {
                            "container": "DTTT_dropdown dropdown-menu",
                            "buttons": {
                                "normal": "",
                                "disabled": "disabled"
                            }
                        },
                        "print": {
                            "info": "DTTT_print_info modal"
                        },
                        "select": {
                            "row": "active"
                        }
                    });

                    // Have the collection use a bootstrap compatible dropdown
                    $.extend(true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
                        "collection": {
                            "container": "ul",
                            "button": "li",
                            "liner": "a"
                        }
                    });
                }
                $("button#button1id_saveOrganization").click(function () {

                    $.post('<?php echo site_url('addressbook/addOrganizationData'); ?>', $('form#organizationData').serialize(),
                            function (data) {
                                $('#modalOrganizationContactItem').modal("hide");
                                var type = "success";
                                var message = "Новая организация добавлена";
                                msg_system(message, type);
                            });


                });

                $("button#button1id_saveContact").click(function () {

                    $.post('<?php echo site_url('/addressbook/insertNewContactRow'); ?>', $('form#contactData').serialize(),
                            function (data) {
                                $('#modalContactItemForm').modal("hide");
                                var type = "success";
                                var message = "Новый контакт добавлен";
                                msg_system(message, type);
                            });


                });

                getCRMUsers();
                $("button#button1id").click(function () {

                    $.post('<?php echo site_url('/records/addTask'); ?>', $('form#formTask').serialize(),
                            function (data) {
                                $('#taskWindow').modal("hide");
                                var type = "success";
                                var message = "Запись создана";
                                msg_system(message, type);
                            });


                });

                $('#selectAssigned').on('change', function () {

                    if (this.value)
                        $('#reminder_block').fadeIn('fast');

                    else
                        $('#reminder_block').fadeOut('fast');

                });


                $("#create_date").datetimepicker({
                    format: 'd.m.Y H:i:s',
                    lang: 'ru',
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
                });

                $("#end_date").datetimepicker({
                    format: 'd.m.Y H:i:s',
                    lang: 'ru',
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
                });

                function getContactDetail(phone_number) {
                    $.post('<?php echo site_url('/core/getContactDetail'); ?>', {'phone_number': phone_number},
                    function (data) {
                        if (data !== "") {

                            $("div#ui_notifIt").append("Звонит " + data);
                            $('div#ui_notifIt').css('text-align', 'center');
                        }
                    });
                }

                $("#dst_block").css('display', 'none');
                $("#src_block").css('display', 'none');
                $("#number_block").css('display', 'block');
                $("#number_block2").css('display', 'block');
                $("#src").val('');
                $("#dst").val('');
                $("input#phone_number").val($('#hidden_phone_number').val());
                if ($('#hidden_usergroup').val() === 'admin') {
                    $('#type_call').on('change', function () {
                        if (this.value === 'allcall') {
                            $("#dst_block").css('display', 'none');
                            $("#src_block").css('display', 'none');

                            $("#dst_block").css('display', 'none');
                            $("#src_block").css('display', 'none');
                            $("#number_block").css('display', 'block');
                            $("#number_block2").css('display', 'block');
                            $("input#phone_number").val($('#hidden_phone_number').val());
                            $("#src").val('');
                            $("#dst").val('');
                        }
                        if (this.value === 'outcall') {
                            $("#src").val($('#hidden_phone_number').val());
                            $("#src").attr('readonly', false);
                            $("#dst").val('');
                            $("#dst").attr('readonly', false);
                            $("#dst_block").css('display', 'block');
                            $("#src_block").css('display', 'block');
                            $("#number_block").css('display', 'none');
                            $("#number_block2").css('display', 'none');
                            $("input#phone_number").val('');
                            $("input#phone_number2").val('');
                        }
                        if (this.value === 'incall') {
                            $("#dst").val($('#hidden_phone_number').val());
                            $("#dst").attr('readonly', false);
                            $("#src").val('');
                            $("#src").attr('readonly', false);
                            $("#dst_block").css('display', 'block');
                            $("#src_block").css('display', 'block');
                            $("#number_block").css('display', 'none');
                            $("#number_block2").css('display', 'none');
                            $("input#phone_number").val('');
                            $("input#phone_number2").val('');
                        }
                    });
                } else {
                    $("input#phone_number").attr('readonly', true);
                    $('#type_call').on('change', function () {
                        if (this.value === 'allcall') {
                            $("#dst_block").css('display', 'none');
                            $("#src_block").css('display', 'none');
                            $("input#phone_number").attr('readonly', true);
                            $("input#phone_number").val($('#hidden_phone_number').val());
                            $("#dst_block").css('display', 'none');
                            $("#src_block").css('display', 'none');
                            $("#number_block").css('display', 'block');
                            $("#number_block2").css('display', 'block');
                            $("#src").val('');
                            $("#dst").val('');
                        }
                        if (this.value === 'outcall') {
                            $("#src").val($('#hidden_phone_number').val());
                            $("#src").attr('readonly', true);
                            $("#dst").val('');
                            $("#dst").attr('readonly', false);
                            $("#dst_block").css('display', 'block');
                            $("#src_block").css('display', 'block');
                            $("#number_block").css('display', 'none');
                            $("#number_block2").css('display', 'none');
                            $("input#phone_number").val('');
                            $("input#phone_number2").val('');
                        }
                        if (this.value === 'incall') {
                            $("#dst").val($('#hidden_phone_number').val());
                            $("#dst").attr('readonly', true);
                            $("#src").val('');
                            $("#src").attr('readonly', false);
                            $("#dst_block").css('display', 'block');
                            $("#src_block").css('display', 'block');
                            $("#number_block").css('display', 'none');
                            $("#number_block2").css('display', 'none');
                            $("input#phone_number").val('');
                            $("input#phone_number2").val('');
                        }
                    });
                }


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
                    $.post('<?php echo site_url('/allcalls/actionList'); ?>', function (data) {
                        console.info(data);
                    }, 'json');
                }

                $("button#submit").click(function () {
                    //Тута надо пилить про контакт инфу
                    $.post('<?php echo site_url('/allcalls/getFilteredCalls'); ?>', $('#form_filter_call').serialize(),
                            function (data) {
                                $('#table_all_calls').empty();
                                $('#table_all_calls').append('<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="allcalls"><thead><tr><th>Дата/Время</th><th>Тип звонка</th><th>Вызывающая сторона</th><th>Принимающая сторона</th><th>Длительность</th><th>Статус</th><th>Действия по звонку</th></tr></thead>');
                                console.log(data); //  2pm
                                $.each(data, function () {
                                    // this = object in array
                                    // access attributes: this.Id, this.Name, etc
                                    if ($('#hidden_usergroup').val() === 'admin') {
                                        $('#allcalls').append('<tr><td>' + this.end + '</td><td>Исходящий</td><td>' + this.src + '</td><td>' + this.dst + '</td><td>' + this.billsec + '</td><td>' + this.disposition + '</td><td>' + this.btn_group + '</td></tr>');
                                    }

                                    if ($('#hidden_usergroup').val() !== 'admin') {

                                        if (this.src === $('#hidden_phone_number').val() || this.src === $('#hidden_external_phone_number').val()) {

                                            $('#allcalls').append('<tr><td>' + this.end + '</td><td>Исходящий</td><td>' + this.src + '</td><td>' + this.dst + '</td><td>' + this.billsec + '</td><td>' + this.disposition + '</td><td>' + this.btn_group + '</td></tr>');
                                        }
                                        if (this.dst === $('#hidden_phone_number').val() || this.dst === $('#hidden_external_phone_number').val()) {

                                            $('#allcalls').append('<tr><td>' + this.end + '</td><td>Входящий</td><td>' + this.src + '</td><td>' + this.dst + '</td><td>' + this.billsec + '</td><td>' + this.disposition + '</td><td>' + this.btn_group + '</td></tr>');
                                        }
                                    }
//                                    if($('#hidden_usergroup').val()  === 'admin'){
//                                        
//                                        $('#allcalls').append('<tr><td>' + this.end + '</td><td>Исходящий</td><td>' + this.src + '</td><td>' + this.dst + '</td><td>' + this.billsec + '</td><td>' + this.disposition + '</td><td>' + this.btn_group + '</td></tr>');
//                                    }
                                });
                                $('#allcalls').dataTable({
                                    "sPaginationType": "bootstrap",
                                    "oLanguage": {
                                        "sUrl": "/assets/js/dataTables.russian.txt"
                                    },
                                    "aaSorting": [[0, "desc"]]
                                });
                                $.extend($.fn.dataTableExt.oStdClasses, {
                                    "sWrapper": "dataTables_wrapper form-inline"
                                });
                                $('#form-content').modal('hide');
                            }, "json"); //Тута
                });

                $('#allcalls').dataTable({
                    "sPaginationType": "bootstrap",
                    "oLanguage": {
                        "sUrl": "/assets/js/dataTables.russian.txt"
                    },
                    "aaSorting": [[0, "desc"]]
                });
                $.extend($.fn.dataTableExt.oStdClasses, {
                    "sWrapper": "dataTables_wrapper form-inline"
                });

                var socket = io.connect('<?php echo $this->config->item('listner_address'); ?>');
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

                socket.on('connecting', function () {
                    $('#server_status').empty();
                    $('#server_status').append("Соединение ...");
                    $('#server_status').removeClass("label label-important").addClass("label label-success");
                });

                socket.on('connect', function () {
                    $('#server_status').empty();
                    $('#server_status').append("Соединение установлено");
                    $('#server_status').removeClass("label label-important").addClass("label label-success");
                });

                socket.on('disconnect', function () {
                    $('#server_status').empty();
                    $('#server_status').append("Соединение разорвано");
                    $('#server_status').removeClass("label label-success").addClass("label label-important");
                });

                $('#scrollCall').scrollpanel();

                socket.on('event', function (data) {

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
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
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
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
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
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
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
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
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
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
                        }

                        if (getNumber === phone_number && $.totalStorage('call') === 'Out') {
                            var text = "Не берут трубку";
                            var type = "error";
                            msg_system(text, type);
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
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
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
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
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
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
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
                        }
                    }

                    /**
                     * 
                     * 
                     * 
                     * 
                     * 
                     * 
                     * */       var trunk_name = "mera_dynamic_ANI/74#";
                    var external_phone_number = trunk_name + $('#hidden_external_phone_number').val();

                    if (data.event === "Dial" && data.subevent === "Begin") {

                        var calleridnum = data.calleridnum;
                        var dialstring = data.dialstring;
                        var string = data.channel; // юрл в котором происходит поиск


                        var regXfer = new RegExp('xfer', 'ig');
                        var result_xfer = string.match(regXfer);  // поиск шаблона в юрл

                        var regV = new RegExp(external_phone_number, 'ig'); ///102/gi;     // шаблон
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


                        if (dialstring === external_phone_number) {
                            //client.emit('event', "Входящий звонок с номера: " + calleridnum);

                            getContactDetail(calleridnum);

                            var text = "Входящий звонок с номера: " + calleridnum;
                            var type = 'success';
                            $.totalStorage('call', 'In');

                            msg_system(text, type);
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
                        }

                        if (dialstring === external_phone_number && result_xfer) {
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

                        if (getNumber2 === external_phone_number || getNumber1 === external_phone_number) {
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

                        if (getNumber === external_phone_number) {

                            var text = "Повесили трубку";
                            var type = "success";
                            msg_system(text, type);
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
                        }
                    }

                    if (data.event === "Hangup" && data.cause === "26") {
                        //client.emit('event', "Повесили трубку");
                        //uniquniqueid_begin почему-то undefined
                        var channel = data.channel;
                        var re = /(.*\/)(\d*)(-.*)/;

                        var getNumber = channel.replace(re, "$2");

                        if (getNumber === external_phone_number) {

                            var text = "Ответил другой абонент";
                            var type = "error";
                            msg_system(text, type);
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
                        }
                    }

                    if (data.event === "Hangup" && data.cause === "17") {
                        //client.emit('event', "Пользователь занят");
                        var channel = data.channel;
                        var re = /(.*\/)(\d*)(-.*)/;

                        var getNumber = channel.replace(re, "$2");
                        console.info($.totalStorage('call'));
                        if (getNumber === external_phone_number && $.totalStorage('call') === 'Out') {
                            var text = "Номер занят.";
                            var type = "error";
                            msg_system(text, type);
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
                        }
                    }

                    if (data.event === "Hangup" && data.cause === "18") {
                        //client.emit('event', "Пользователь занят");
                        var channel = data.channel;
                        var re = /(.*\/)(\d*)(-.*)/;

                        var getNumber = channel.replace(re, "$2");
                        console.info($.totalStorage('call'));
                        if (getNumber === external_phone_number && $.totalStorage('call') === 'Out') {
                            var text = "Нет адресата.";
                            var type = "error";
                            msg_system(text, type);
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
                        }
                    }

                    if (data.event === "Hangup" && data.cause === "19") {
                        //client.emit('event', "Пропущенный вызов с номера: " + data.calleridnum);
                        console.info($.totalStorage('call'));
                        var channel = data.channel;
                        var re = /(.*\/)(\d*)(-.*)/;

                        var getNumber = channel.replace(re, "$2");

                        if (getNumber === external_phone_number && $.totalStorage('call') === 'In') {
                            var text = "Пропущенный вызов с номера: " + $.totalStorage('calleridnum');
                            var type = "error";
                            msg_system(text, type);
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
                        }

                        if (getNumber === external_phone_number && $.totalStorage('call') === 'Out') {
                            var text = "Не берут трубку";
                            var type = "error";
                            msg_system(text, type);
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
                        }
                    }
                    if (data.event === "Hangup" && data.cause === "34") {
                        //client.emit('event', "Пропущенный вызов с номера: " + data.calleridnum);

                        var channel = data.channel;
                        var re = /(.*\/)(\d*)(-.*)/;

                        var getNumber = channel.replace(re, "$2");

                        if (getNumber === external_phone_number) {
                            var text = "Ошибка вызова";
                            var type = "error";
                            msg_system(text, type);
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
                        }
                    }
                    if (data.event === "Hangup" && data.cause === "1") {
                        //client.emit('event', "Пропущенный вызов с номера: " + data.calleridnum);

                        var channel = data.channel;
                        var re = /(.*\/)(\d*)(-.*)/;

                        var getNumber = channel.replace(re, "$2");

                        if (getNumber === external_phone_number) {
                            var text = "Несуществующий номер";
                            var type = "error";
                            msg_system(text, type);
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
                        }
                    }
                    if (data.event === "Hangup" && data.cause === "21") {
                        //client.emit('event', "Пропущенный вызов с номера: " + data.calleridnum);

                        var channel = data.channel;
                        var re = /(.*\/)(\d*)(-.*)/;

                        var getNumber = channel.replace(re, "$2");

                        if (getNumber === external_phone_number) {
                            var text = "Вызов отклонен";
                            var type = "error";
                            msg_system(text, type);
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
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
                        success: function (data) {
                            console.info(data);
                        }
                    });
                }

                function updateLinkCallData(data) {
                    $.ajax({
                        url: '<?php echo site_url('/core/updateLinkCallData'); ?>',
                        type: "POST",
                        data: {data: data},
                        success: function (data) {
                            console.info(data);
                        }
                    });
                }

                function updateEndCallData(data) {
                    $.ajax({
                        url: '<?php echo site_url('/core/updateEndCallData'); ?>',
                        type: "POST",
                        data: {data: data},
                        success: function (data) {
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
            body,html{
                font-family: 'Ubuntu', sans-serif;
                padding-top: 30px;
            }
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
            .nav-list {
                padding-right: 15px;
                padding-left: 0px !important;
                margin-bottom: 0;
            }
        </style>
    </head>

    <body>

        <!-- Task Modal Form -->
        <div class="modal hide fade" id="taskWindow" style="width:600px; ">
            <div class="modal-header">
                <a href="#" class="pull-right" data-dismiss="modal">×</a>
                <h4>Новая запись</h4>
            </div>
            <div class="modal-body" style="max-height: 600px;">
                <form action="task/addTask" class="form-horizontal" id="formTask">
                    <fieldset>
                        <!-- Form Name -->
                        <input type="hidden" name="id_call" id="id_call" value=""/>
                        <input id="source_records" name="source_records" type="hidden" value="Модуль - Все звонки" />
                        <!-- Text input-->
                        <div class="control-group">
                            <label class="control-label" for="phone_num">Номер телефона</label>
                            <div class="controls">
                                <input id="phone_num" name="phone_num" type="text" placeholder="" class="input-medium" value="">
                            </div>
                        </div>
                        <!-- Select Basic -->
                        <div class="control-group">
                            <label class="control-label" for="selectContact">Контакт</label>
                            <div class="controls">
                                <input id="selectContact" name="selectContact" type="text" placeholder="" class="input-xlarge" value="">
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

                        <!-- Select Basic -->
                        <div class="control-group">
                            <label class="control-label" for="selectAssigned">Назначена</label>
                            <div class="controls">
                                <select id="selectAssigned" name="selectAssigned" class="input-medium">
                                    <option></option>
                                    <?php
                                    foreach ($users as $value) {
                                        echo "<option value='" . $value->id . "'>" . $value->first_name . " " . $value->last_name . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="create_date">Дата начала</label>
                            <div class="controls">
                                <input id="create_date" name="create_date" type="text" class="input-medium" value="">

                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="end_date">Дата окончания</label>
                            <div class="controls">
                                <input id="end_date" name="end_date" type="text" class="input-medium" value="">

                            </div>
                        </div>
                        <!-- Multiple Checkboxes -->

                        <div id="reminder_block" style="display:none;">
                            <!-- Text input-->
                            <div class="control-group">

                                <div class="controls">
                                    <label class="checkbox" for="checkboxes-report">
                                        <input type="checkbox" name="checkboxes-report" id="checkboxes-report" >
                                        Отчет
                                    </label>
                                </div>
                            </div>
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
        <!-- End of Task Modal Form -->

        <!-- Contact Modal Form -->
        <div id="modalContactItem" class="modal hide fade" style="width:500px;">
            <div class="modal-header">
                <h4>Какой тип контакта добавить в адресную книгу?</h4>
            </div>
            <div class="modal-body">
                <p>
                <div class="controls">
                    <button id="setOrganizationItem" name="setOrganizationItem" class="btn btn-large btn-block btn-success">Организацию</button>
                    <button id="setContactItem" name="setContactItem" class="btn btn-large btn-block btn-success">Контакт</button>
                </div>
                </p>
            </div>
            <div class="modal-footer">
                <!-- Button (Double) -->
                <div class="control-group">
                    <label class="control-label" for="button1id"></label>
                    <div class="controls">
                        <button id="button2id" name="button2id" class="btn btn-mini btn-danger" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Contact Modal Form -->

        <!-- Organization Contact Modal Form -->
        <div id="modalOrganizationContactItem" class="modal hide fade" >
            <div class="modal-header">
                <h4>Новая организация</h4>
            </div>
            <div class="modal-body" style="max-height:600px;">
                <form action="<?php echo site_url('/addressbook/addOrganizationData'); ?>" method="post" accept-charset="utf-8" class="form-horizontal" id="organizationData">                            <fieldset>

                        <!-- Form Name -->
                        <!-- Text input-->
                        <div class="control-group">
                            <label for="organization_name" class="control-label">Наименование</label>                                    <div class="controls">
                                <input type="text" name="organization_name" value="" id="organization_name" placeholder="" class="input-xlarge">                                    </div>
                        </div>

                        <!-- Text input-->
                        <div class="control-group">
                            <label for="address" class="control-label">Адрес организации</label>                                    <div class="controls">
                                <input type="text" name="address" value="" id="address" placeholder="" class="input-xlarge">                                    </div>
                        </div>

                        <!-- Text input-->
                        <div class="control-group">
                            <label for="phone_number" class="control-label">Телефон (основной)</label>                                    <div class="controls">
                                <input type="text" name="phone_number" value="" id="phone_number" placeholder="" class="input-xlarge">                                    </div>
                        </div>

                        <!-- Text input-->
                        <div class="control-group">
                            <label for="email" class="control-label">Email</label>                                    <div class="controls">
                                <input type="text" name="email" value="" id="email" placeholder="" class="input-xlarge">                                    </div>
                        </div>
                        <div class="control-group">
                            <label for="comment" class="control-label">Комментарий</label>                                    <div class="controls">
                                <textarea name="comment" cols="40" rows="10" id="comment" placeholder="" class="input-xlarge"></textarea>                                    </div>
                        </div>
                        <div class="control-group">
                            <label for="private" class="control-label">Приватный контакт</label>
                            <div class="controls">
                                <input type="checkbox" name="private" value="" id="private">
                            </div>
                        </div>
                        <div class="accordion" id="accordion2">
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                                        Дополнительная информация ...
                                    </a>
                                </div>
                                <div id="collapseOne" class="accordion-body collapse">
                                    <div class="accordion-inner">
                                        <!-- Text input-->
                                        <div class="control-group">
                                            <label for="short_organization_name" class="control-label">Краткое наименование</label>                                                    <div class="controls">
                                                <input type="text" name="short_organization_name" value="" id="short_organization_name" placeholder="" class="input-xlarge">                                                    </div>
                                        </div>
                                        <div class="control-group">
                                            <label for="full_organization_name" class="control-label">Полное наименование</label>                                                    <div class="controls">
                                                <input type="text" name="full_organization_name" value="" id="full_organization_name" placeholder="" class="input-xlarge">                                                    </div>
                                        </div>
                                        <div class="control-group">
                                            <label for="alt_address" class="control-label">Дополнительный адрес</label>                                                    <div class="controls">
                                                <input type="text" name="alt_address" value="" id="alt_address" placeholder="" class="input-xlarge">                                                    </div>
                                        </div>
                                        <div class="control-group">
                                            <label for="inn" class="control-label">ИНН</label>                                                    <div class="controls">
                                                <input type="text" name="inn" value="" id="inn" placeholder="" class="input-xlarge">                                                        
                                            </div>
                                        </div>

                                        <!-- Text input-->
                                        <div class="control-group">
                                            <label for="alt_phone_number" class="control-label">Телефон (доп.)</label>                                                    <div class="controls">
                                                <input type="text" name="alt_phone_number" value="" id="alt_phone_number" placeholder="" class="input-xlarge">                                                    </div>
                                        </div>

                                        <!-- Text input-->
                                        <div class="control-group">
                                            <label for="fax" class="control-label">Факс</label>                                                    <div class="controls">
                                                <input type="text" name="fax" value="" id="fax" placeholder="" class="input-xlarge">                                                    </div>
                                        </div>

                                        <!-- Text input-->
                                        <div class="control-group">
                                            <label for="web_url" class="control-label">Web</label>                                                    <div class="controls">
                                                <input type="text" name="web_url" value="" id="web_url" placeholder="" class="input-xlarge">                                                    </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <!-- Button (Double) -->
                <div class="control-group">
                    <label class="control-label" for="button1id"></label>
                    <div class="controls">
                        <div class="controls">
                            <button id="button1id_saveOrganization" name="button1id" class="btn btn-success">Сохранить</button>
                            <button id="cancelForm" name="button2id" class="btn btn-danger" data-dismiss="modal">Отменить</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Contact Modal Form -->

        <!-- Contact Modal Form -->
        <div id="modalContactItemForm" class="modal hide fade" >
            <div class="modal-header">
                <h4>Новый контакт</h4>
            </div>
            <div class="modal-body" style="max-height:600px;">
                <form action="<?php echo site_url('/addressbook/insertNewContactRow'); ?>" method="post" accept-charset="utf-8" class="form-horizontal" id="contactData">                            <fieldset>

                        <!-- Form Name -->
                        <!-- Text input-->
                        <div class="control-group">
                            <label for="contact_name" class="control-label">ФИО</label>                                    <div class="controls">
                                <input type="text" name="contact_name" value="" id="contact_name" placeholder="" class="input-xlarge">                                        
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="control-group">
                            <label for="job_position" class="control-label">Должность</label>                                    <div class="controls">
                                <input type="text" name="job_position" value="" id="job_position" placeholder="" class="input-xlarge">                                    </div>
                        </div>

                        <!-- Text input-->
                        <div class="control-group">
                            <label for="private_phone_number" class="control-label">Телефон (основной)</label>                                    <div class="controls">
                                <input type="text" name="private_phone_number" value="" id="private_phone_number" placeholder="" class="input-xlarge">                                    </div>
                        </div>
                        <!-- Text input-->
                        <div class="control-group">
                            <label for="mobile_number" class="control-label">Мобильный</label>                                    <div class="controls">
                                <input type="text" name="mobile_number" value="" id="mobile_number" placeholder="" class="input-xlarge">                                    </div>
                        </div>
                        <!-- Text input-->
                        <div class="control-group">
                            <label for="email" class="control-label">Email</label>                                    <div class="controls">
                                <input type="text" name="email" value="" id="email" placeholder="" class="input-xlarge">                                    </div>
                        </div>
                        <!-- Text input-->
                        <div class="control-group">
                            <label for="address" class="control-label">Адрес</label>                                    <div class="controls">
                                <input type="text" name="address" value="" id="address" placeholder="" class="input-xlarge"> 
                            </div>
                        </div>
                        <!-- Text input-->
                        <div class="control-group">
                            <label for="birthday" class="control-label">Дата рождения</label>                                    <div class="controls">
                                <input type="text" name="birthday" value="" id="birthday" placeholder="" class="input-xlarge">                                    </div>
                        </div>

                        <div class="control-group">
                            <label for="comment" class="control-label">Дополнительно</label>                                    <div class="controls">
                                <textarea name="comment" cols="40" rows="10" id="comment" placeholder="" class="input-xlarge"></textarea>                                    </div>
                        </div>
                        <div class="control-group">
                            <label for="private" class="control-label">Приватный контакт</label>
                            <div class="controls">
                                <input type="checkbox" name="private" value="" id="private">
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <!-- Button (Double) -->
                <div class="control-group">
                    <label class="control-label" for="button1id"></label>
                    <div class="controls">
                        <div class="controls">
                            <button id="button1id_saveContact" name="button1id" class="btn btn-success">Сохранить</button>
                            <button id="cancelForm" name="button2id" class="btn btn-danger" data-dismiss="modal">Отменить</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Contact Modal Form -->
        <div class="container-fluid">
            <?php
            echo $menu;
            ?>
        </div><!--/.nav-collapse -->
