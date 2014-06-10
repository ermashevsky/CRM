<body>
    <div class="container-fluid">
        <div class="row">
            <div class="span10">
                <div class="row-fluid">
                    <div class="span12">

<!--                        <form class="form-horizontal" action="tasks/updateTaskParameters" method="POST">-->
                            <?php 
                            echo form_open('tasks/updateTaskParameters', 'class="form-horizontal"');
                            ?>
                            <fieldset>
                                <?php
                                foreach($task as $row):
                                ?>
                                <!-- Form Name -->
                                <legend>Редактирование задачи</legend>
                                <input type="hidden" name="id" id="id" value="<?php echo $row->id;?>">
                                 <!-- Text input-->
                                <div class="control-group">
                                    <label class="control-label" for="status">Статус</label>
                                    <div class="controls">
                                        <input id="status" name="status" type="text" placeholder="" class="input-medium" readonly="" value="<?php echo $row->status; ?>">
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
                                            foreach ($users as $value) {
                                                echo "<option value='" . $value->id . "'";
                                                        if($row->assigned === $value->id){
                                                            echo "selected";
                                                        }
                                                echo ">" . $value->first_name . " " . $value->last_name . "</option>";
                                            }
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
                                        <input id="task_name" name="task_name" type="text" placeholder="" class="input-xxlarge" value="<?php echo $row->task_name; ?>">

                                    </div>
                                </div>
                                
                                <!-- Textarea -->
                                <div class="control-group">
                                    <label class="control-label" for="task_description">Описание</label>
                                    <div class="controls">                     
                                        <textarea id="task_description" name="task_description" class="input-xxlarge" cols="10" rows="10"><?php echo $row->task_description; ?></textarea>
                                    </div>
                                </div>
                                <?php
                                 endforeach;
                                ?>
                                <!-- Multiple Checkboxes -->
                                <div class="control-group">

                                    <div class="controls">
                                        <label class="checkbox" for="checkboxes-reminder">
                                            <?php
                                                if($row->reminder_date !== NULL){
                                            ?>
                                            <input type="checkbox" name="checkboxes-reminder" id="checkboxes-reminder" checked="true">
                                            Напомнить
                                        </label>
                                    </div>
                                </div>
                                <div id="reminder_block" style="display:block;">
                                 <!-- Text input-->
                                <div class="control-group">
                                    <label class="control-label" for="reminder_date">Дата/время</label>
                                    <div class="controls">
                                        <input id="reminder_date" name="reminder_date" type="text" class="input-medium" value="<?php echo date('d.m.Y H:i:s',strtotime($row->reminder_date)); ?>">

                                    </div>
                                </div>
                                            <?php
                                                }else{
                                                    ?>
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
                                 <?php
                                                }
                                            ?>
                                            
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
                                <!-- Button (Double) -->
                                <div class="control-group">
                                    <label class="control-label" for="button1id"></label>
                                    <div class="controls">
                                        <button id="button1id" name="button1id" class="btn btn-success">Сохранить</button>
                                        <button id="button2id" name="button2id" class="btn btn-danger">Отменить</button>
                                    </div>
                                </div>

                            </fieldset>
                        </form>

                    </div><!--/span-->
                </div><!--/row-->
            </div><!--/row-->


