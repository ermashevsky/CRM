<body>
    <div class="container-fluid">
        <div class="row">
            <div class="span10">
                <h3 style="color:#08c;">Записи</h3>
                <div style="display:inline-block;width:800px;">
                    <?php
                    echo anchor("records/addTask/", "<i class='icon-tasks'> </i>Создать запись", "class='btn btn-small btn-info pull-left'");
                    ?>
                    <div class="btn-group">
                        <a class="btn dropdown-toggle btn-small  btn-success inline" data-toggle="dropdown" href="#">
                            <i class="icon-filter"> </i> Фильтр записей
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#" onclick="allRec();
                                    return false;">Все записи</a></li>
                            <li><a href="#" onclick="completeRec();
                                    return false;">Выполненные</a></li>
                            <li><a href="#" onclick="inWorkRec();
                                    return false;">В работе</a></li>
                            <li><a href="#" onclick="activeRec();
                                    return false;">Активные</a></li>
                            <li><a href="#" onclick="overdueRec();
                                    return false;">Просроченные</a></li>
                        </ul>
                        <div class="input-append pull-right">
                            <input type="text" name="dateRange" id="dateRange" class="input-small" style="height: 16px !important" placeholder="Укажите дату"/>
                            <button type="button" name="dateRangeButton" id="dateRangeButton" class="btn btn-small btn-info"><i class="icon-check"> </i></button>
                        </div>
                    </div>

                </div>

                <table class="table table-striped table-bordered table-condensed" id='allContactsTable'>
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
                        <?php
                        $count = 1;
                        $crmUser = new Records();
                        foreach ($table as $rows) {

                            if ($rows->create_date == '0000-00-00 00:00:00') {
                                $create_date = '';
                            } else {
                                $create_date = date('d.m.Y H:i:s', strtotime($rows->create_date));
                            }

                            if ($rows->end_date == '0000-00-00 00:00:00') {
                                $end_date = '';
                            } else {
                                $end_date = date('d.m.Y H:i:s', strtotime($rows->end_date));
                            }

                            echo '<tr>'
                            . '<td style="background-color: '.$rows->td_color.'">' . $count++ . '</td>'
                            . '<td style="background-color: '.$rows->td_color.'">';
                            if ($rows->task_name !== "") {
                                echo anchor("records/viewTask/" . $rows->id, $rows->task_name);
                            } else {
                                echo anchor("records/viewTask/" . $rows->id, substr($rows->task_description, 0, 80) . '...');
                            }
                            echo '</td>';
                            echo '<td style="background-color: '.$rows->td_color.'">' . $rows->initiator . '</td>';
                            echo '<td style="background-color: '.$rows->td_color.'">' . $crmUser->getUserById($rows->assigned) . '</td>'
                            . '<td style="background-color: '.$rows->td_color.'">' . $create_date . '</td>'
                            . '<td style="background-color: '.$rows->td_color.'">' . $end_date . '</td>'
                            . '<td style="background-color: '.$rows->td_color.'">'
                            . '<div class="btn-group">'
                            . '<button type="button" class="btn btn-mini btn-info" title="Выполнено" onclick="doneRecord(' . $rows->id . '); return false;"><i class="icon-check"> </i></button>'
                            . '<button type="button" class="btn btn-mini btn-success" title="Редактировать"  onclick="redirect2EditRecord(' . $rows->id . '); return false;"><i class="icon-edit"> </i></button>'
                            . '<button type="button" class="btn btn-mini btn-danger" title="Удалить" onclick="delRecord(' . $rows->id . '); return false;"><i class="icon-trash"> </i></button>'
                            . '</div>'
                            . '</td>'
                            . '</tr>';
                        }
                        //records/deleteTask
                        ?>
                    </tbody>
                </table>
            </div><!--/span-->
