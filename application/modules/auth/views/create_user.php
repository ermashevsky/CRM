<body>
    <div class="container-fluid">
        <div class="row">

            <div class="span10">

                <div class="row-fluid">
                    <div class="span12">
<h3>Добавление пользователя</h3>
	
	<div id="infoMessage"><?php echo $message;?></div>
	
    <?php echo form_open("auth/create_user");?>
      <p>Логин:<br />
      <?php echo form_input($login);?>
      </p>
        
      <p>Фамилия:<br />
      <?php echo form_input($last_name);?>
      </p>
      
      <p>Имя:<br />
      <?php echo form_input($first_name);?>
      </p>
      
      <p>Должность:<br />
      <?php echo form_input($work_position);?>
      </p>
      
      <p>Подразделение:<br />
      <?php echo form_input($company);?>
      </p>
      
      <p>Email:<br />
      <?php echo form_input($email);?>
      </p>
      
      <p>Телефон (внутр.):<br />
      <?php echo form_input($phone1);?>
      </p>
      
      <p>Группа (Роль):<br />
      <?php echo form_input($group);?>
      </p>
      
      <p>Пароль:<br />
      <?php echo form_input($password);?>
      </p>
      
      <p>Повтор пароля:<br />
      <?php echo form_input($password_confirm);?>
      </p>
      <p><?php echo form_submit('submit', 'Добавить пользователя', 'class="btn btn-primary btn-small"');?></p>

      
    <?php echo form_close();?>
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