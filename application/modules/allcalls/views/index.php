<body>
    <div class="container-fluid">
        <div class="row">
            <div class="span10">
                <h3 style="color:#08c;">История звонков</h3>
                <div id="thanks">
                    <p class="myParagraphBlock">
                        <div class="btn-group">
                        <button class="btn btn-success btn-small"><i class="icon-download-alt"> </i> Сохранить в ...</button>
                        <button class="btn btn-success btn-small dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#" id="saveXLS"><i class="icon-list-alt"> </i> формат XLS</a></li>
                            <li><a href="#" id="saveCSV"><i class="icon-list"> </i> формат CSV</a></li>
                        </ul>
                    </div>
                        <!--<button class="btn btn-success btn-small" id="save2XLS"><i class="icon-download-alt"> </i> Сохранить в XLS</button>-->
                   
                        <a data-toggle="modal" href="#form-content" id="filterDataButton" class="btn btn-info btn-small">
                            <i class="icon-filter"> </i> Фильтр звонков
                        </a>
                    <p>
                </div>
                <!-- model content -->    
                <div id="form-content" class="modal hide fade in" style="display: none; ">
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">×</a>
                        <h4>Фильтр звонков</h4>
                    </div>

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
                            <div class="control-group" id="duration_block2">
                                <label class="control-label" for="duration">Продолжительность:</label>
                                <div class="controls">
                                    <select name="select_duration_value">
                                        <option id="less" name="less" value="less"><=</option>
                                        <option id="more" name="more" value="more">>=</option>
                                    </select>
                                    <input type="text" class="input-mini" name="duration_minute" id="duration_minute" style="width:15px;"/>
                                    <input type="text" class="input-mini" name="duration_second" id="duration_second" style="width:15px;"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="type_call">Статус звонка:</label>
                                <div class="controls">
                                    <select name="status_call" id="status_call">
                                        <option value="all_status">Все</option>
                                        <option value="ANSWERED">Ответили</option>
                                        <option value="NO ANSWER">Пропущенный</option>
                                        <option value="BUSY">Занято</option>
                                        <option value="CALL INTERCEPTION">Перехваченный</option>
                                        <option value="ANSWER_BY">Переведенный</option>
                                        <option value="FAILED">Ошибка</option>
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
