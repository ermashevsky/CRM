<body>
    <div class="container-fluid">
        <div class="row">
            <div class="span10">
                <div class="row-fluid">
                    <div class="span12">
                        <h3>Карточка организации</h3>
                        <table class="table table-striped table-bordered table-condensed" summary="" id="organizationDetails" style="border-collapse:collapse;">
                            <tbody>
                                <?php
                                foreach ($organization as $row):
                                    ?>
                                    <?php
                                    if ($row->organization_name !== '') {
                                        ?>
                                        <tr>
                                            <th scope="row">Наименование</th>
                                            <td>
                                                <?php
                                                echo $row->organization_name;
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($row->short_organization_name !== '') {
                                        ?>
                                        <tr>
                                            <th scope="row">Краткое наименование</th>
                                            <td>
                                                <?php
                                                echo $row->short_organization_name;
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($row->full_organization_name !== '') {
                                        ?>
                                        <tr>
                                            <th scope="row">Полное наименование</th>
                                            <td>
                                                <?php
                                                echo $row->full_organization_name;
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($row->address !== '') {
                                        ?>
                                        <tr>
                                            <th scope="row">Адрес организации</th>
                                            <td>
                                                <?php
                                                echo $row->address;
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($row->alt_address !== '') {
                                        ?>
                                        <tr>
                                            <th scope="row">Дополнительный адрес</th>
                                            <td>
                                                <?php
                                                echo $row->alt_address;
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($row->phone_number !== '') {
                                        ?>
                                        <tr>
                                            <th scope="row">Телефон (основной)</th>
                                            <td>
                                                <?php
                                                echo $row->phone_number;
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($row->alt_phone_number !== '') {
                                        ?>
                                        <tr>
                                            <th scope="row">Телефон (доп.)</th>
                                            <td>
                                                <?php
                                                echo $row->alt_phone_number;
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($row->fax !== '') {
                                        ?>
                                        <tr>
                                            <th scope="row">Факс</th>
                                            <td>
                                                <?php
                                                echo $row->fax;
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($row->email !== '') {
                                        ?>
                                        <tr>
                                            <th scope="row">Email</th>
                                            <td>
                                                <?php
                                                echo $row->email;
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($row->comment !== '') {
                                        ?>
                                        <tr>
                                            <th scope="row">Комментарий</th>
                                            <td>
                                                <?php
                                                echo $row->comment;
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($row->inn !== '0') {
                                        ?>
                                        <tr>
                                            <th scope="row">ИНН</th>
                                            <td>
                                                <?php
                                                echo $row->inn;
                                                ?>
                                            </td>
                                        </tr>

                                        <?php
                                    }
                                    ?>
                                    <tr>
                                        <th data-toggle="collapse" data-target="#demo1" class="accordion-toggle" scope="row">Местоположение на карте ...</th>
                                        <td class="hiddenRow">
                                            <div class="accordian-body collapse" id="demo1">
                                                <?php
                                                echo $map['js'];
                                                echo $map['html'];
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <?php
                            echo form_button('deleteFromOrganization', '<i class="icon-trash"> </i>Удалить', 'class="btn btn-danger btn-small pull-right" onclick="deleteOrganization(' . $row->id . '); return false;"');
                            echo anchor("addressbook/editOrganizationDetails/" . $row->id, '<i class="icon-pencil"> </i>Редактировать', 'class="btn btn-success btn-small pull-right"');
                        endforeach;
                        ?>

                        <h3>Контакты организации</h3>
                        <?php
                        echo form_hidden('organization_id', $row->id);
                        echo form_hidden('organization_name', $row->organization_name);

                        echo form_button('newContactRefererForm', '<i class="icon-plus"> </i>Новый контакт', 'class="btn btn-info btn-small pull-left" onclick=newContactRefererModalForm(); return false;');
                        ?>
                        <table class="table table-striped table-bordered table-condensed" summary="" id="contactList">
                            <thead>
                                <tr>
                                    <th>
                                        #
                                    </th>
                                    <th>
                                        ФИО
                                    </th>
                                    <th>
                                        Должность
                                    </th>
                                    <th>
                                        Телефон
                                    </th>
                                    <th>
                                        Email
                                    </th>
                                    <th>
                                        Адрес
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $counter = 1;
                                foreach ($contacts as $rows):
                                    echo '<tr>'
                                    . '<td>' . $counter++
                                    . '</td>'
                                    . '<td>' . anchor("addressbook/viewContactDetails/" . $rows->id, $rows->contact_name)
                                    . '</td>'
                                    . '<td>' . $rows->job_position
                                    . '</td>'
                                    . '<td>' . $rows->private_phone_number
                                    . '</td>'
                                    . '<td>' . $rows->email
                                    . '</td>'
                                    . '<td>' . $rows->address
                                    . '</td>',
                                    '<td>'
                                    . form_button('deleteFromOrganization', '<i class="icon-trash"> </i>Удалить', 'class="btn btn-danger btn-mini pull-right" onclick="deleteFromOrganization(' . $rows->id . '); return false;"')
                                    . '</td>'
                                    . '</tr>';
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div><!--/span-->
                </div><!--/row-->
            </div><!--/row-->

            <div id="myNewContactForm" class="modal hide fade" 
                 tabindex="-1" role="dialog">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        ×</button>
                    <h3>Новый контакт</h3>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal" id="contactData">
                        <!-- Text input-->
                        <div class="control-group">
                            <label for="contact_name" class="control-label">ФИО</label>                                    <div class="controls">
                                <input type="text" name="contact_name" value="" id="contact_name" placeholder="" class="input-xlarge">                                        
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="control-group">
                            <label for="job_position" class="control-label">Должность</label>                                    <div class="controls">
                                <input type="text" name="job_position" value="" id="job_position" placeholder="" class="input-xlarge">                                    </div>
                        </div>

                        <!-- Text input-->
                        <div class="control-group">
                            <label for="private_phone_number" class="control-label">Телефон (основной)</label>                                    <div class="controls">
                                <input type="text" name="private_phone_number" value="" id="private_phone_number" placeholder="" class="input-xlarge">                                    </div>
                        </div>
                        <!-- Text input-->
                        <div class="control-group">
                            <label for="mobile_number" class="control-label">Мобильный</label>                                    <div class="controls">
                                <input type="text" name="mobile_number" value="" id="mobile_number" placeholder="" class="input-xlarge">                                    </div>
                        </div>
                        <!-- Text input-->
                        <div class="control-group">
                            <label for="email" class="control-label">Email</label>                                    <div class="controls">
                                <input type="text" name="email" value="" id="email" placeholder="" class="input-xlarge">                                    </div>
                        </div>
                        <!-- Text input-->
                        <div class="control-group">
                            <label for="address" class="control-label">Адрес</label>                                    <div class="controls">
                                <input type="text" name="address" value="" id="address" placeholder="" class="input-xlarge"> 
                            </div>
                        </div>
                        <!-- Text input-->
                        <div class="control-group">
                            <label for="birthday" class="control-label">Дата рождения</label>                                    <div class="controls">
                                <input type="text" name="birthday" value="" id="birthday" placeholder="" class="input-xlarge">                                    </div>
                        </div>

                        <div class="control-group">
                            <label for="comment" class="control-label">Дополнительно</label>                                    <div class="controls">
                                <textarea name="comment" cols="40" rows="10" id="comment" placeholder="" class="input-xlarge"></textarea>                                    </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="selectOrganization">Организация</label>
                            <div class="controls">
                                <select id="organization_name">

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" onclick="saveNewContact();return false;">Сохранить</button>
                    <button class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
