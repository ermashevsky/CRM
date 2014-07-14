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
                                if($row->task_name!==""){
                                   echo $row->task_name;
                                }else{
                                   echo substr($row->task_description, 0, 80).'...';
                                }
                         
                                echo " ";
                                if(is_null($row->end_date)){
                                    echo anchor("tasks/closeTask/" . $row->id, "Закрыть задачу", "class='btn btn-mini btn-info'");
                                }else{
                                    echo anchor("tasks/reopenTask/" . $row->id, "Открыть задачу", "class='btn btn-mini btn-info'");
                                }
                                ?>
                            </h3>
                            <h6>Добавил(а): Ермашевский Денис</h6>
                            <?php if($row->reminder_date !== NULL){?>
                            <span class="label label-info">Напомнить: <?php echo date('d.m.Y H:i:s',strtotime($row->reminder_date)); ?></span>
                            <?php
                            }else{
                                
                            }
                            ?>
                            <table class="table table-striped table-bordered table-condensed" summary="" id="contactList"  style="border-collapse:collapse;");
                                   >
                                <tbody>
                                    <tr>
                                        <th scope="row">Статус</th>
                                        <td>
                                            <?php
                                            echo $row->status;
                                            ?>
                                        </td>
                                        <th scope="row">Создана</th>
                                        <td>
                                            <?php
                                            echo date('d.m.Y H:i:s',strtotime($row->create_date));
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Приоритет</th>
                                        <td>
                                            <?php
                                            echo $row->priority;
                                            ?>
                                        </td>
                                        <th scope="row">Выполнена</th>
                                        <td>
                                            <?php
                                            if(!empty($row->end_date)):
                                                echo date('d.m.Y H:i:s',strtotime($row->end_date));
                                            endif;
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Назначена</th>
                                        <td>
                                            <?php
                                            $crmUser = new Tasks();
                                            if($row->assigned!==''):
                                            echo $crmUser ->getUserById($row->assigned);
                                            endif;
                                            ?>
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
                            echo anchor("tasks/deleteTask/" . $row->id, "Удалить", "class='btn btn-mini btn-danger pull-right'");
                            echo anchor("tasks/editTask/" . $row->id, "Редактировать", "class='btn btn-mini btn-success pull-right'");

                        endforeach;
                        ?>
                        <br/>
                    </div><!--/span-->
                </div><!--/row-->
            </div><!--/row-->


