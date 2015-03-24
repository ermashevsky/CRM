<body>
    <div class="container-fluid">
        <div class="row">
            <div class="span10">
                <div class="row-fluid">
                    <div class="span12">
                        <h3>Карточка контакта</h3>
                        <table class="table table-striped table-bordered table-condensed" summary="" id="contactList"  style="border-collapse:collapse;">

                            <tbody>
                                <?php
                                foreach ($contactDetail as $row):
                                    ?>
                                    <?php
                                    if ($row->contact_name !== '') {
                                        ?>
                                        <tr>
                                            <th scope="row">ФИО</th>
                                            <td>
                                                <?php
                                                echo $row->contact_name;
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($row->job_position !== '') {
                                        ?>
                                        <tr>
                                            <th scope="row">Должность</th>
                                            <td>
                                                <?php
                                                echo $row->job_position;
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($row->private_phone_number !== '') {
                                        ?>
                                        <tr>
                                            <th scope="row">Телефон</th>
                                            <td>
                                                <?php
                                                echo $row->private_phone_number;
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($row->mobile_number !== '') {
                                        ?>
                                        <tr>
                                            <th scope="row">Мобильный</th>
                                            <td>
                                                <?php
                                                echo $row->mobile_number;
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
                                            <th scope="row">Адрес</th>
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
                                    if ($row->birthday !== '') {
                                        ?>
                                        <tr>
                                            <th scope="row">Дата рождения</th>
                                            <td>
                                                <?php
                                                echo $row->birthday;
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
                                    if ($row->organization_id !== '') {
                                        ?>
                                        <tr>
                                            <th scope="row">Организация</th>
                                            <td>
                                                <?php
                                                $organizationDetails = new AddressBook();
                                                $data = $organizationDetails->getOrganizationDetails($row->organization_id);
                                                foreach ($data as $organizationRow):
                                                    echo anchor("addressbook/viewOrganizationDetails/" . $row->organization_id, $organizationRow->organization_name);
                                                    echo form_button('deleteFromOrganization', '<i class="icon-trash"> </i>Удалить из организации', 'class="btn btn-danger btn-mini pull-right" onclick="deleteFromOrganization(' . $row->id . '); return false;"');
                                                endforeach;
                                                ?>
                                            </td>
                                        </tr>
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
                                        <?php
                                    }

                                endforeach;
                                ?>
                            </tbody>
                        </table>
                        <br/>
                        <?php
                        echo anchor("addressbook/deleteContactDetails/" . $row->id, '<i class="icon-trash"> </i> Удалить', 'class="btn btn-danger btn-small pull-right"');
                        echo anchor("addressbook/editContactDetails/" . $row->id, '<i class="icon-pencil"> </i>Редактировать', 'class="btn btn-success btn-small pull-right"');
                        ?>
                    </div><!--/span-->
                </div><!--/row-->
            </div><!--/row-->


