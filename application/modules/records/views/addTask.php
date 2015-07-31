<body>
    <div class="container-fluid">
        <div class="row">
            <div class="span10">
                <div class="row-fluid">
                    <div class="span12">
                        <?php
                        echo form_open('records/addTask', 'class="form-horizontal"');
                        ?>
                        <fieldset>
                            <!-- Form Name -->
                            <legend>Новая запись</legend>
                            <div id="phone_num_block" style="display: none;">
                                <div class="control-group">
                                    <label class="control-label" for="phone_num">Номер телефона</label>
                                    <div class="controls">
                                        <input id="phone_num" name="phone_num" type="text" placeholder="" class="input-xxlarge" value="">

                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="contact_name">Контакт</label>
                                    <div class="controls">
                                        <input id="contact_name" name="contact_name" type="text" placeholder="" class="input-xxlarge" value="">

                                    </div>
                                </div>
                            </div>
                            
                            <input id="source_records" name="source_records" type="hidden" value="Модуль записи">
                            
                            <div class="control-group">
                                <label class="control-label" for="task_name">Заголовок</label>
                                <div class="controls">
                                    <input id="task_name" name="task_name" type="text" placeholder="" class="input-xxlarge" value="">

                                </div>
                            </div>

                            <!-- Textarea -->
                            <div class="control-group">
                                <label class="control-label" for="task_description">Описание</label>
                                <div class="controls">                     
                                    <textarea id="task_description" name="task_description" class="input-xxlarge" cols="10" rows="10"></textarea>
                                </div>
                            </div>


                            <div class="control-group">
                                <label class="control-label" for="selectAssigned">Получатель</label>
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

                            <!-- Multiple Checkboxes -->

                            <!-- Text input-->
                            <div class="control-group">
                                <label class="control-label" for="create_date">Дата начала</label>
                                <div class="controls">
                                    <input name="create_date" type="text" id="create_date"  class="input-medium" value="">

                                </div>
                            </div>
                            <!-- Text input-->
                            <div class="control-group">
                                <label class="control-label" for="end_date">Дата окончания</label>
                                <div class="controls">
                                    <input name="end_date" type="text" id="end_date" class="input-medium" value="">

                                </div>
                            </div>

                            <div class="control-group" id="checkboxes_report_block" style="display: none;">
                                <div class="controls">
                                    <label class="checkbox" for="checkboxes-report">
                                        <input type="checkbox" name="checkboxes-report" id="checkboxes-report" value="1">
                                        Отчёт
                                    </label>
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


