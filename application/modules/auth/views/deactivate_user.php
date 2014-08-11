<body>
    <div class="container-fluid">
        <div class="row">

            <div class="span16">
                <div class="row-fluid">
	<h3>Блокирование пользователя</h3>
    <div class="pageTitleBorder"></div>
	<p>Вы действительно хотите заблокировать пользователя '<b><?php echo $user->username; ?></b>' ?</p>
	
    <?php echo form_open("auth/deactivate/".$user->id,'class="form-inline"');?>
    	
      <p>
      	<label for="confirm">Да:</label>
		<input type="radio" name="confirm" value="yes" checked="checked" />
      	<label for="confirm">Нет:</label>
		<input type="radio" name="confirm" value="no" />
      </p>
      
      <?php echo form_hidden($csrf); ?>
      <?php echo form_hidden(array('id'=>$user->id)); ?>
      
      <p><?php
      $attributes = array('class' => 'class="btn btn-info btn-small"');
      echo form_submit('submit', 'Сохранить', $attributes['class']);?></p>

    <?php echo form_close();?>

</div>
                    </div>
                                        </div>
                                    </div>