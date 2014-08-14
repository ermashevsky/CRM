<body>
    <div class="container-fluid">
        <div class="row">

            <div class="span16">
                <div class="row-fluid">
                    
                        <h3 style="color:#08c;">Пользователи</h3>
                        <div id="infoMessage" class="label label-info"><?php echo $message; ?></div>
                        <table class='table table-striped table-bordered table-condensed' cellspacing="0" width="100%" id="user_list">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Фамилия</th>
                                    <th>Имя</th>
                                    <th>Должность</th>
                                    <th>Подразделение</th>
                                    <th>Email</th>
                                    <th>Телефон</th>
                                    <th>Роль (Группа)</th>
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $n = 1;
                                foreach ($users as $user):
                                if($user->username !== 'su'){
                                ?>
                                
                                    <tr>
                                        <td><?php echo $n++; ?></td>
                                        <td><?php echo $user->last_name; ?></td>
                                        <td><?php echo $user->first_name; ?></td>
                                        <td><?php echo $user->work_position; ?></td>
                                        <td><?php echo $user->company; ?></td>
                                        <td><?php echo $user->email; ?></td>
                                        <td><?php echo $user->phone; ?></td>
                                        <td>
                                            <?php foreach ($user->groups as $group): ?>
                                                <?php echo $group->name; ?><br />
                                            <?php endforeach ?>
                                        </td>
                                        <td><?php echo ($user->active) ? anchor("auth/deactivate/" . $user->id, 'Активен', array('class' => 'btn btn-info btn-mini')) : anchor("auth/activate/" . $user->id, 'Заблокирован', array('class' => 'btn btn-danger btn-mini')); ?></td>
                                        <td><?php echo anchor("auth/edit_user/" . $user->id, 'Редактировать', array('class' => 'btn btn-success btn-mini'));?> | <?php echo anchor("auth/delete_user/" . $user->id, 'Удалить', array('class' => 'btn btn-danger btn-mini'));?></td>
                                    </tr>
                                <?php
                                }
                                endforeach; ?>
                            </tbody>
                        </table>
                        <p><a class="btn btn-info btn-small" href="#" data-toggle="modal" data-target=".bs-example-modal-sm">Добавить пользователя</a></p>
                    
                </div><!--/row-->
            </div><!--/span-->
            
            <div class="modal hide fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Новый пользователь</h4>
      </div>
      <div class="modal-body">
   <form class="form-horizontal" id="create_user_form">
<fieldset>

<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="login">Логин</label>
  <div class="controls">
    <input id="login" name="login" type="text" placeholder="Введите логин" class="input-xlarge" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="last_name">Фамилия</label>
  <div class="controls">
    <input id="last_name" name="last_name" type="text" placeholder="Введите фамилию" class="input-xlarge" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="first_name">Имя</label>
  <div class="controls">
    <input id="first_name" name="first_name" type="text" placeholder="Введите имя" class="input-xlarge" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="work_position">Должность</label>
  <div class="controls">
    <input id="work_position" name="work_position" type="text" placeholder="Введите должность" class="input-xlarge" required="">
    
  </div>
</div>

<!-- Select Basic -->
<div class="control-group">
  <label class="control-label" for="work_dept">Подразделение</label>
  <div class="controls">
    <select id="work_dept" name="work_dept" class="input-xlarge">
      <option>Администрация</option>
      <option>Бухгалтерия</option>
      <option>Абонентский отдел</option>
      <option>Коммерческий отдел</option>
      <option>Технический отдел</option>
    </select>
  </div>
</div>

<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="email">Email</label>
  <div class="controls">
    <input id="email" name="email" type="text" placeholder="Введите email" class="input-xlarge">
    
  </div>
</div>

<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="phone">Телефон (внутр.)</label>
  <div class="controls">
    <input id="phone" name="phone" type="text" placeholder="Введите внутренний телефон" class="input-xlarge" required="">
    
  </div>
</div>

<!-- Select Basic -->
<div class="control-group">
  <label class="control-label" for="group">Группа (Роль)</label>
  <div class="controls">
    <select id="group" name="group" class="input-xlarge">
      <option>Администратор</option>
      <option>Менеджер</option>
    </select>
  </div>
</div>

<!-- Password input-->
<div class="control-group">
  <label class="control-label" for="password">Пароль</label>
  <div class="controls">
    <input id="password" name="password" type="password" placeholder="Введите пароль" class="input-xlarge" required="">
    
  </div>
</div>

<!-- Password input-->
<div class="control-group">
  <label class="control-label" for="password_confirm">Повтор пароля</label>
  <div class="controls">
    <input id="password_confirm" name="password_confirm" type="password" placeholder="Повторите пароль" class="input-xlarge" required="">
    
  </div>
</div>
</fieldset>
</form>
     
      </div>
      <div class="modal-footer">
        
          <button type="button" class="btn btn-primary btn-small" onclick="create_user();return false;">Сохранить</button>
        <button type="button" class="btn btn-default btn-small" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>           
