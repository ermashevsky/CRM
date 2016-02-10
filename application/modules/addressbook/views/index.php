<body>
    <div class="container-fluid">
        <div class="row">
            <div class="span10">
                <h3 style="color:#08c;">Адресная книга</h3>
                <ul class="nav nav-tabs">
                    <li class="active"> <a href="#organizations_list" data-toggle="tab">Организации</a></li>
                    <li><a href="#contacts_list" data-toggle="tab">Контакты</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="organizations_list">
                        
                        <a href='/addressbook/addOrganization' name="addOrganization" class="btn btn-info btn-small">Добавить организацию</a>

                        <table class="table table-striped table-bordered table-condensed" id='allOrganizationsTable'>
                            <thead>
                            <th>#</th>
                            <th>Наименование</th>
                            <th>Телефон (осн.)</th>
                            <th>Email</th>
                            <th>Адрес</th>
                            <th>Контакты</th>
                            <th>Записи</th>
                            </thead>
                            <tbody>
                                <?php
                                $recordsCount = new AddressBook();
                                $count = 1;
                                foreach ($table as $rows) {
                                    echo '<tr>'
                                    . '<td>' . $count++ . '</td>'
                                    . '<td>' . anchor("addressbook/viewOrganizationDetails/" . $rows->id, $rows->organization_name) . '</td>'
                                    . '<td>' . $rows->phone_number . '</td>'
                                    . '<td>' . $rows->email . '</td>'
                                    . '<td>' . $rows->address . '</td>'
                                    . '<td>' . $rows->counter_members . '</td>'
                                    . '<td style="width:70px;">' . anchor("records/viewOrganizationRecords/" . $rows->phone_number, '<span class="btn btn-info btn-mini">' . $recordsCount->getRecCount($rows->phone_number) . '</span> ') . '<a href="#taskWindow" title="Добавить запись" onclick=setTask(' . $rows->id . ',' . $rows->phone_number . '); return false; data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a></td>'
                                    . '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="tab-pane" id="contacts_list">
                        
                        <a href='/addressbook/addContact' name="addContact" class="btn btn-info btn-small">Добавить контакт</a>

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
                                $count2 = 1;
                                foreach ($table2 as $rows) {
                                    echo '<tr>'
                                    . '<td>' . $count2++ . '</td>'
                                    . '<td>' . anchor("addressbook/viewContactDetails/" . $rows->id, $rows->contact_name) . '</td>'
                                    . '<td>' . $rows->job_position . '</td>'
                                    . '<td>' . $rows->private_phone_number . '</td>'
                                    . '<td>' . $rows->email . '</td>'
                                    . '<td>' . $rows->address . '</td>';
                                    if (is_null($rows->organization_id) || $rows->organization_id === "0") {
                                        echo '<td></td>';
                                    } else {
                                        echo '<td>' . anchor("addressbook/viewOrganizationDetails/" . $rows->organization_id, $rows->organization_name) . '</td>';
                                    }
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div><!--/span-->
