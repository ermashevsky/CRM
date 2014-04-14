<body>
    <div class="container-fluid">
        <div class="row">

            <div class="span10">
                <div class="hero-unit">
                    <h1>Hello, world!</h1>
                    <p>This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
                    <p><a class="btn btn-primary btn-large">Learn more »</a></p>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <h3>Пользователи</h3>
                        <div id="infoMessage"><?php echo $message; ?></div>
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-condensed" id="user_list">
                            <thead>
                                <tr>
                                    <th>Фамилия</th>
                                    <th>Имя</th>
                                    <th>Email</th>
                                    <th>Телефон</th>
                                    <th>Роль (Группа)</th>
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo $user->last_name; ?></td>
                                        <td><?php echo $user->first_name; ?></td>
                                        <td><?php echo $user->email; ?></td>
                                        <td><?php echo $user->email; ?></td>
                                        <td>
                                            <?php foreach ($user->groups as $group): ?>
                                                <?php echo $group->name; ?><br />
                                            <?php endforeach ?>
                                        </td>
                                        <td><?php echo ($user->active) ? anchor("auth/deactivate/" . $user->id, 'Active') : anchor("auth/activate/" . $user->id, 'Inactive'); ?></td>
                                        <td>Редактировать | Удалить</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <p><a class="btn btn-primary btn-small" href="#" data-toggle="modal" data-target=".bs-example-modal-sm">Добавить пользователя</a></p>
                    </div><!--/span-->
                </div><!--/row-->
                <div class="row-fluid">
                    <div class="span4">
                        <h2>Heading</h2>
                        <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                        <p><a class="btn" href="#">View details »</a></p>
                    </div><!--/span-->
                    <div class="span4">
                        <h2>Heading</h2>
                        <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                        <p><a class="btn" href="#">View details »</a></p>
                    </div><!--/span-->
                    <div class="span4">
                        <h2>Heading</h2>
                        <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                        <p><a class="btn" href="#">View details »</a></p>
                    </div><!--/span-->
                </div><!--/row-->
            </div><!--/span-->
            
            <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
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

<!-- File Button --> 
<div class="control-group">
  <label class="control-label" for="photo_upload">Фотография</label>
  <div class="controls">
    <input id="photo_upload" name="photo_upload" class="input-file" type="file">
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