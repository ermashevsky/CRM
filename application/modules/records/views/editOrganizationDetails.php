<body>
    <div class="container-fluid">
        <div class="row">
            <div class="span10">
                <?php
                $groupButton = new AddressBook();
                $groupButton->createGroupButton();
                foreach ($organizationDetail as $rows):
                    ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <?php
                            echo validation_errors();
                            $attributes = array('class' => 'form-horizontal', 'id' => 'editOrganizationData');
                            echo form_open('addressbook/updateOrganizationData', $attributes);
                            echo form_hidden('organization_id',$rows->id);
                            
                            ?>
                            <fieldset>

                                <!-- Form Name -->
                                <legend>Редактирование данных организации</legend>
                                <!-- Text input-->
                                <div class="control-group">
                                    <?php
                                    $attributes_label = array(
                                        'class' => 'control-label',
                                    );
                                    echo form_label('Наименование', 'organization_name', $attributes_label);
                                    ?>
                                    <div class="controls">
                                        <?php
                                        $organization_name = array(
                                            'name' => 'organization_name',
                                            'id' => 'organization_name',
                                            'placeholder' => '',
                                            'value' => $rows->organization_name,
                                            'class' => 'input-xlarge'
                                        );

                                        echo form_input($organization_name);
                                        ?>
                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="control-group">
                                    <?php
                                    echo form_label('Адрес организации', 'address', $attributes_label);
                                    ?>
                                    <div class="controls">
                                        <?php
                                        $address = array(
                                            'name' => 'address',
                                            'id' => 'address',
                                            'placeholder' => '',
                                            'value' => $rows->address,
                                            'class' => 'input-xlarge'
                                        );

                                        echo form_input($address);
                                        ?>
                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="control-group">
                                    <?php
                                    echo form_label('Телефон (основной)', 'phone_number', $attributes_label);
                                    ?>
                                    <div class="controls">
                                        <?php
                                        $phone_number = array(
                                            'name' => 'phone_number',
                                            'id' => 'phone_number',
                                            'placeholder' => '',
                                            'value' => $rows->phone_number,
                                            'class' => 'input-xlarge'
                                        );

                                        echo form_input($phone_number);
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
                                            'placeholder' => '',
                                            'value' => $rows->email,
                                            'class' => 'input-xlarge'
                                        );

                                        echo form_input($email);
                                        ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <?php
                                    echo form_label('Комментарий', 'comment', $attributes_label);
                                    ?>
                                    <div class="controls">
                                        <?php
                                        $comment = array(
                                            'name' => 'comment',
                                            'id' => 'comment',
                                            'placeholder' => '',
                                            'value' => $rows->comment,
                                            'class' => 'input-xlarge'
                                        );

                                        echo form_textarea($comment);
                                        ?>
                                    </div>
                                </div>
                                <div class="accordion" id="accordion2">
                                    <div class="accordion-group">
                                        <div class="accordion-heading">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                                                Дополнительная информация ...
                                            </a>
                                        </div>
                                        <div id="collapseOne" class="accordion-body collapse">
                                            <div class="accordion-inner">
                                                <!-- Text input-->
                                                <div class="control-group">
                                                    <?php
                                                    echo form_label('Краткое наименование', 'short_organization_name', $attributes_label);
                                                    ?>
                                                    <div class="controls">
                                                        <?php
                                                        $short_organization_name = array(
                                                            'name' => 'short_organization_name',
                                                            'id' => 'short_organization_name',
                                                            'placeholder' => '',
                                                            'value' => $rows->short_organization_name,
                                                            'class' => 'input-xlarge'
                                                        );

                                                        echo form_input($short_organization_name);
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <?php
                                                    echo form_label('Полное наименование', 'full_organization_name', $attributes_label);
                                                    ?>
                                                    <div class="controls">
                                                        <?php
                                                        $full_organization_name = array(
                                                            'name' => 'full_organization_name',
                                                            'id' => 'full_organization_name',
                                                            'placeholder' => '',
                                                            'value' => $rows->full_organization_name,
                                                            'class' => 'input-xlarge'
                                                        );

                                                        echo form_input($full_organization_name);
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <?php
                                                    echo form_label('Дополнительный адрес', 'alt_address', $attributes_label);
                                                    ?>
                                                    <div class="controls">
                                                        <?php
                                                        $alt_address = array(
                                                            'name' => 'alt_address',
                                                            'id' => 'alt_address',
                                                            'placeholder' => '',
                                                            'value' => $rows->alt_address,
                                                            'class' => 'input-xlarge'
                                                        );

                                                        echo form_input($alt_address);
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <?php
                                                    echo form_label('ИНН', 'inn', $attributes_label);
                                                    ?>
                                                    <div class="controls">
                                                        <?php
                                                        $inn = array(
                                                            'name' => 'inn',
                                                            'id' => 'inn',
                                                            'placeholder' => '',
                                                            'value' => $rows->inn,
                                                            'class' => 'input-xlarge'
                                                        );

                                                        echo form_input($inn);
                                                        ?>                                                        
                                                    </div>
                                                </div>

                                                <!-- Text input-->
                                                <div class="control-group">
                                                    <?php
                                                    echo form_label('Телефон (доп.)', 'alt_phone_number', $attributes_label);
                                                    ?>
                                                    <div class="controls">
                                                        <?php
                                                        $alt_phone_number = array(
                                                            'name' => 'alt_phone_number',
                                                            'id' => 'alt_phone_number',
                                                            'placeholder' => '',
                                                            'value' => $rows->alt_phone_number,
                                                            'class' => 'input-xlarge'
                                                        );

                                                        echo form_input($alt_phone_number);
                                                        ?>
                                                    </div>
                                                </div>

                                                <!-- Text input-->
                                                <div class="control-group">
                                                    <?php
                                                    echo form_label('Факс', 'fax', $attributes_label);
                                                    ?>
                                                    <div class="controls">
                                                        <?php
                                                        $fax = array(
                                                            'name' => 'fax',
                                                            'id' => 'fax',
                                                            'placeholder' => '',
                                                            'value' => $rows->fax,
                                                            'class' => 'input-xlarge'
                                                        );

                                                        echo form_input($fax);
                                                        ?>
                                                    </div>
                                                </div>

                                                <!-- Text input-->
                                                <div class="control-group">
                                                    <?php
                                                    echo form_label('Web', 'web_url', $attributes_label);
                                                    ?>
                                                    <div class="controls">
                                                        <?php
                                                        $web_url = array(
                                                            'name' => 'web_url',
                                                            'id' => 'web_url',
                                                            'placeholder' => '',
                                                            'value' => $rows->web_url,
                                                            'class' => 'input-xlarge'
                                                        );

                                                        echo form_input($web_url);
                                                    endforeach;
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-group">
                                    <div class="accordion-heading">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                                            Контакты организации ...
                                        </a>
                                    </div>
                                    <div id="collapseTwo" class="accordion-body collapse">
                                        <div class="accordion-inner">
                                            <p class="pull-left">

                                                <select id="selectContact" name="selectContact" class="input-xlarge" onchange="getval(this);">
                                                    <option></option>  
                                                </select>
                                            </p>
                                            <table class="table table-striped table-bordered table-condensed" id="contact_list">
                                                <thead>
                                                <th>ФИО</th>
                                                <th>Должность</th>
                                                <th>Телефон</th>
                                                <th>Email</th>
                                                <th>Адрес</th>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach($contactDetail as $contact):
                                                        echo '<tr>'
                                                            . '<td>'.anchor("addressbook/viewContactDetails/".$contact->id, $contact->contact_name).'</td>'
                                                            . '<td>'.$contact->job_position.'</td>'
                                                            . '<td>'.$contact->private_phone_number.'</td>'
                                                            . '<td>'.$contact->email.'</td>'
                                                            . '<td>'.$contact->address.'</td>'
                                                            . '<input type="hidden" name="token[]" value="'.$contact->contact_name.'" />'
                                                        . '</tr>';
                                                    endforeach;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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


