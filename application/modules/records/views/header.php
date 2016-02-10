<!-- /project_dir/index.html -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex,nofollow"/>
        <title>Office WebCRM </title>
        <script type="text/javascript" src="/assets/js/jquery-latest.js"></script>
        <script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/assets/js/bootstrap-button.js"></script>
        <script type="text/javascript" src="/assets/js/bootstrap-fileupload.js"></script>
        <script type="text/javascript" src="/assets/js/bootstrap-notify.js"></script>
        <script type="text/javascript" src="/assets/js/bootstrap-wysiwyg.js"></script>
        <script type="text/javascript" src="/assets/js/jquery.uploadify.min.js"></script>
        <script type="text/javascript" src="/assets/js/bootbox.min.js"></script>
        <script type="text/javascript" src="/assets/js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="/assets/js/bootstrap-progressbar.js"></script>
        <script type="text/javascript" src="/assets/js/bootstrap-tooltip.js"></script>
        <script type="text/javascript" src="/assets/js/bootstrap-tagsinput.js"></script>
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

        <script src="<?php echo $this->config->item('listner_socket_address'); ?>"></script>
        <script src="http://office.crm64.ru:3010/socket.io/socket.io.js"></script>
        <script type="text/javascript">
            function addRecord(phone_num) {

                var phone_number = $('#phone_num_hide' + phone_num).val();
                var id_call = $('#id_call' + phone_num).val();

                $.post('<?php echo site_url('/core/getContactDetail'); ?>', {'phone_number': phone_number},
                function (data) {
                    $('#phone_num').val(phone_number);
                    $('#contact').val(data);
                    $('#id_call').val(id_call);
                    $('#source_records').val('Модуль - История звонков');
                });

                $('#taskWindow').modal('show');
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

            function redirect2EditRecord(id) {
                window.location.replace("/records/editTask/" + id);
            }

            function insertDataOrganization() {

                var MyRows = $('table#contact_list').find('tbody').find('tr');
                for (var i = 0; i < MyRows.length; i++) {
                    var MyIndexValue = $(MyRows[i]).find('td:eq(0)').html();
                    console.info(MyIndexValue);
                }
            }

            function deleteFromOrganization(id) {
                bootbox.confirm("Действительно хотите исключить из организации?", function (result) {
                    if (result) {
                        $.post('<?php echo site_url('/addressbook/deleteFromOrganization'); ?>', {'id': id},
                        function (data) {
                            location.reload();
                        }, 'json');
                    } else {
                        console.log("User declined dialog");
                    }
                });
            }

            function originateCall(internalNumb) {

                var originateDst = $('#originateDst').val();
                if ($.isNumeric(originateDst) === true) {
                    $('#originateDstText').css('display','none');
                    $.ajax({
                        url: '<?php echo site_url('/core/originateCall'); ?>',
                        type: "POST",
                        data: {originateDst: originateDst, internalNumb: internalNumb},
                        success: function (data) {
                            console.info(data);
                        }
                    });
                } else {
                    $('#originateDstText').css('display','block');
                }
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

            function saveNewContact() {

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
                        {'organization_id': organization_id, 'contact_name': contact_name, 'job_position': job_position,
                            'private_phone_number': private_phone_number, 'mobile_number': mobile_number, 'email': email, 'address': address,
                            'birthday': birthday, 'comment': comment},
                function (data) {
                    location.reload();
                });

            }

            function deleteOrganization(id) {
                $.post('<?php echo site_url('/addressbook/getContactsOrganization'); ?>', {'id': id},
                function (data) {
                    if (data > 0) {
                        bootbox.dialog("В организации существуют контакты.<br/>Удалить организацию вместе с контактами?", [{
                                "label": "Да",
                                "class": "btn-default",
                                "callback": function () {
                                    $.post('<?php echo site_url('/addressbook/deleteOrganizationWithContacts'); ?>', {'id': id},
                                    function (data) {
                                        window.location.replace("/");
                                    }, 'json');
                                }
                            }, {
                                "label": "Нет",
                                "class": "btn-default",
                                "callback": function () {
                                    $.post('<?php echo site_url('/addressbook/deleteOrganizationWithoutContacts'); ?>', {'id': id},
                                    function (data) {
                                        window.location.replace("/");
                                    }, 'json');
                                }
                            }]);
                    }
                    if (data === 0) {
                        bootbox.dialog("Удалить организацию ?", [{
                                "label": "Да",
                                "class": "btn-default",
                                "callback": function () {
                                    $.post('<?php echo site_url('/addressbook/deleteOrganization'); ?>', {'id': id},
                                    function (data) {

                                    });
                                }
                            }, {
                                "label": "Нет",
                                "class": "btn-default",
                                "callback": function () {

                                }
                            }]);


                    }
                }, 'json');
            }

            function delRecord(id) {
                bootbox.dialog("Удалить запись?", [{
                        "label": "Да",
                        "class": "btn-default",
                        "callback": function () {
                            $.post('<?php echo site_url('/records/deleteTask'); ?>', {'id': id},
                            function (data) {
                                location.reload();
                            });
                        }
                    }, {
                        "label": "Нет",
                        "class": "btn-default",
                        "callback": function () {

                        }
                    }]);
            }

            function doneRecord(id) {
                bootbox.dialog("Нажмите кнопку 'ДА' чтобы выполнить.", [{
                        "label": "Да",
                        "class": "btn-default",
                        "callback": function () {
                            $.post('<?php echo site_url('/records/doneRecord'); ?>', {'id': id},
                            function (data) {
                                location.reload();
                            });
                        }
                    }, {
                        "label": "Нет",
                        "class": "btn-default",
                        "callback": function () {

                        }
                    }]);
            }

            function activeRec() {
                $('#dateRange').val(new Date().format('d.m.yyyy'));
                $('#dateRange').fadeIn();
                $('#dateRangeButton').fadeIn();
            }

            function completeRec() {
                $('#dateRange').val("");
                $('#dateRange').fadeOut();
                $('#dateRangeButton').fadeOut();

                $.post('<?php echo site_url('/records/getExecutionEndRec'); ?>',
                        function (data) {
                            console.info(data);
                            $("#allContactsTable").dataTable().fnClearTable();
                            var t = $('#allContactsTable').DataTable();
                            var n = 1;

                            $.each(data, function (i, val) {

                                if (data[i].task_name !== "") {

                                    var end_date = "";
                                    var create_date = "";

                                    if (data[i].end_date === "0000-00-00 00:00:00") {
                                        end_date = "";
                                    } else {
                                        end_date = new Date(data[i].end_date).format('dd.mm.yyyy HH:MM:ss');
                                    }

                                    if (data[i].create_date === "0000-00-00 00:00:00") {
                                        create_date = "";
                                    } else {
                                        create_date = new Date(data[i].create_date).format('dd.mm.yyyy HH:MM:ss');
                                    }

                                    t.dataTable().fnAddData([n++, '<a href="records/viewTask/' + data[i].id + '">' + data[i].task_name + '</a>', data[i].initiator, data[i].assigned, create_date, end_date, '<div class="btn-group"><button type="button" class="btn btn-small btn-info" title="Выполнено" onclick="doneRecord(' + data[i].id + '); return false;"><i class="icon-check"> </i></button><button type="button" class="btn btn-small btn-success" title="Редактировать"  onclick="redirect2EditRecord(' + data[i].id + '); return false;"><i class="icon-edit"> </i></button><button type="button" class="btn btn-small btn-danger" title="Удалить" onclick="delRecord(' + data[i].id + '); return false;"><i class="icon-trash"> </i></button></div>']);
                                    $('#allContactsTable td').css('background-color', '#90ee90');
                                } else {

                                    var end_date = "";
                                    var create_date = "";

                                    if (data[i].end_date === "0000-00-00 00:00:00") {
                                        end_date = "";
                                    } else {
                                        end_date = new Date(data[i].end_date).format('dd.mm.yyyy HH:MM:ss');
                                    }

                                    if (data[i].create_date === "0000-00-00 00:00:00") {
                                        create_date = "";
                                    } else {
                                        create_date = new Date(data[i].create_date).format('dd.mm.yyyy HH:MM:ss');
                                    }

                                    t.dataTable().fnAddData([n++, '<a href="records/viewTask/' + data[i].id + '">' + data[i].task_description + '</a>', data[i].initiator, data[i].assigned, create_date, end_date, '<div class="btn-group"><button type="button" class="btn btn-small btn-info" title="Выполнено" onclick="doneRecord(' + data[i].id + '); return false;"><i class="icon-check"> </i></button><button type="button" class="btn btn-small btn-success" title="Редактировать"  onclick="redirect2EditRecord(' + data[i].id + '); return false;"><i class="icon-edit"> </i></button><button type="button" class="btn btn-small btn-danger" title="Удалить" onclick="delRecord(' + data[i].id + '); return false;"><i class="icon-trash"> </i></button></div>']);
                                    $('#allContactsTable td').css('background-color', '#90ee90');
                                }

                            });
                        }, 'json');
            }

            function overdueRec() {
                $('#dateRange').val("");
                $('#dateRange').fadeOut();
                $('#dateRangeButton').fadeOut();

                $.post('<?php echo site_url('/records/getOverDueRec'); ?>',
                        function (data) {
                            console.info(data);
                            $("#allContactsTable").dataTable().fnClearTable();
                            var t = $('#allContactsTable').DataTable();
                            var n = 1;

                            $.each(data, function (i, val) {

                                if (data[i].task_name !== "") {

                                    var end_date = "";
                                    var create_date = "";

                                    if (data[i].end_date === "0000-00-00 00:00:00") {
                                        end_date = "";
                                    } else {
                                        end_date = new Date(data[i].end_date).format('dd.mm.yyyy HH:MM:ss');
                                    }

                                    if (data[i].create_date === "0000-00-00 00:00:00") {
                                        create_date = "";
                                    } else {
                                        create_date = new Date(data[i].create_date).format('dd.mm.yyyy HH:MM:ss');
                                    }

                                    t.dataTable().fnAddData([n++, '<a href="records/viewTask/' + data[i].id + '">' + data[i].task_name + '</a>', data[i].initiator, data[i].assigned, create_date, end_date, '<div class="btn-group"><button type="button" class="btn btn-small btn-info" title="Выполнено" onclick="doneRecord(' + data[i].id + '); return false;"><i class="icon-check"> </i></button><button type="button" class="btn btn-small btn-success" title="Редактировать"  onclick="redirect2EditRecord(' + data[i].id + '); return false;"><i class="icon-edit"> </i></button><button type="button" class="btn btn-small btn-danger" title="Удалить" onclick="delRecord(' + data[i].id + '); return false;"><i class="icon-trash"> </i></button></div>']);
                                    $('#allContactsTable td').css('background-color', '#ffc0cb');
                                } else {

                                    var end_date = "";
                                    var create_date = "";

                                    if (data[i].end_date === "0000-00-00 00:00:00") {
                                        end_date = "";
                                    } else {
                                        end_date = new Date(data[i].end_date).format('dd.mm.yyyy HH:MM:ss');
                                    }

                                    if (data[i].create_date === "0000-00-00 00:00:00") {
                                        create_date = "";
                                    } else {
                                        create_date = new Date(data[i].create_date).format('dd.mm.yyyy HH:MM:ss');
                                    }

                                    t.dataTable().fnAddData([n++, '<a href="records/viewTask/' + data[i].id + '">' + data[i].task_description + '</a>', data[i].initiator, data[i].assigned, create_date, end_date, '<div class="btn-group"><button type="button" class="btn btn-small btn-info" title="Выполнено" onclick="doneRecord(' + data[i].id + '); return false;"><i class="icon-check"> </i></button><button type="button" class="btn btn-small btn-success" title="Редактировать"  onclick="redirect2EditRecord(' + data[i].id + '); return false;"><i class="icon-edit"> </i></button><button type="button" class="btn btn-small btn-danger" title="Удалить" onclick="delRecord(' + data[i].id + '); return false;"><i class="icon-trash"> </i></button></div>']);
                                    $('#allContactsTable td').css('background-color', '#ffc0cb');
                                }

                            });
                        }, 'json');
            }

            function inWorkRec() {
                $('#dateRange').val("");
                $('#dateRange').fadeOut();
                $('#dateRangeButton').fadeOut();

                $.post('<?php echo site_url('/records/getInWorkRec'); ?>',
                        function (data) {
                            console.info(data);
                            $("#allContactsTable").dataTable().fnClearTable();
                            var t = $('#allContactsTable').DataTable();
                            var n = 1;

                            $.each(data, function (i, val) {

                                if (data[i].task_name !== "") {

                                    var end_date = "";
                                    var create_date = "";

                                    if (data[i].end_date === "0000-00-00 00:00:00") {
                                        end_date = "";
                                    } else {
                                        end_date = new Date(data[i].end_date).format('dd.mm.yyyy HH:MM:ss');
                                    }

                                    if (data[i].create_date === "0000-00-00 00:00:00") {
                                        create_date = "";
                                    } else {
                                        create_date = new Date(data[i].create_date).format('dd.mm.yyyy HH:MM:ss');
                                    }

                                    t.dataTable().fnAddData([n++, '<a href="records/viewTask/' + data[i].id + '">' + data[i].task_name + '</a>', data[i].initiator, data[i].assigned, create_date, end_date, '<div class="btn-group"><button type="button" class="btn btn-small btn-info" title="Выполнено" onclick="doneRecord(' + data[i].id + '); return false;"><i class="icon-check"> </i></button><button type="button" class="btn btn-small btn-success" title="Редактировать"  onclick="redirect2EditRecord(' + data[i].id + '); return false;"><i class="icon-edit"> </i></button><button type="button" class="btn btn-small btn-danger" title="Удалить" onclick="delRecord(' + data[i].id + '); return false;"><i class="icon-trash"> </i></button></div>']);
                                    $('#allContactsTable td').css('background-color', '#87cefa');
                                } else {

                                    var end_date = "";
                                    var create_date = "";

                                    if (data[i].end_date === "0000-00-00 00:00:00") {
                                        end_date = "";
                                    } else {
                                        end_date = new Date(data[i].end_date).format('dd.mm.yyyy HH:MM:ss');
                                    }

                                    if (data[i].create_date === "0000-00-00 00:00:00") {
                                        create_date = "";
                                    } else {
                                        create_date = new Date(data[i].create_date).format('dd.mm.yyyy HH:MM:ss');
                                    }

                                    t.dataTable().fnAddData([n++, '<a href="records/viewTask/' + data[i].id + '">' + data[i].task_description + '</a>', data[i].initiator, data[i].assigned, create_date, end_date, '<div class="btn-group"><button type="button" class="btn btn-small btn-info" title="Выполнено" onclick="doneRecord(' + data[i].id + '); return false;"><i class="icon-check"> </i></button><button type="button" class="btn btn-small btn-success" title="Редактировать"  onclick="redirect2EditRecord(' + data[i].id + '); return false;"><i class="icon-edit"> </i></button><button type="button" class="btn btn-small btn-danger" title="Удалить" onclick="delRecord(' + data[i].id + '); return false;"><i class="icon-trash"> </i></button></div>']);
                                    $('#allContactsTable td').css('background-color', '#87cefa');
                                }

                            });
                        }, 'json');
            }

            function allRec() {
//                $('#dateRange').val("");
//                $('#dateRange').fadeOut();
//                $('#dateRangeButton').fadeOut();
//
//                $.post('<?php echo site_url('/records/getAllRec'); ?>',
//                        function (data) {
//                            console.info(data);
//                            $("#allContactsTable").dataTable().fnClearTable();
//                            var t = $('#allContactsTable').DataTable();
//                            var n = 1;
//
//                            $.each(data, function (i, val) {
//
//                                if (data[i].task_name !== "") {
//
//                                    var end_date = "";
//                                    var create_date = "";
//
//                                    if (data[i].end_date === "0000-00-00 00:00:00") {
//                                        end_date = "";
//                                    } else {
//                                        end_date = new Date(data[i].end_date).format('dd.mm.yyyy HH:MM:ss');
//                                    }
//
//                                    if (data[i].create_date === "0000-00-00 00:00:00") {
//                                        create_date = "";
//                                    } else {
//                                        create_date = new Date(data[i].create_date).format('dd.mm.yyyy HH:MM:ss');
//                                    }
//
//                                    t.dataTable().fnAddData([n++, '<a href="records/viewTask/' + data[i].id + '">' + data[i].task_name + '</a>', data[i].initiator, data[i].assigned, create_date, end_date, '<div class="btn-group"><button type="button" class="btn btn-small btn-info" title="Выполнено" onclick="doneRecord(' + data[i].id + '); return false;"><i class="icon-check"> </i></button><button type="button" class="btn btn-small btn-success" title="Редактировать"  onclick="redirect2EditRecord(' + data[i].id + '); return false;"><i class="icon-edit"> </i></button><button type="button" class="btn btn-small btn-danger" title="Удалить" onclick="delRecord(' + data[i].id + '); return false;"><i class="icon-trash"> </i></button></div>']);
//                                    
//                                    
//                                } else {
//
//                                    var end_date = "";
//                                    var create_date = "";
//
//                                    if (data[i].end_date === "0000-00-00 00:00:00") {
//                                        end_date = "";
//                                    } else {
//                                        end_date = new Date(data[i].end_date).format('dd.mm.yyyy HH:MM:ss');
//                                    }
//
//                                    if (data[i].create_date === "0000-00-00 00:00:00") {
//                                        create_date = "";
//                                    } else {
//                                        create_date = new Date(data[i].create_date).format('dd.mm.yyyy HH:MM:ss');
//                                    }
//
//                                    t.dataTable().fnAddData([n++, '<a href="records/viewTask/' + data[i].id + '">' + data[i].task_description + '</a>', data[i].initiator, data[i].assigned, create_date, end_date, '<div class="btn-group"><button type="button" class="btn btn-small btn-info" title="Выполнено" onclick="doneRecord(' + data[i].id + '); return false;"><i class="icon-check"> </i></button><button type="button" class="btn btn-small btn-success" title="Редактировать"  onclick="redirect2EditRecord(' + data[i].id + '); return false;"><i class="icon-edit"> </i></button><button type="button" class="btn btn-small btn-danger" title="Удалить" onclick="delRecord(' + data[i].id + '); return false;"><i class="icon-trash"> </i></button></div>']);
//                                    
//                                }
//
//                            });
//                        }, 'json');
                window.location.reload();
            }

            // javascript code
            function getval(sel) {
                if (sel.value !== '') {
                    $.post('<?php echo site_url('/addressbook/getContactById'); ?>', {'id': sel.value}, function (data) {
                        console.info(data);
                        var counter = 0;
                        $.each(data, function (i, val) {
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

            function getCRMUsers() {
                $.post('<?php echo site_url('/records/getCRMUsers'); ?>',
                        function (data) {
                            $.each(data, function (i, val) {
                                $("#selectAssigned").append('<option value="' + data[i].id + '">' + data[i].first_name + ' ' + data[i].last_name + '</option>');
                            });
                        }, 'json');
            }
            ;
            
            function setPeriod(){
                
                if($("#formRec input#task_create_date").val() !== "" && $("#formRec input#task_end_date").val() !== ""){
                    new_task_create_date = $("#formRec input#task_create_date").val().split(" ")[0];
                    new_task_end_date = $("#formRec input#task_end_date").val().split(" ")[0];
                    $("#formRec input#task_create_date").val(new_task_create_date+" 00:00");
                    $("#formRec input#task_end_date").val(new_task_end_date+" 23:59");
                    
                }
                
                if($("#formRec input#task_create_date").val() === "" && $("#formRec input#task_end_date").val() === ""){
                    $("#formRec input#task_create_date").val(new Date().format('dd.mm.yyyy 00:00'));
                    $("#formRec input#task_end_date").val(new Date().format('dd.mm.yyyy 23:59'));
                }
                
            }

// /project_dir/index.html
            $(document).ready(function () {
                
                $('#button2id').click(function(){
                   $("#formTask")[0].reset(); 
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

                $("form#formRec input#checkboxes-reminder").click(function () {
                    console.info('ok');
                    if ($('form#formRec input:checkbox[name=checkboxes-reminder]').is(':checked')) {

                        console.info('Is Checked');

                        $('form#formRec #checkboxes_reminder_block').css('display', 'block');

                    } else {

                        console.info('Is Not Checked');

                        $('form#formRec #checkboxes_reminder_block').css('display', 'none');
                        $("form#formRec #task_reminder_date").val("");

                    }

                });

                function getAssignedName(id) {
                    $.post('<?php echo site_url('/records/getAssignedUserById'); ?>', {'id': id},
                    function (data) {
                        return 'data';
                    });
                }

                $('#dateRange').hide();
                $('#dateRangeButton').hide();

                $('#dateRange').focusout(function () {
                    console.info($(this).val());

                    $.post('<?php echo site_url('/records/getActiveRec'); ?>', {'date': $(this).val()},
                    function (data) {

                        console.info(data);
                        $("#allContactsTable").dataTable().fnClearTable();
                        var t = $('#allContactsTable').DataTable();
                        var n = 1;

                        $.each(data, function (i, val) {

                            if (data[i].task_name !== "") {

                                var end_date = "";
                                var create_date = "";

                                if (data[i].end_date === "0000-00-00 00:00:00") {
                                    end_date = "";
                                } else {
                                    end_date = new Date(data[i].end_date).format('dd.mm.yyyy HH:MM:ss');
                                }

                                if (data[i].create_date === "0000-00-00 00:00:00") {
                                    create_date = "";
                                } else {
                                    create_date = new Date(data[i].create_date).format('dd.mm.yyyy HH:MM:ss');
                                }

                                t.dataTable().fnAddData([n++, '<a href="records/viewTask/' + data[i].id + '">' + data[i].task_name + '</a>', data[i].initiator, data[i].assigned, create_date, end_date, '<div class="btn-group"><button type="button" class="btn btn-small btn-info" title="Выполнено" onclick="doneRecord(' + data[i].id + '); return false;"><i class="icon-check"> </i></button><button type="button" class="btn btn-small btn-success" title="Редактировать"  onclick="redirect2EditRecord(' + data[i].id + '); return false;"><i class="icon-edit"> </i></button><button type="button" class="btn btn-small btn-danger" title="Удалить" onclick="delRecord(' + data[i].id + '); return false;"><i class="icon-trash"> </i></button></div>']);
                                $('#allContactsTable td').css('background-color', '#87cefa');

                            } else {
                                var end_date = "";
                                var create_date = "";

                                if (data[i].end_date === "0000-00-00 00:00:00") {
                                    end_date = "";
                                } else {
                                    end_date = new Date(data[i].end_date).format('dd.mm.yyyy HH:MM:ss');
                                }

                                if (data[i].create_date === "0000-00-00 00:00:00") {
                                    create_date = "";
                                } else {
                                    create_date = new Date(data[i].create_date).format('dd.mm.yyyy HH:MM:ss');
                                }

                                t.dataTable().fnAddData([n++, '<a href="records/viewTask/' + data[i].id + '">' + data[i].task_description + '</a>', data[i].initiator, data[i].assigned, create_date, end_date, '<div class="btn-group"><button type="button" class="btn btn-small btn-info" title="Выполнено" onclick="doneRecord(' + data[i].id + '); return false;"><i class="icon-check"> </i></button><button type="button" class="btn btn-small btn-success" title="Редактировать"  onclick="redirect2EditRecord(' + data[i].id + '); return false;"><i class="icon-edit"> </i></button><button type="button" class="btn btn-small btn-danger" title="Удалить" onclick="delRecord(' + data[i].id + '); return false;"><i class="icon-trash"> </i></button></div>']);
                                $('#allContactsTable td').css('background-color', '#87cefa');

                            }

                        });


                    }, 'json');

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

                $("#dateRange").datetimepicker({
                    format: 'd.m.Y',
                    value: new Date().format('d.m.Y'),
                    lang: 'ru',
                    timepicker: false,
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

                $("form#formRec #task_reminder_date").datetimepicker({
                    format: 'd.m.Y H:i',
                    lang: 'ru',
                    timepicker: true,
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
                });

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
                $('#checkboxes-reminder').change(function () {
                    if (this.checked)
                        $('#reminder_block').fadeIn('fast');

                    else
                        $('#reminder_block').fadeOut('fast');
                    $('#reminder_date').val('');

                });
                $('#contactList').dataTable({
                    "sPaginationType": "bootstrap",
                    "oLanguage": {
                        "sUrl": "/assets/js/dataTables.russian.txt"
                    },
                    "aaSorting": [[0, "asc"]]
                });
                $.extend($.fn.dataTableExt.oStdClasses, {
                    "sWrapper": "dataTables_wrapper form-inline"
                });

                $('#allContactsTable').dataTable({
                    "sPaginationType": "bootstrap",
                    "oLanguage": {
                        "sUrl": "/assets/js/dataTables.russian.txt"
                    },
                    "aaSorting": [[0, "asc"]]
                });
                $.extend($.fn.dataTableExt.oStdClasses, {
                    "sWrapper": "dataTables_wrapper form-inline"
                });

                $.post('<?php echo site_url('/addressbook/getAllContacts'); ?>', function (data) {
                    console.info(data);
                    var select = $('#selectContact');
                    $.each(data, function (i, val) {
                        select.append('<option value="' + data[i].id + '">' + data[i].contact_name + '</option>');

                    });
                }, 'json');

                var datas = [];

                $.post('<?php echo site_url('/addressbook/getAllOrganizations'); ?>', function (data) {
                    console.info(data);
                    $.each(data, function (i, value) {
                        datas.push({id: data[i].id, text: data[i].organization_name});
                    });//Тут запихиваю в data то что приходит с аякса.
                    $('.select2field').select2({
                        placeholder: "Поиск организации",
                        minimumInputLength: 3,
                        allowClear: true,
                        width: 280,
                        createSearchChoice: function (term, data) {
                            if ($(data).filter(function () {
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

                $('#selectAssigned').on('change', function () {
                    if (this.value !== '') {
                        console.info('Not Empty');
                        $('#checkboxes_report_block').css('display', 'block');
                    } else {
                        $('#checkboxes_report_block').css('display', 'none');
                    }
                });


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

                $("input#create_date .input-medium").datetimepicker({
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

                $("input#task_create_date").datetimepicker({
                    format: 'd.m.Y H:i',
                    lang: 'ru',
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
                });

                $("input#task_end_date").datetimepicker({
                    format: 'd.m.Y H:i',
                    lang: 'ru',
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
                });

                $("#report_date").datetimepicker({
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

                var socket = io.connect('<?php echo $this->config->item('listner_address'); ?>');
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
                        console.info(data);

                        $.each(data, function (i, value) {

                            if (data[i].sms_notification === "1") {
                                send_sms_notification();
                            }

                            if (data[i].display_notification === "1") {
                                var message = "Напоминание в " + (new Date(datetime)).format('d.m.yyyy HH:mm:ss') + " <br/> Текст: " + description;
                                msg_system(message, 'success');
                            }

                            if (data[i].email_notification === "1") {
                                send_email_notification(description, data[i].email);
                            }

                            if (data[i].call_notification === "1") {
                                send_call_notification();
                            }

                        });

                    }, 'json');

                });

                function send_sms_notification() {

                }

                function send_email_notification(msg, address) {
                    $.post('<?php echo site_url('/core/sendReminderLetter'); ?>', {'msg': msg, 'address': address}, function (data) {

                    });

                }

                function send_call_notification() {

                }

                var messages = $("#messages");

                function msg_system(message, type) {

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
                            
                            $.post('<?php echo site_url('/core/viewCallEventUniversal'); ?>',
                                    function (data) {
                                        $("#lastTenCalls").empty();
                                        $("#lastTenCalls").append(data);
                                    });
                            
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
                            
                            $.post('<?php echo site_url('/core/viewCallEventUniversal'); ?>',
                                    function (data) {
                                        $("#lastTenCalls").empty();
                                        $("#lastTenCalls").append(data);
                                    });

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
                                <input id="contact" name="selectContact" type="text" placeholder="" class="input-xlarge" value="">
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
                                <a id="setPeriod" onclick="setPeriod(); return false;" name="setPeriod" class="btn btn-mini btn-warning" >Весь день</a>
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
                        <button id="button2id" name="button2id" class="btn btn-danger" data-dismiss="modal">Отменить</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Task Modal Form -->