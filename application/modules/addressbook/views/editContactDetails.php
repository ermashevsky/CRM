<body>
    <div class="container-fluid">
        <div class="row">
            <div class="span10">
                <?php
                $groupButton = new AddressBook();
                $groupButton->createGroupButton();
                foreach ($contactDetail as $rows):
                ?>
                <div class="row-fluid">
                    <div class="span12">
                        <?php
                        echo validation_errors();
                        $attributes = array('class' => 'form-horizontal', 'id' => 'contactData');
                        echo form_open('addressbook/updateContactDetails', $attributes);
                        echo form_hidden('contact_id',$rows->id);
                        ?>
                            <fieldset>

                                <!-- Form Name -->
                                <legend>Редактирование данных контакта</legend>
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
                                            'value' => $rows->contact_name,
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
                                            'value' => $rows->job_position,
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
                                            'value' => $rows->private_phone_number,
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
                                            'value' => $rows->mobile_number,
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
                                            'value' => $rows->email,
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
                                            'value' => $rows->address,
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
                                            'value' => $rows->birthday,
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
                                            'value' => $rows->comment,
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
                                        <?php 
                                        if($rows->organization_id === NULL):
                                        ?>
                                        <input type="text" name="organization_note" class="select2field" id="select2field" />
                                        <?php 
                                        endif;
                                        ?>
                                        <p>
                                        <table class="table table-striped table-bordered table-condensed" id="contact_list">
                                                <thead>
                                                <th>Наименование</th>
                                                <th>Адрес организации</th>
                                                <th>Телефон</th>
                                                <th>Email</th>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $organizationData = new AddressBook();
                                                    $dataOrganization = $organizationData->getOrganizationDetails($rows->organization_id);
                                                    
                                                    foreach($dataOrganization as $organization_value):
                                                        echo '<tr>'
                                                            . '<td>'.anchor("addressbook/viewOrganizationDetails/".$organization_value->id, $organization_value->organization_name).'</td>'
                                                            . '<td>'.$organization_value->address.'</td>'
                                                            . '<td>'.$organization_value->phone_number.'</td>'
                                                            . '<td>'.$organization_value->email.'</td>'
                                                            . '<input type="hidden" name="organizationid" value="'.$organization_value->id.'" />'
                                                        . '</tr>';
                                                    endforeach;
                                                    ?>
                                                </tbody>
                                            </table>
                                    </p>
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
                                            'class' => 'btn btn-success btn-small',
                                            'value' => 'Сохранить',
                                            'type' => 'submit'
                                        );
                                        $reset_btn = array(
                                            'name' => 'reset',
                                            'id' => 'reset',
                                            'class' => 'btn btn-danger btn-small',
                                            'value' => 'Отменить',
                                            'type' => 'reset'
                                        );
                                        echo form_submit($submit_btn);
                                        echo form_reset($reset_btn);
                                    endforeach;
                                        ?>
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                    </div><!--/span-->
                </div><!--/row-->
            </div><!--/row-->


