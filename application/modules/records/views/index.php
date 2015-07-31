<body>
    <div class="container-fluid">
        <div class="row">
            <div class="span10">
                <h3 style="color:#08c;">Задачи</h3>
                <?php
                echo anchor("records/addTask/", "<i class='icon-tasks'> </i>Создать запись", "class='btn btn-small btn-info pull-left'");
                ?>
                <!--
                                <div class="span4">
                                    <div class="btn-toolbar pull-left">
                                    <ul class="nav nav-pills">
                                        
                                    </ul>
                                </div>
                                </div>-->
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
                            }else{
                                $create_date = date('d.m.Y H:i:s', strtotime($rows->create_date));
                            }
                            
                            if ($rows->end_date == '0000-00-00 00:00:00') {
                                $end_date = '';
                            }else{
                                $end_date = date('d.m.Y H:i:s', strtotime($rows->end_date));
                            }
                            
                            echo '<tr>'
                            . '<td>' . $count++ . '</td>'
                            . '<td>';
                            if ($rows->task_name !== "") {
                                echo anchor("records/viewTask/" . $rows->id, $rows->task_name);
                            } else {
                                echo anchor("records/viewTask/" . $rows->id, substr($rows->task_description, 0, 80) . '...');
                            }
                            echo '</td>';
                            echo '<td>' .$rows->initiator . '</td>';
                            echo '<td>' . $crmUser->getUserById($rows->assigned) . '</td>'
                            . '<td>' . $create_date . '</td>'
                            . '<td>' . $end_date . '</td>'
                            . '<td>' . anchor("records/deleteTask/" . $rows->id, "Удалить", "class='btn btn-mini btn-danger pull-left'") . '</td>'
                            . '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div><!--/span-->
