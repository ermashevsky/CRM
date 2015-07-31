<body>
    <div class="container-fluid">
        <div class="row">
            <div class="span10">
                <div class="row-fluid">
                    <div class="span12">

<!--                        <form class="form-horizontal" action="records/updateTaskParameters" method="POST">-->
                            <?php 
                            echo form_open('records/updateTaskParameters', 'class="form-horizontal"');
                            ?>
                            <fieldset>
                                <?php
                                foreach($task as $row):
                                ?>
                                <!-- Form Name -->
                                <legend>Редактирование записи</legend>
                                <input type="hidden" name="id" id="id" value="<?php echo $row->id;?>">

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


