<body>
    <div class="container-fluid">
        <div class="row">
            <div class="span10">
                <h3>Адресная книга</h3>
                <?php
                $groupButton = new AddressBook();
                $groupButton->createGroupButton();
                ?>
                <div class="span4">
                    <div class="btn-toolbar pull-left">
                    <ul class="nav nav-pills">
                        <?php echo anchor("addressbook/", "<i class='icon-home'> </i>Организации", array('class' => 'btn btn-small')); ?>
                        <?php echo anchor("addressbook/allContacts", "<i class='icon-user'> </i>Контакты", array('class' => 'btn btn-small')); ?>
                    </ul>
                </div>
                </div>
                <table class="table table-striped table-bordered table-condensed" id='allContactsTable'>
                    <thead>
                    <th>#</th>
                    <th>ФИО</th>
                    <th>Должность</th>
                    <th>Телефон</th>
                    <th>Email</th>
                    <th>Адрес</th>
                    <th>Организация</th>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        foreach ($table as $rows) {
                            echo '<tr>'
                            . '<td>' . $count++ . '</td>'
                            . '<td>' . anchor("addressbook/viewContactDetails/" . $rows->id, $rows->contact_name) . '</td>'
                            . '<td>' . $rows->job_position . '</td>'
                            . '<td>' . $rows->private_phone_number . '</td>'
                            . '<td>' . $rows->email . '</td>'
                            . '<td>' . $rows->address . '</td>';
                            if(is_null($rows->organization_id)||$rows->organization_id === "0"){
                                echo '<td></td>';
                            }else{
                                echo '<td>' . anchor("addressbook/viewOrganizationDetails/" . $rows->organization_id, $rows->organization_name) . '</td>';
                            }
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div><!--/span-->
