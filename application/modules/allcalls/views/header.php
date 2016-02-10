<!-- /project_dir/index.html -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex,nofollow"/>
        <title>Office WebCRM </title>
        <script src="/assets/js/jquery-latest.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
        <script src="/assets/js/bootstrap-button.js"></script>
        <script src="/assets/js/bootstrap-fileupload.js"></script>
        <script src="/assets/js/bootstrap-notify.js"></script>
        <script src="/assets/js/jquery.uploadify.min.js"></script>
        <script src="/assets/js/bootbox.min.js"></script>
        <script src="/assets/js/jquery.dataTables.js"></script>
        <script src="/assets/js/dataTables.tableTools.js"></script>
        <script src="/assets/js/bootstrap-progressbar.js"></script>
        <script src="/assets/js/bootstrap-tagsinput.js"></script>
        <script src="/assets/js/bootstrap-spinedit.js"></script>

        <script type="text/javascript" src="/assets/js/notifIt.js"></script>
        <script type="text/javascript" src="/assets/js/jquery.total-storage.min.js"></script>
        <script type="text/javascript" src="/assets/js/jquery.scrollpanel.js"></script>
        <script type="text/javascript" src="/assets/js/jquery.datetimepicker.js"></script>
        <script type="text/javascript" src="/assets/js/date.format.js"></script>
        <script type="text/javascript" src="/assets/js/table2CSV.js"></script>

        <script type="text/javascript" src="/assets/js/jquery.autocomplete.min.js"></script>
        <script type="text/javascript" src="/assets/js/jquery.maskedinput.js"></script>


        <link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="/assets/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="/assets/css/bootstrap-button.css" rel="stylesheet">
        <link href="/assets/css/bootstrap-fileupload.css" rel="stylesheet">
        <link href="/assets/css/jquery.dataTables.css" rel="stylesheet">
        <link href="/assets/css/TableTools.css" rel="stylesheet">
        <link href="/assets/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/assets/css/uploadify.css" />
        <link rel="stylesheet" href="/assets/css/bootstrap-tagsinput.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/notifIt.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/jquery.datetimepicker.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-spinedit.css">

        <script src="<?php echo $this->config->item('listner_socket_address'); ?>"></script>
        <script type="text/javascript">
            function getFullClientInfo(phone_num) {

                $("#myModal").modal('show').css(
                        {
                            'margin-left': function () {
                                return -($(this).width() / 2);
                            }
                        });
            }

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
            function setTask(id_call, src, dst, call_type) {

                $('input#id_call').val(id_call);
                console.info(src);
                console.info(dst);
                console.info(call_type);

                var phone_num = '';

                if (call_type === 'outgoing') {
                    phone_num = dst;
                } else {
                    phone_num = src;
                }

                $.post('<?php echo site_url('/core/getContactDetail'); ?>', {'phone_number': phone_num},
                function (data) {

                    $('#phone_num').val(phone_num);
                    $('#selectContact').val(data);

                    $('#create_date').val("");
                    $('#end_date').val("");
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

            function setContactItem(call_id, src, dst, call_type) {
                //panel with buttons

                console.info(src);
                console.info(dst);
                console.info(call_type);

                $("input#phone_number").removeAttr('value');
                $("input#private_phone_number").removeAttr('value');

                var phone_num = '';

                if (call_type === 'outgoing') {
                    phone_num = dst;
                } else {
                    phone_num = src;
                }



                $.post('<?php echo site_url('/core/getContactDetail'); ?>', {'phone_number': phone_num},
                function (data) {

                    if (data !== "") {
                        $('#modalContactItem').modal('hide');

                        var message = "Контакт с номером " + phone_num + " существует.";
                        var type = "success";
                        notify(message, type);

                    } else {
                        localStorage.clear();

                        $('#modalContactItem').modal('show');

                        localStorage.setItem('privatePhoneNum', phone_num);
                        localStorage.setItem('phoneNum', phone_num);

                    }

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


                $.post('<?php echo site_url('/allcalls/truncateTable'); ?>',
                        function (data) {

                        });


                $("#button2idFormTask").click(function () {
                    $('#formTask').trigger('reset');

                });

                $("#button2idorganizationData").click(function () {
                    $('#organizationData').trigger('reset');

                });



                $("#mobile_number").mask("9-999-999-9999", {autoclear: false}).val('');
                $("#private_phone_number").mask("9-999-999-9999", {autoclear: false}).val('');
                $('#mobile_number').bind("change paste keyup", function () {
                    //alert($(this).val());
                });
                var url = window.location.href;
                $("#checkboxes-reminder").click(function () {

                    if ($('input:checkbox[name=checkboxes-reminder]').is(':checked')) {

                        console.info('Is Checked');
                        $('#checkboxes_reminder_block').css('display', 'block');
                    } else {

                        console.info('Is Not Checked');
                        $('#checkboxes_reminder_block').css('display', 'none');
                        $("#task_reminder_date").val("");
                    }

                });
                $("button#setOrganizationItem").click(function () {

                    $("input#phone_number").removeAttr('value');
                    $("input#phone_number").val(localStorage.getItem('phoneNum'));
                    $('#modalContactItem').modal('hide');
                    $("#modalOrganizationContactItem").modal("show");
                });
                $("button#setContactItem").click(function () {

                    $("input#private_phone_number").removeAttr('value');
                    $("input#private_phone_number").val(localStorage.getItem('privatePhoneNum'));
                    $('#modalContactItem').modal('hide');
                    $("#modalContactItemForm").modal("show");
                });
                $('#cancelForm').click(function () {
                    console.info('OK');
                    //location.reload();
                });
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
                                location.reload();
                            });
                });

                $("button#button1id_saveContact").click(function () {

                    $("#organization_id").val($("#organization").val());

                    if ($('#contact_name').val() !== "" && $('#private_phone_number').val() !== "") {
                        $.post('<?php echo site_url('/addressbook/insertNewContactRow'); ?>', $('form#contactData').serialize(),
                                function (data) {
                                    $('#modalContactItemForm').modal("hide");
                                    var type = "success";
                                    var message = "Новый контакт добавлен";
                                    msg_system(message, type);
                                    location.reload();
                                });
                    } else {

                        if ($('#contact_name').val() === "") {
                            $('#contact_warning_text').css('display', 'block');
                        } else {
                            $('#contact_warning_text').css('display', 'none');
                        }

                        if ($('#private_phone_number').val() === "") {
                            $('#phone_warning_text').css('display', 'block');
                        } else {
                            $('#phone_warning_text').css('display', 'none');
                        }


                    }

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
                $("#task_create_date").datetimepicker({
                    format: 'd.m.Y H:i',
                    lang: 'ru',
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
                });
                $("#task_end_date").datetimepicker({
                    format: 'd.m.Y H:i',
                    lang: 'ru',
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
                });
                $("#task_reminder_date").datetimepicker({
                    format: 'd.m.Y H:i',
                    lang: 'ru',
                    timepicker: true,
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
                });
                $("#birthday").datetimepicker({
                    format: 'd.m.Y',
                    lang: 'ru',
                    timepicker: false,
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
                });
                $('#duration_minute').spinedit({
                    minimum: 0,
                    maximum: 59,
                    step: 1,
                    value: 0
                });
                $('#duration_second').spinedit({
                    minimum: 0,
                    maximum: 59,
                    step: 1,
                    value: 0
                });

                $('#duration_minute').tooltip({'trigger': 'focus', 'title': 'Поле "Минуты". От 0 - 59 мин.'});
                $('#duration_second').tooltip({'trigger': 'focus', 'title': 'Поле "Секунды". От 0 - 59 сек.'});

                function getContactDetail2(phone_number) {
                    $.post('<?php echo site_url('/core/getContactDetail'); ?>', {'phone_number': phone_number},
                    function (data) {
                        if (data !== "") {
                            $('#contactDetail_' + phone_number).append(data);
                        }
                    });
                }


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
                $('#duration').datetimepicker({
                    format: 'H:i:s',
                    value: new Date().format('00:00:00'),
                    lang: 'ru',
                    datepicker: false,
                    step: 1,
                    closeOnDateSelect: true,
                    scrollTime: true

                });
                function getListAction() {
                    // Print hello on the console.
                    $.post('<?php echo site_url('/allcalls/actionList'); ?>', function (data) {
                        console.info(data);
                    }, 'json');
                }

                function save2db(date_time, call_type, src, dst, duration, status) {

                    $.post('<?php echo site_url('/allcalls/saveToDb'); ?>', {date_time: date_time, call_type: call_type, src: src, dst: dst, duration: duration, status: status},
                    function (data) {

                    });
                }

                function translateDisposition(disposition) {
                    return "Ответили";
                }


                $("button#submit").click(function () {
                    //Сделать проверка полей

                    /**Сюда сделать вызов truncateTable();
                     * 
                     * Также сделать вызов truncateTable(); при загрузке страниц модуля AllCalls
                     * 
                     * */

                    $.post('<?php echo site_url('/allcalls/truncateTable'); ?>',
                            function (data) {

                            });

                    $.post('<?php echo site_url('/allcalls/getFilteredCalls'); ?>', $('#form_filter_call').serialize(),
                            function (data) {
                                $('#table_all_calls').empty();
                                $('#table_all_calls').append('<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="allcalls"><thead><tr><th>Дата/Время</th><th>Тип звонка</th><th>Вызывающая сторона</th><th>Принимающая сторона</th><th>Длительность</th><th>Статус</th><th class="noExl">Действия по звонку</th></tr></thead>');
                                console.log(data); //  2pm
                                $.each(data, function () {

                                    console.info(this.channel);
                                    console.info(this.dstchannel);
                                    // this = object in array
                                    // access attributes: this.Id, this.Name, etc
                                    if ($('#hidden_usergroup').val() === 'admin') {
                                        save2db(this.end, 'Исходящий', this.src, this.dst, this.billsec, this.disposition);
                                        $('#allcalls').append('<tr><td>' + this.end + '</td><td>Исходящий</td><td>' + this.src + this.src_contact + '</td><td>' + this.dst + this.dst_contact + '</td><td>' + this.billsec + '</td><td>' + this.disposition + '</td><td class="noExl">' + this.btn_group + '</td></tr>');

                                    }

                                    if ($('#hidden_usergroup').val() !== 'admin') {

                                        if (this.channel === $('#hidden_phone_number').val() || this.src === $('#hidden_phone_number').val() || this.src === $('#hidden_external_phone_number').val()) {


                                            var btn_grp = '<a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>\n\
                        <a href="#" title="Добавить контакт" onclick="setContactItem(' + this.id + ',' + this.dst + ');return false;" class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>\n\
                        <a href="#taskWindow"  title="Добавить запись" onclick="setTask(' + this.id + ',' + this.dst + '); return false;" data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a></div>';

                                            save2db(this.end, 'Исходящий', this.src, this.dst, this.billsec, this.disposition);
                                            $('#allcalls').append('<tr><td>' + this.end + '</td><td>Исходящий</td><td>' + this.src + this.src_contact + '</td><td>' + this.dst + this.dst_contact + '</td><td>' + this.billsec + '</td><td>' + this.disposition + '</td><td class="noExl"><div class="btn-group"><a href="#" title="Вызов абонента" onclick=prepareOriginateCall(' + $('#hidden_phone_number').val() + ',' + this.src + ',' + this.dst + ',"outgoing");return false; class="btn btn-warning btn-mini"><i class="icon-white icon-phone"></i></a>' + btn_grp + '</td></tr>');
                                        }
                                        if (this.dstchannel === $('#hidden_phone_number').val() || this.dst === $('#hidden_phone_number').val() || this.dst === $('#hidden_external_phone_number').val()) {

                                            var btn_grp = '<a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>\n\
                        <a href="#" title="Добавить контакт" onclick="setContactItem(' + this.id + ',' + this.src + ');return false;" class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>\n\
                        <a href="#taskWindow"  title="Добавить запись" onclick="setTask(' + this.id + ',' + this.src + '); return false;" data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a></div>';

                                            save2db(this.end, 'Входящий', this.src, this.dst, this.billsec, this.disposition);
                                            $('#allcalls').append('<tr><td>' + this.end + '</td><td>Входящий</td><td>' + this.src + this.src_contact + '</td><td>' + this.dst + this.dst_contact + '</td><td>' + this.billsec + '</td><td>' + this.disposition + '</td><td class="noExl"><div class="btn-group"><a href="#" title="Вызов абонента" onclick=prepareOriginateCall(' + $('#hidden_phone_number').val() + ',' + this.src + ',' + this.dst + ',"incomming");return false; class="btn btn-warning btn-mini"><i class="icon-white icon-phone"></i></a>' + btn_grp + '</td></tr>');
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

//                    $('.myParagraphBlock').append(oTableTools.dom.container);
                });
                var table = $('#allcalls').dataTable({
                    "sPaginationType": "bootstrap",
                    "oLanguage": {
                        "sUrl": "/assets/js/dataTables.russian.txt"
                    },
                    "aaSorting": [[0, "desc"]]

                });
                $.extend($.fn.dataTableExt.oStdClasses, {
                    "sWrapper": "dataTables_wrapper form-inline"
                });
//                var oTableTools = new TableTools(table, {
//                    "sSwfPath": "/assets/js/swf/copy_csv_xls.swf",
//                    "aButtons": [
//                        {
//                            "sExtends": "csv",
//                            "sButtonText": "<i class='icon-download-alt'> </i>Сохранить в XLS",
//                            "sButtonClass": "btn btn-info btn-small"
//                        }
//                    ]
//                });
//
//                $('.myParagraphBlock').append(oTableTools.dom.container);
//                oTableTools.fnResizeRequired(true);
//                oTableTools.fnResizeButtons();

                function checkEmptyTable() {

                    $.post('<?php echo site_url('/allcalls/getRowsCountXLSTable'); ?>',
                            function (data) {
                                return data;
                            }, 'json');
                }

                $('#saveXLS').click(function () {

                    /**
                     * Проверка на пустую таблицу для конкретного пользователя
                     * Если таблица пуста - сделать запрос на все звонки за день для
                     * конкретного пользователя
                     * Иначе (таблица не пуста) для конкретного пользователя есть
                     * данные от фильтра звонков
                     * 
                     * Такую же функцию сдлеать для CSV файла !!!
                     * */
                    $.post('<?php echo site_url('/allcalls/getRowsCountXLSTable'); ?>',
                            function (data) {
                                if (data > 0) {

                                    $.post('<?php echo site_url('/allcalls/getDataForXLS'); ?>',
                                            function (data) {
                                                $('<a></a>')
                                                        .attr('id', 'downloadFileXLS')
                                                        .attr('target', '_blank')
                                                        .attr('href', 'http://office.crm64.ru/file.xls')
                                                        .appendTo('body');
                                                $('#downloadFile').ready(function () {
                                                    $('#downloadFile').get(0).click();
                                                    setTimeout(function () {

                                                    }, 10000); // 0 milliseconds

                                                });
                                            }, 'json');
                                } else {

                                    $.post('<?php echo site_url('/allcalls/getCurrentDataForXLS'); ?>',
                                            function (data) {
                                                $('<a></a>')
                                                        .attr('id', 'downloadFileXLS')
                                                        .attr('target', '_blank')
                                                        .attr('href', 'http://office.crm64.ru/file.xls')
                                                        .appendTo('body');
                                                $('#downloadFileXLS').ready(function () {
                                                    $('#downloadFileXLS').get(0).click();
                                                    setTimeout(function () {
                                                        // Add to document using html, rather than tmpContainer

                                                    }, 10000); // 0 milliseconds

                                                });
                                            }, 'json');
                                }
                            }, 'json');
                });
                $('#saveCSV').click(function () {

                    /**
                     * Проверка на пустую таблицу для конкретного пользователя
                     * Если таблица пуста - сделать запрос на все звонки за день для
                     * конкретного пользователя
                     * Иначе (таблица не пуста) для конкретного пользователя есть
                     * данные от фильтра звонков
                     * 
                     * Такую же функцию сдлеать для CSV файла !!!
                     * */
                    $.post('<?php echo site_url('/allcalls/getRowsCountXLSTable'); ?>',
                            function (data) {
                                if (data > 0) {

                                    $.post('<?php echo site_url('/allcalls/getDataForCSV'); ?>',
                                            function (data) {
                                                $('<a></a>')
                                                        .attr('id', 'downloadFileCSV')
                                                        .attr('href', 'data:text/csv;charset=utf8,' + encodeURIComponent(data))
                                                        .attr('download', 'filename.csv')
                                                        .appendTo('body');
                                                $('#downloadFileCSV').ready(function () {
                                                    $('#downloadFileCSV').get(0).click();
                                                    setTimeout(function () {
                                                        // Add to document using html, rather than tmpContainer
                                                        $.post('<?php echo site_url('/allcalls/truncateTable'); ?>',
                                                                function (data) {

                                                                });
                                                    }, 10000); // 0 milliseconds

                                                });
                                            }, 'json');
                                } else {

                                    $.post('<?php echo site_url('/allcalls/getCurrentDataForCSV'); ?>',
                                            function (data) {
                                                $('<a></a>')
                                                        .attr('id', 'downloadFileCSV')
                                                        .attr('href', 'data:text/csv;charset=utf8,' + encodeURIComponent(data))
                                                        .attr('download', 'filename.csv')
                                                        .appendTo('body');
                                                $('#downloadFile').ready(function () {
                                                    $('#downloadFile').get(0).click();
                                                    setTimeout(function () {
                                                        // Add to document using html, rather than tmpContainer
                                                        $.post('<?php echo site_url('/allcalls/truncateTable'); ?>',
                                                                function (data) {

                                                                });
                                                    }, 10000); // 0 milliseconds

                                                });
                                            }, 'json');
                                }
                            }, 'json');
                });
                $('#filterDataButton').click(function () {

                    $.post('<?php echo site_url('/allcalls/truncateTable'); ?>',
                            function (data) {

                            });
                });
                function downloadFile(fileName, urlData) {

                    var aLink = document.createElement('a');
                    var evt = document.createEvent("HTMLEvents");
                    evt.initEvent("click");
                    aLink.download = fileName;
                    aLink.href = urlData;
                    aLink.dispatchEvent(evt);
                }

                var socket = io.connect('<?php echo $this->config->item('listner_address'); ?>', {'force new connection': true});
                var socket3 = io.connect('http://office.crm64.ru:3010');
                socket3.on('news', function (data) {
                    var datetime = data.datetime;
                    var description = data.description;
                    $.post('<?php echo site_url('/core/getUserParamsByID'); ?>',
                            {
                                'datetime': data.datetime,
                                'description': data.description,
                                'user_id': data.userid

                            }, function (data) {


                        $.each(data, function (i, value) {

                            alert(data[i].email_notification);
                            if (data[i].email_notification === "1") {
                                send_email_notification(description, data[i].email);
                            }

                            if (data[i].sms_notification === "1") {
                                send_sms_notification();
                            }

                            if (data[i].display_notification === "1") {
                                var message = "Напоминание в " + (new Date(datetime)).format('d.m.yyyy HH:mm:ss') + " <br/> Текст: " + description;
                                msg_system(message, 'success');
                            }

                            if (data[i].call_notification === "1") {
                                send_call_notification();
                            }

                        });
                    }, 'json');
                });
                function send_sms_notification() {

                }

                $('#organization').autocomplete({
                    serviceUrl: '/allcalls/searchOrganization',
                    type: 'get',
                    dataType: 'json',
                    onSelect: function (suggestion) {
                        console.info('You selected: ' + suggestion.value + ', ' + suggestion.data);
                        $('#organization_id').val(suggestion.data);
                    }
                });


                function send_email_notification(msg, address) {
                    $.post('<?php echo site_url('/core/sendReminderLetter'); ?>', {'msg': msg, 'address': address}, function (data) {

                    });
                }



                function send_call_notification() {

                }
                var messages = $("#messages");
                function msg_system(message, type, status, value) {

                    var m = notif({
                        msg: message,
                        type: type,
                        width: 300,
                        height: 300,
                        opacity: 1,
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
                        var result_xfer = string.match(regXfer); // поиск шаблона в юрл

                        var regV = new RegExp(phone_number, 'ig'); ///102/gi;     // шаблон
                        var result = string.match(regV); // поиск шаблона в юрл
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

                            $.post('<?php echo site_url('/core/viewCallEventUniversal'); ?>',
                                    function (data) {
                                        $("#lastTenCalls").empty();
                                        $("#lastTenCalls").append(data);
                                    });

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

                            $.post('<?php echo site_url('/core/viewCallEventUniversal'); ?>',
                                    function (data) {
                                        $("#lastTenCalls").empty();
                                        $("#lastTenCalls").append(data);
                                    });

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
                                location.reload();
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
                                location.reload();
                            }, 5000);
                        }
                    }

                    var trunk_name = "mera_dynamic_ANI/74#";
                    var external_phone_number = trunk_name + $('#hidden_external_phone_number').val();
                    if (data.event === "Dial" && data.subevent === "Begin") {

                        var calleridnum = data.calleridnum;
                        var dialstring = data.dialstring;
                        var string = data.channel; // юрл в котором происходит поиск


                        var regXfer = new RegExp('xfer', 'ig');
                        var result_xfer = string.match(regXfer); // поиск шаблона в юрл

                        var regV = new RegExp(external_phone_number, 'ig'); ///102/gi;     // шаблон
                        var result = string.match(regV); // поиск шаблона в юрл
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

                            $.post('<?php echo site_url('/core/viewCallEventUniversal'); ?>',
                                    function (data) {
                                        $("#lastTenCalls").empty();
                                        $("#lastTenCalls").append(data);
                                    });

                            var text = "Входящий звонок с номера: " + calleridnum;
                            var type = 'success';
                            $.totalStorage('call', 'In');

                            msg_system(text, type);




                            setTimeout(function () {
                                location.reload();
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

                            $.post('<?php echo site_url('/core/viewCallEventUniversal'); ?>',
                                    function (data) {
                                        $("#lastTenCalls").empty();
                                        $("#lastTenCalls").append(data);
                                    });

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

                            $.post('<?php echo site_url('/core/viewCallEventUniversal'); ?>',
                                    function (data) {
                                        $("#lastTenCalls").empty();
                                        $("#lastTenCalls").append(data);
                                    });

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
            function originateCall(internalNumb) {

                var originateDst = $('#originateDst').val();
                if ($.isNumeric(originateDst) === true) {
                    $('#originateDstText').css('display', 'none');
                    $.ajax({
                        url: '<?php echo site_url('/core/originateCall'); ?>',
                        type: "POST",
                        data: {originateDst: originateDst, internalNumb: internalNumb},
                        success: function (data) {
                            console.info(data);
                        }
                    });
                } else {
                    $('#originateDstText').css('display', 'block');
                }
            }

            function prepareOriginateCall(internal_number, src, dst, call_type) {
                //panel with buttons

//                console.info(src);
//                console.info(dst);
//                console.info(call_type);

                var phone_num = '';
                if (call_type === 'outgoing') {
                    phone_num = dst;
                    $.ajax({
                        url: '<?php echo site_url('/core/originateCall'); ?>',
                        type: "POST",
                        data: {originateDst: dst, internalNumb: internal_number},
                        success: function (data) {
                            console.info(data);
                        }
                    });
                } else {
                    phone_num = src;
                    $.ajax({
                        url: '<?php echo site_url('/core/originateCall'); ?>',
                        type: "POST",
                        data: {originateDst: src, internalNumb: internal_number},
                        success: function (data) {
                            console.info(data);
                        }
                    });
                }

            }

            function setTestPeriod() {

                if ($("#formTask input#task_create_date").val() !== "" && $("#formTask input#task_end_date").val() !== "") {
                    new_task_create_date = $("#formTask input#task_create_date").val().split(" ")[0];
                    new_task_end_date = $("#formTask input#task_end_date").val().split(" ")[0];
                    $("#formTask input#task_create_date").val(new_task_create_date + " 00:00");
                    $("#formTask input#task_end_date").val(new_task_end_date + " 23:59");

                }

                if ($("#formTask input#task_create_date").val() === "" && $("#formTask input#task_end_date").val() === "") {
                    $("#formTask input#task_create_date").val(new Date().format('dd.mm.yyyy 00:00'));
                    $("#formTask input#task_end_date").val(new Date().format('dd.mm.yyyy 23:59'));
                }
            }


        </script>
        <style>
            .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
            .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
            .autocomplete-selected { background: #F0F0F0; }
            .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
            .autocomplete-group { padding: 2px 5px; }
            .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }

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
            #allcalls td{
                vertical-align: middle;
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
            #myModal 
            {
                width:1024px;
                height:auto;
                max-height:100%;
            }
            .vertical-center {
                display: inline-block;
                vertical-align: middle !important;
                float: none;
            }
            /*            #myModal .modal-body {
                            width: 1280px;
                            height: 1024px;
                        }*/

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
                                <textarea id="task_description" name="task_description" class="input-xlarge" cols="10" rows="6"></textarea>
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
                            <label class="control-label" for="task_create_date">Дата начала</label>
                            <div class="controls">
                                <input id="task_create_date" name="task_create_date" type="text" class="input-medium" value="">

                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="task_end_date">Дата окончания</label>
                            <div class="controls">
                                <input id="task_end_date" name="task_end_date" type="text" class="input-medium" value="">

                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="setPeriod"></label>
                            <div class="controls">
                                <a id="setPeriod" onclick="setTestPeriod();
                                        return false;" name="setPeriod" class="btn btn-mini btn-warning" >Весь день</a>
                            </div>
                        </div>

                        <div class="controls">
                            <label class="checkbox" for="checkboxes-report">
                                <input type="checkbox" name="checkboxes-reminder" id="checkboxes-reminder" value="1">
                                Напоминание
                            </label>
                        </div>

                        <div class="control-group" id="checkboxes_reminder_block" style="display: none;">
                            <div class="control-group">
                                <label class="control-label" for="task_reminder_date">Дата напоминания</label>
                                <div class="controls">
                                    <input name="task_reminder_date" type="text" id="task_reminder_date" class="input-medium" value="">

                                </div>
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
                        <button id="button2idFormTask" name="button2id" class="btn btn-danger" data-dismiss="modal">Отменить</button>
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

        <!-- Modal -->
        <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 id="myModalLabel">Карточка клиента</h3>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-condensed" summary="" id="organizationDetails" style="border-collapse:collapse; font-size: 11px;">
                    <tbody>

                    </tbody>
                </table>

                <h5>Контакты организации</h5>
                <table class="table table-striped table-bordered table-condensed" summary="" id="contactList" style="border-collapse:collapse; font-size: 11px;">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                ФИО
                            </th>
                            <th>
                                Должность
                            </th>
                            <th>
                                Телефон
                            </th>
                            <th>
                                Email
                            </th>
                            <th>
                                Адрес
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

                <h5>Записи организации</h5>
                <div style="display:inline-block;width:800px;">
                    <?php
                    echo anchor("records/addTask/", "<i class='icon-tasks'> </i>Создать запись", "class='btn btn-small btn-info pull-left'");
                    ?>
                </div>

                <table class="table table-striped table-bordered table-condensed" id='allContactsTable' style="border-collapse:collapse; font-size: 11px;">
                    <thead>
                    <th>#</th>
                    <th>Тема</th>
                    <th>Инициатор</th>
                    <th>Назначена</th>
                    <th>Создана</th>
                    <th>Окончена</th>
                    <th>Действия</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-primary">Save changes</button>
            </div>
        </div>


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
                            <label for="contact_name" class="control-label">ФИО</label>
                            <div class="controls">
                                <input type="text" name="contact_name" value="" id="contact_name" placeholder="" class="input-xlarge">
                                <div class="alert alert-error" name="contact_warning_text" class="error" id="contact_warning_text" style="display: none; width: 230px;">
                                    Заполните поле "ФИО"
                                </div>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="control-group">
                            <label for="organization" class="control-label">Организация</label>
                            <div class="controls">
                                <input type="text" name="organization" value="" id="organization" placeholder="" class="input-xlarge">
                                <input type="hidden" name="organization_id" id="organization_id" value="" />
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="control-group">
                            <label for="job_position" class="control-label">Должность</label>
                            <div class="controls">
                                <input type="text" name="job_position" value="" id="job_position" placeholder="" class="input-xlarge">
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="control-group">
                            <label for="private_phone_number" class="control-label">Телефон (основной)</label>
                            <div class="controls">
                                <input type="text" name="private_phone_number" value="" id="private_phone_number" placeholder="" class="input-xlarge">
                                <div class="alert alert-error" name="phone_warning_text" id="phone_warning_text" style="display: none; width: 230px;">
                                    Заполните поле "Телефон" 
                                </div>
                            </div>
                        </div>
                        <!-- Text input-->
                        <div class="control-group">
                            <label for="mobile_number" class="control-label">Мобильный</label>
                            <div class="controls">
                                <input type="text" name="mobile_number" value="" id="mobile_number" placeholder="" class="input-xlarge">
                            </div>
                        </div>
                        <!-- Text input-->
                        <div class="control-group">
                            <label for="email" class="control-label">Email</label>
                            <div class="controls">
                                <input type="text" name="email" value="" id="email" placeholder="" class="input-xlarge">
                            </div>
                        </div>
                        <!-- Text input-->
                        <div class="control-group">
                            <label for="address" class="control-label">Адрес</label>
                            <div class="controls">
                                <input type="text" name="address" value="" id="address" placeholder="" class="input-xlarge"> 
                            </div>
                        </div>
                        <!-- Text input-->
                        <div class="control-group">
                            <label for="birthday" class="control-label">Дата рождения</label>
                            <div class="controls">
                                <input type="text" name="birthday" value="" id="birthday" placeholder="" class="input-xlarge">
                            </div>
                        </div>

                        <div class="control-group">
                            <label for="comment" class="control-label">Дополнительно</label>
                            <div class="controls">
                                <textarea name="comment" cols="40" rows="4" id="comment" placeholder="" class="input-xlarge"></textarea>
                            </div>
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
