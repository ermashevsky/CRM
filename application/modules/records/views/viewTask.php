<body>
    <div class="container-fluid">
        <div class="row">
            <div class="span10">
                <div class="row-fluid">
                    <div class="span12">
                        <?php
                        foreach ($task as $row):
                            ?>
                            <h3>
                                <?php
                                if ($row->task_name !== "") {
                                    echo $row->task_name;
                                } else {
                                    echo substr($row->task_description, 0, 80) . '...';
                                }

                                echo " ";
                                ?>
                            </h3>

                            <table class="table table-striped table-bordered table-condensed" summary="" id="contactList"  style="border-collapse:collapse;">
                                <tbody>
                                    <tr>
                                        <th scope="row">Создана</th>
                                        <td>
                                            <?php
                                            if ($row->create_date == '0000-00-00 00:00:00') {
                                                echo '';
                                            } else {
                                                echo date('d.m.Y H:i:s', strtotime($row->create_date));
                                            }
                                            
                                            ?>
                                        </td>
                                        <th scope="row">Выполнена</th>
                                        <td>
                                            <?php
//                                            if (!empty($row->end_date)):
//                                                echo date('d.m.Y H:i:s', strtotime($row->end_date));
//                                            endif;

                                            if ($row->end_date == '0000-00-00 00:00:00') {
                                                echo '';
                                            } else {
                                                echo date('d.m.Y H:i:s', strtotime($row->end_date));
                                            }
                                            ?>
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <th scope="row">Инициатор</th>
                                        <td>
                                            <?php
                                            echo $row->initiator;
                                            ?>
                                        </td>
                                         <th scope="row">Назначена</th>
                                        <td>
                                            <?php
                                            $crmUser = new Records();
                                            if ($row->assigned !== ''):
                                                echo $crmUser->getUserById($row->assigned);
                                            endif;
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Назначена</th>
                                        <td>
                                            
                                        </td>
                                        <th scope="row">Категория</th>
                                        <td>
                                            <?php
                                            echo $row->category;
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr/>
                            <h4>Описание</h4>

                            <?php
                            echo $row->task_description;
                            ?>
                            <hr/>
                            <?php
                            echo anchor("records/deleteTask/" . $row->id, "Удалить", "class='btn btn-mini btn-danger pull-right'");
                            echo anchor("records/editTask/" . $row->id, "Редактировать", "class='btn btn-mini btn-success pull-right'");

                        endforeach;
                        ?>
                        <br/>
                    </div><!--/span-->
                </div><!--/row-->
            </div><!--/row-->


