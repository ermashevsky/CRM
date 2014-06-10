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
                        <?php echo anchor("addressbook/", "Организации", array('class' => 'btn btn-small')); ?>
                        <?php echo anchor("addressbook/allContacts", "Контакты", array('class' => 'btn btn-small')); ?>
                    </ul>
                </div>
                </div>
                <table class="table table-striped table-bordered table-condensed" id='allContactsTable'>
                    <thead>
                    <th>#</th>
                    <th>Наименование</th>
                    <th>Телефон (осн.)</th>
                    <th>Email</th>
                    <th>Адрес</th>
                    <th>Контакты</th>
                    <th>События</th>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        foreach ($table as $rows) {
                            echo '<tr>'
                            . '<td>' . $count++ . '</td>'
                            . '<td>' . anchor("addressbook/viewOrganizationDetails/" . $rows->id, $rows->organization_name) . '</td>'
                            . '<td>' . $rows->phone_number . '</td>'
                            . '<td>' . $rows->email . '</td>'
                            . '<td>' . $rows->address . '</td>'
                            . '<td>' . $rows->counter_members . '</td>'
                            . '<td></td>'
                            . '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div><!--/span-->
