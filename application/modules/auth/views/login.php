<div class="container block" style="height: 200px;">
    <div class="row">
        <div class="span4 offset4 well">
            <legend>Авторизация пользователя</legend>
            <?php if ($message) {
                ?>
                <div id="infoMessage"><div class="alert alert-error">
                        <a class="close" data-dismiss="alert" href="#">×</a><?php echo $message; ?>
                    </div></div>
            <?php } ?>
            <?php echo form_open("auth/login"); ?>

            <p>
                 <div class="input-prepend input-block-level">
      <span class="add-on"><i class="icon-user"></i></span>
                <?php
                $attributes_login = array(
                    'name' => 'identity',
                    'id' => 'identity',
                    'class' => 'span4',
                    'placeholder' => 'Логин'
                );
                echo form_input($attributes_login);
                ?>
        </div>
            </p>
            <p>
                 <div class="input-prepend input-block-level">
      <span class="add-on"><i class="icon-eye-close"></i></span>
                <?php
                $attributes_password = array(
                    'name' => 'password',
                    'id' => 'password',
                    'class' => 'span4',
                    'type' => 'password',
                    'placeholder' => 'Пароль'
                );
                echo form_input($attributes_password);
                ?>
                 </div>
            </p>
            <p>
                <label for="remember" class="checkbox">Запомнить меня:
<?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?>
                </label>
            </p>


            <p><?php
                $attributes_submit = array(
                    'name' => 'submit',
                    'id' => 'submit',
                    'class' => 'btn btn-info btn-block',
                    'value' => 'Войти'
                );
                echo form_submit($attributes_submit);
                ?></p>

<?php echo form_close(); ?>
        </div>
    </div>
</div>
