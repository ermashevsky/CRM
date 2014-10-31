<body>
    <div class="container-fluid">
        <div class="row">
            <div class="span10">
                <h3 style="color:#08c;">История звонков</h3>
<div id="thanks"><p><a data-toggle="modal" href="#form-content" class="btn btn-info btn-mini">Фильтр звонков</a></p></div>
    <!-- model content -->    
    <div id="form-content" class="modal hide fade in" style="display: none; ">
            <div class="modal-header">
                  <a class="close" data-dismiss="modal">×</a>
                  <h4>Фильтр звонков</h4>
            </div>
        <?php
        echo $_SERVER['HTTP_HOST'];
        ?>
        <div>
                
                <form class="form-horizontal" id="form_filter_call">
                        <div class="control-group">
                        <label class="control-label" for="date_time" >Дата/Время:</label>
                        <div class="controls inline">
                        <input type="text" class="input-medium" name="date_time" id="date_time"/> - <input type="text" class="input-medium" name="date_time2" id="date_time2"/>
                        </div>
                        </div>
                        <div class="control-group">
                        <label class="control-label" for="type_call">Тип звонка:</label>
                        <div class="controls">
                        <select name="type_call" id="type_call">
                            <option value="allcall">Все звонки</option>
                            <option value="incall">Входящий</option>
                            <option value="outcall">Исходящий</option>
                        </select>
                        </div>
                        </div>
                        <div class="control-group" id="src_block">
                        <label class="control-label" for="src">Вызывающий:</label>
                        <div class="controls">
                        <input type="text" class="input-medium" name="src" id="src"/>
                        </div>
                        </div>
                        <div class="control-group" id="dst_block">
                        <label class="control-label" for="dst">Принимающий:</label>
                        <div class="controls">
                        <input type="text" class="input-medium" name="dst" id="dst"/>
                        </div>
                        </div>
                        <div class="control-group" id="number_block">
                        <label class="control-label" for="phone_number">Номер1:</label>
                        <div class="controls">
                        <input type="text" class="input-medium" name="phone_number" id="phone_number"/>
                        </div>
                        </div>
                        <div class="control-group" id="number_block2">
                        <label class="control-label" for="phone_number2">Номер2:</label>
                        <div class="controls">
                        <input type="text" class="input-medium" name="phone_number2" id="phone_number2"/>
                        </div>
                        </div>
                        <div class="control-group">
                        <label class="control-label" for="type_call">Статус звонка:</label>
                        <div class="controls">
                        <select name="status_call" id="status_call">
                            <option value="all_status">Все</option>
                            <option value="ANSWERED">Answered</option>
                            <option value="NO ANSWER">No Answer</option>
                            <option value="BUSY">Busy</option>
                        </select>
                        </div>
                        </div>
                    </form>
 </div>
         <div class="modal-footer">
             <button class="btn btn-success btn-mini" id="submit">Поиск</button>
             <a href="#" class="btn btn-mini" data-dismiss="modal">Отменить</a>
          </div>
    </div>
    <div id="table_all_calls">
                    <?php
                    $calls = new Allcalls();
                    $calls->getAllCalls();
                    ?>  
                </div>

            </div><!--/span-->
            