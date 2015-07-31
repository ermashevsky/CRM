<body>
    <div class="container-fluid">
        <div class="row">
            <div class="span10">
                <?php
                $groupButton = new AddressBook();
                $groupButton->createGroupButton();
                ?>
                <div class="row-fluid">
                    <div class="span12">
                        <?php
                        echo validation_errors();
                        $attributes = array('class' => 'form-horizontal', 'id' => 'contactData');
                        echo form_open('addressbook/insertNewContactRow', $attributes); ?>
                            <fieldset>

                                <!-- Form Name -->
                                <legend>Новый контакт</legend>
                                <!-- Text input-->
                                <div class="control-group">
                                    <?php
                                        $attributes_label = array(
                                           'class' => 'control-label',
                                       );
                                       echo form_label('ФИО', 'contact_name', $attributes_label);
                                   ?>
                                    <div class="controls">
                                        <?php
                                           $data = array(
                                            'name' => 'contact_name',
                                            'id' => 'contact_name',
                                            'placeholder' => '',
                                            'value' => $this->form_validation->set_value('contact_name'),
                                            'class' => 'input-xlarge'
                                        );

                                        echo form_input($data);
                                        ?>
                                        
                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="control-group">
                                    <?php
                                       echo form_label('Должность', 'job_position', $attributes_label);
                                   ?>
                                    <div class="controls">
                                        <?php
                                           $job_position = array(
                                            'name' => 'job_position',
                                            'id' => 'job_position',
                                            'value' => $this->form_validation->set_value('job_position'),
                                            'placeholder' => '',
                                            'class' => 'input-xlarge'
                                        );

                                        echo form_input($job_position);
                                        ?>
                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="control-group">
                                    <?php
                                       echo form_label('Телефон (основной)', 'private_phone_number', $attributes_label);
                                   ?>
                                    <div class="controls">
                                        <?php
                                           $private_phone_number = array(
                                            'name' => 'private_phone_number',
                                            'id' => 'private_phone_number',
                                            'value' => $this->form_validation->set_value('private_phone_number'),
                                            'placeholder' => '',
                                            'class' => 'input-xlarge'
                                        );

                                        echo form_input($private_phone_number);
                                        ?>
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="control-group">
                                    <?php
                                       echo form_label('Мобильный', 'mobile_number', $attributes_label);
                                   ?>
                                    <div class="controls">
                                        <?php
                                           $mobile_number = array(
                                            'name' => 'mobile_number',
                                            'id' => 'mobile_number',
                                            'value' => $this->form_validation->set_value('mobile_number'),
                                            'placeholder' => '',
                                            'class' => 'input-xlarge'
                                        );

                                        echo form_input($mobile_number);
                                        ?>
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="control-group">
                                    <?php
                                       echo form_label('Email', 'email', $attributes_label);
                                   ?>
                                    <div class="controls">
                                        <?php
                                           $email = array(
                                            'name' => 'email',
                                            'id' => 'email',
                                            'value' => $this->form_validation->set_value('email'),
                                            'placeholder' => '',
                                            'class' => 'input-xlarge'
                                        );

                                        echo form_input($email);
                                        ?>
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="control-group">
                                    <?php

                                       echo form_label('Адрес', 'address', $attributes_label);
                                   ?>
                                    <div class="controls">
                                        <?php
                                           $address = array(
                                            'name' => 'address',
                                            'id' => 'address',
                                            'value' => $this->form_validation->set_value('address'),
                                            'placeholder' => '',
                                            'class' => 'input-xlarge'
                                        );

                                        echo form_input($address);
                                        ?> 
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="control-group">
                                    <?php

                                       echo form_label('Дата рождения', 'birthday', $attributes_label);
                                   ?>
                                    <div class="controls">
                                        <?php
                                           $birthday = array(
                                            'name' => 'birthday',
                                            'id' => 'birthday',
                                            'value' => $this->form_validation->set_value('birthday'),
                                            'placeholder' => '',
                                            'class' => 'input-xlarge'
                                        );

                                        echo form_input($birthday);
                                        ?>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <?php

                                       echo form_label('Дополнительно', 'comment', $attributes_label);
                                   ?>
                                    <div class="controls">
                                        <?php
                                           $comment = array(
                                            'name' => 'comment',
                                            'id' => 'comment',
                                            'value' => $this->form_validation->set_value('comment'),
                                            'placeholder' => '',
                                            'class' => 'input-xlarge'
                                        );

                                        echo form_textarea($comment);
                                        ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="selectOrganization">Организация</label>
                                    <div class="controls">
                                        <input type="text" name="organization_id" class="select2field" id="select2field"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                     <?php
                                        $label = array(
                                           'class' => 'control-label',
                                       );
                                       echo form_label('Приватный контакт', 'private', $label);
                                   ?>
                                    <div class="controls">
                                        <?php
                                            echo form_checkbox('private', 'accept', FALSE);
                                        ?>
                                    </div>
                                </div>
                                <!-- Button (Double) -->
                                <div class="control-group">
                                    <label class="control-label" for="submit"></label>
                                    <div class="controls">
                                        <?php
                                        $submit_btn = array(
                                            'name' => 'submit',
                                            'id' => 'submit',
                                            'class' => 'btn btn-success',
                                            'value' => 'Сохранить',
                                            'type' => 'submit'
                                        );
                                        $reset_btn = array(
                                            'name' => 'reset',
                                            'id' => 'reset',
                                            'class' => 'btn btn-danger',
                                            'value' => 'Отменить',
                                            'type' => 'reset'
                                        );
                                        echo form_submit($submit_btn);
                                        echo form_reset($reset_btn);
                                        ?>
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                    </div><!--/span-->
                </div><!--/row-->
            </div><!--/row-->


