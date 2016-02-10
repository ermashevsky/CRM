<?php

/**
 * Upload
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Controllers.Core
 * @author   Ермашевский Денис <ermashevsky@gmail.com>
 * @license  http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link     http://www.crm-a.lcl/
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . "/third_party/PHPExcel.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * Класс Upload содержит методы работы с файлами CSV (загрузка, чтение, поиск и запись данных в БД)
 *
 * @category PHP
 * @package  Controllers.Core
 * @author   Ермашевский Денис <ermashevsky@gmail.com>
 * @access   public
 * @license  http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version  Release: 145
 * @link     http://www.crm-a.lcl/
 */
class Allcalls extends MX_Controller {

    /**
     * Унифицированный метод-конструктор __construct()
     *
     * @author Ермашевский Денис
     */
    function __construct() {
        parent::__construct();

        $this->load->library('ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('url', 'form');
    }

    function index() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $test = $this->ion_auth->get_users_groups($this->session->userdata('user_id'))->result(); // Return array groups 
            $data['user']->group = $test[0]->name;
            $this->load->module('menu');
            $menu = array('menu' => $this->menu->render('header'));
            $this->load->view('header', $menu);
            $this->load->view('index');
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }

    function getContactDetail($phone_number) {

        //$phone_number = $this->input->post('phone_number');

        $this->load->model('allcalls_model');
        $contactDetail = $this->allcalls_model->getContactDetail($phone_number);

        return $contactDetail;
    }

    function format_seconds($seconds) {
        $t = round($seconds);
        return sprintf('%02d:%02d:%02d', ($t / 3600), ($t / 60 % 60), $t % 60);
    }

    function actionList() {
        return '<div class="btn-toolbar">
                    <div class="btn-group">
                        <a href="#" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" onclick="setNote();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" data-toggle="modal" class="btn btn-info btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>
                </div>';
    }

    function searchOrganization() {

        $this->load->model('allcalls_model');
        if (isset($_GET['query'])) {
            $q = strtolower($_GET['query']);
            $this->allcalls_model->searchOrganization($q);
        }
    }

    function findNumber($text) {
        $pos = strrpos($text, '/'); // поиск позиции точки с конца строки
        if (!$pos) {
            return $text; // если точка не найдена - возвращаем строку
        }
        return substr($text, $pos, 20); // обрезаем строку используя количество 
        // символов до точки + 1 (сама точка, 
        // если она не нужна "+1" нужно убрать) 
    }

    function getAllCalls() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $this->load->model('allcalls_model');
            $call_data = $this->allcalls_model->getAllCall($data['user']->phone, $data['user']->external_phone);

            echo '<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="allcalls">'
            . '<thead><tr><th>Дата/Время</th><th>Тип звонка</th><th>Вызывающая сторона</th><th>Принимающая сторона</th><th>Длительность</th><th>Статус</th><th>Действия по звонку</th></tr></thead>';

            foreach ($call_data as $calls) {
                //$dst = $this->stripChars($calls->dst);
                $dst = $this->findNumber($calls->dst);

                $date = new DateTime($calls->end);

                if ($calls->dst === $data['user']->phone) {
                    $call_type = json_encode("incoming");
                    $call_type_php = "incoming";
                    echo '<tr><td>' . $date->format('d.m.Y H:i:s') . '</td><td>Входящий</td><td>' . $calls->src . '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($calls->src) . '</span></td><td>' . $dst . '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($calls->dst) . '</span>' . '</td><td>' . $this->format_seconds($calls->billsec) . '</td><td>' . $this->translateDisposition($call_type_php, $calls->disposition) . '</td><td><div class="btn-toolbar">
                    <div class="btn-group">
                        <a href="#" title="Вызов абонента" onclick=prepareOriginateCall(' . $data['user']->phone . ',' . $calls->src . ',' . $dst . ',' . $call_type . ');return false; class="btn btn-warning btn-mini"><i class="icon-white icon-phone"></i></a>
                        <a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" title="Добавить контакт" onclick=setContactItem(' . $calls->id . ',' . $calls->src . ',' . $dst . ',' . $call_type . ');return false; class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" title="Добавить запись" onclick=setTask(' . $calls->id . ',' . $calls->src . ',' . $dst . ',' . $call_type . '); return false; data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>
                </div></td></tr>';
                }

                $position = strripos($calls->channel, 'SIP/' . $data['user']->phone);

                if ($calls->src === $data['user']->phone || $position !== false) {
                    $call_type = json_encode("outgoing");
                    $call_type_php = "outgoing";
                    echo '<tr><td>' . $date->format('d.m.Y H:i:s') . '</td><td>Исходящий</td><td>' . $calls->src . '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($calls->src) . '</span></td><td>' . $dst . '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($dst) . '</span></td><td>' . $this->format_seconds($calls->billsec) . '</td><td>' . $this->translateDisposition($call_type_php, $calls->disposition) . '</td><td><div class="btn-group">
                        <a href="#" title="Вызов абонента" onclick=prepareOriginateCall(' . $data['user']->phone . ',' . $calls->src . ',' . $dst . ',' . $call_type . ');return false; class="btn btn-warning btn-mini"><i class="icon-white icon-phone"></i></a>
                        <a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" title="Добавить контакт" onclick=setContactItem(' . $calls->id . ',' . $calls->src . ',' . $dst . ',' . $call_type . ');return false; class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" title="Добавить запись" onclick=setTask(' . $calls->id . ',' . $calls->src . ',' . $dst . ',' . $call_type . '); return false; data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div></td></tr>';
                }



                if ($calls->dst === $data['user']->external_phone) {
                    $call_type = json_encode("incoming");
                    $call_type_php = "incoming";
                    echo '<tr><td>' . $date->format('d.m.Y H:i:s') . '</td><td>Входящий</td><td>' . $calls->src . '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($calls->src) . '</span></td><td>' . $dst . '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($dst) . '</span></td><td>' . $this->format_seconds($calls->billsec) . '</td><td>' . $this->translateDisposition($call_type_php, $calls->disposition) . '</td><td><div class="btn-toolbar">
                    <div class="btn-group">
                        <a href="#" title="Вызов абонента" onclick=prepareOriginateCall(' . $data['user']->phone . ',' . $calls->src . ',' . $dst . ',' . $call_type . ');return false; class="btn btn-warning btn-mini"><i class="icon-white icon-phone"></i></a>
                        <a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" title="Добавить контакт" onclick=setContactItem(' . $calls->id . ',' . $calls->src . ',' . $dst . ',' . $call_type . ');return false; class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" title="Добавить запись" onclick=setTask(' . $calls->id . ',' . $calls->src . ',' . $dst . ',' . $call_type . '); return false; data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>
                </div></td></tr>';
                }
            }
            echo '</table>';
        }
    }
    
    function getAllOrganizationCalls($id) {
        
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $this->load->model('allcalls_model');
            $phone_organization_list = $this->allcalls_model->getOrganizationPhoneList($id);
            $call_data = $this->allcalls_model->getAllOrganizationCall($data['user']->phone, $data['user']->external_phone, $phone_organization_list);

            echo '<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="allcalls">'
            . '<thead><tr><th>Дата/Время</th><th>Тип звонка</th><th>Вызывающая сторона</th><th>Принимающая сторона</th><th>Длительность</th><th>Статус</th><th>Действия по звонку</th></tr></thead>';

            foreach ($call_data as $calls) {
                //$dst = $this->stripChars($calls->dst);
                $dst = $this->findNumber($calls->dst);

                $date = new DateTime($calls->end);

                if ($calls->dst === $data['user']->phone) {
                    $call_type = json_encode("incoming");
                    $call_type_php = "incoming";
                    echo '<tr><td>' . $date->format('d.m.Y H:i:s') . '</td><td>Входящий</td><td>' . $calls->src . '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($calls->src) . '</span></td><td>' . $dst . '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($calls->dst) . '</span>' . '</td><td>' . $this->format_seconds($calls->billsec) . '</td><td>' . $this->translateDisposition($call_type_php, $calls->disposition) . '</td><td><div class="btn-toolbar">
                    <div class="btn-group">
                        <a href="#" title="Вызов абонента" onclick=prepareOriginateCall(' . $data['user']->phone . ',' . $calls->src . ',' . $dst . ',' . $call_type . ');return false; class="btn btn-warning btn-mini"><i class="icon-white icon-phone"></i></a>
                        <a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" title="Добавить контакт" onclick=setContactItem(' . $calls->id . ',' . $calls->src . ',' . $dst . ',' . $call_type . ');return false; class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" title="Добавить запись" onclick=setTask(' . $calls->id . ',' . $calls->src . ',' . $dst . ',' . $call_type . '); return false; data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>
                </div></td></tr>';
                }

                $position = strripos($calls->channel, 'SIP/' . $data['user']->phone);

                if ($calls->src === $data['user']->phone || $position !== false) {
                    $call_type = json_encode("outgoing");
                    $call_type_php = "outgoing";
                    echo '<tr><td>' . $date->format('d.m.Y H:i:s') . '</td><td>Исходящий</td><td>' . $calls->src . '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($calls->src) . '</span></td><td>' . $dst . '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($dst) . '</span></td><td>' . $this->format_seconds($calls->billsec) . '</td><td>' . $this->translateDisposition($call_type_php, $calls->disposition) . '</td><td><div class="btn-group">
                        <a href="#" title="Вызов абонента" onclick=prepareOriginateCall(' . $data['user']->phone . ',' . $calls->src . ',' . $dst . ',' . $call_type . ');return false; class="btn btn-warning btn-mini"><i class="icon-white icon-phone"></i></a>
                        <a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" title="Добавить контакт" onclick=setContactItem(' . $calls->id . ',' . $calls->src . ',' . $dst . ',' . $call_type . ');return false; class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" title="Добавить запись" onclick=setTask(' . $calls->id . ',' . $calls->src . ',' . $dst . ',' . $call_type . '); return false; data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div></td></tr>';
                }



                if ($calls->dst === $data['user']->external_phone) {
                    $call_type = json_encode("incoming");
                    $call_type_php = "incoming";
                    echo '<tr><td>' . $date->format('d.m.Y H:i:s') . '</td><td>Входящий</td><td>' . $calls->src . '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($calls->src) . '</span></td><td>' . $dst . '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($dst) . '</span></td><td>' . $this->format_seconds($calls->billsec) . '</td><td>' . $this->translateDisposition($call_type_php, $calls->disposition) . '</td><td><div class="btn-toolbar">
                    <div class="btn-group">
                        <a href="#" title="Вызов абонента" onclick=prepareOriginateCall(' . $data['user']->phone . ',' . $calls->src . ',' . $dst . ',' . $call_type . ');return false; class="btn btn-warning btn-mini"><i class="icon-white icon-phone"></i></a>
                        <a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" title="Добавить контакт" onclick=setContactItem(' . $calls->id . ',' . $calls->src . ',' . $dst . ',' . $call_type . ');return false; class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" title="Добавить запись" onclick=setTask(' . $calls->id . ',' . $calls->src . ',' . $dst . ',' . $call_type . '); return false; data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>
                </div></td></tr>';
                }
            }
            echo '</table>';
        }
    }

    function getFilteredCalls() {

        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();

        $date_time = $this->input->post('date_time');
        $date_time2 = $this->input->post('date_time2');
        $type_call = $this->input->post('type_call');
        $src = $this->input->post('src');
        $dst = $this->input->post('dst');
        $status_call = $this->input->post('status_call');
        $phone_number = $this->input->post('phone_number');
        $phone_number2 = $this->input->post('phone_number2');
        $select_duration_value = $this->input->post('select_duration_value');
        $duration_minute = $this->input->post('duration_minute');
        $duration_second = $this->input->post('duration_second');

        if ($duration_minute > 0 && $duration_second > 0) {
            $duration = ($duration_minute * 60) + $duration_second;
        }

        if ($duration_minute == 0 && $duration_second > 0) {
            $duration = $duration_second;
        }

        if ($duration_minute == 0 && $duration_second == 0) {
            $duration = 0;
        }

        if ($duration_minute > 0 && $duration_second == 0) {
            $duration = ($duration_minute * 60) + $duration_second;
        }


        $user_phone_number = $data['user']->phone;

        $this->load->model('allcalls_model');

        if ($this->ion_auth->is_admin()) {
            if (strlen($phone_number2) > 0 && strlen($phone_number) > 0) {
                $condition = "5";
            }
            if ($phone_number2 === "" && strlen($phone_number) !== 0) {
                $condition = "6";
            }

            if (strlen($src) > 0 && strlen($dst) > 0 && $phone_number2 === "" && $phone_number === "") {
                $condition = "1-3";
            }

            if (strlen($dst) > 0 && $src === "" && $phone_number2 === "" && $phone_number === "") {
                $condition = "2-4";
            }

            if ($dst === "" && strlen($src) > 0 && $phone_number2 === "" && $phone_number === "") {
                $condition = "2-4";
            }

            $filtered_call_data = $this->allcalls_model->getFilteredCalls2($date_time, $date_time2, $src, $dst, $status_call, $type_call, $user_phone_number, $phone_number, $phone_number2, $duration, $select_duration_value, $condition);
        } else {
            if (strlen($phone_number2) > 0 && strlen($phone_number) > 0) {
                $condition = "5";
            }
            if ($phone_number2 === "" && strlen($phone_number) !== 0) {
                $condition = "6";
            }

            if (strlen($src) > 0 && strlen($dst) > 0 && $phone_number2 === "" && $phone_number === "") {
                $condition = "1-3";
            }

            if (strlen($dst) > 0 && $src === "" && $phone_number2 === "" && $phone_number === "") {
                $condition = "2-4";
            }

            if ($dst === "" && strlen($src) > 0 && $phone_number2 === "" && $phone_number === "") {
                $condition = "2-4";
            }

            $filtered_call_data = $this->allcalls_model->getFilteredCalls3($date_time, $date_time2, $src, $dst, $status_call, $type_call, $user_phone_number, $phone_number, $phone_number2, $duration, $select_duration_value, $condition);
        }

        echo json_encode($filtered_call_data);
    }

    function checkModuleStatus($moduleName) {
        $this->load->model('core_model');
        return $this->core_model->checkModuleStatus($moduleName);
    }

    function saveToDb() {

        $date_time = $this->input->post('date_time');
        $call_type = $this->input->post('call_type');
        $src = $this->input->post('src');
        $dst = $this->input->post('dst');
        $duration = $this->input->post('duration');
        $status = $this->input->post('status');
        $contact_dst = $this->getContactDetail($this->input->post('dst'));
        $contact_src = $this->getContactDetail($this->input->post('src'));

        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        $this->load->model('allcalls_model');
        $this->allcalls_model->saveToDb($date_time, $call_type, $src, $dst, $duration, $status, $contact_src, $contact_dst, $data['user']->id);
    }

    function truncateTable() {
        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();

        $this->load->model('allcalls_model');
        $this->allcalls_model->truncateTable($data['user']->id);
    }

    function saveToXLS() {

        $this->load->model('allcalls_model');
        $data = $this->allcalls_model->saveToXLS();

        $this->generateCsv($data);
    }

    function checkXLSTable() {
        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        $this->load->model('allcalls_model');
        $call_data = $this->allcalls_model->getAllCall($data['user']->phone, $data['user']->external_phone);

        foreach ($call_data as $val) {
            $tmp = array();

            $tmp['date_time'] = date('d.m.Y H:i:s', strtotime($val->end));

            $position = strripos($val->channel, 'SIP/' . $data['user']->phone);

            if ($val->src === $data['user']->phone || $position !== false) {
                $tmp['call_type'] = "Исходящий";
            }

            if ($val->dst === $data['user']->external_phone) {
                $tmp['call_type'] = "Входящий";
            }

            $tmp['src'] = $val->src;
            $tmp['contact_src'] = $this->getContactDetail($val->src);
            $tmp['dst'] = $val->dst;
            $tmp['contact_dst'] = $this->getContactDetail($val->dst);
            $tmp['duration'] = $this->format_seconds($val->billsec);
            $tmp['status'] = $val->disposition;

            $results[$val->id] = $tmp;
        }

        $this->generateCsv($results);
    }

    function getCurrentDataForXLS() {

        $results = array();

        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        $this->load->model('allcalls_model');
        $call_data = $this->allcalls_model->getAllCall($data['user']->phone, $data['user']->external_phone);

        foreach ($call_data as $val) {
            $tmp = array();

            $tmp['date_time'] = date('d.m.Y H:i:s', strtotime($val->end));

            $position = strripos($val->channel, 'SIP/' . $data['user']->phone);




            if ($val->src == $data['user']->phone || $position !== false || $val->src == $data['user']->external_phone) {

                $tmp['status'] = $this->translateDisposition("outgoing", $val->disposition);
                $tmp['call_type'] = "Исходящий";
            } else {
                $tmp['status'] = $this->translateDisposition("incoming", $val->disposition);
                $tmp['call_type'] = "Входящий";
            }

            if ($val->dst == $data['user']->external_phone || $val->dst == $data['user']->phone) {

                $tmp['status'] = $this->translateDisposition("incoming", $val->disposition);

                $tmp['call_type'] = "Входящий";
            } else {
                $tmp['status'] = $this->translateDisposition("outgoing", $val->disposition);

                $tmp['call_type'] = "Исходящий";
            }


            $tmp['src'] = $val->src;
            $tmp['contact_src'] = $this->getContactDetail($val->src);
            $tmp['dst'] = $val->dst;
            $tmp['contact_dst'] = $this->getContactDetail($val->dst);
            $tmp['duration'] = $this->format_seconds($val->billsec);

            $results[$val->id] = $tmp;
        }

        $this->createFiles($data['user']->id, $results);
    }

    function getDataForXLS() {

        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        $this->load->model('allcalls_model');
        $call_data = $this->allcalls_model->saveToXLS($data['user']->id);
        $this->createFiles($data['user']->id, $call_data);
    }

    function getCurrentDataForCSV() {

        $results = array();

        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        $this->load->model('allcalls_model');
        $call_data = $this->allcalls_model->getAllCall($data['user']->phone, $data['user']->external_phone);

        foreach ($call_data as $val) {
            $tmp = array();

            $tmp['date_time'] = date('d.m.Y H:i:s', strtotime($val->end));

            $position = strripos($val->channel, 'SIP/' . $data['user']->phone);


            if ($val->src == $data['user']->phone || $position !== false || $val->src == $data['user']->external_phone) {
                $tmp['call_type'] = "Исходящий";
                $tmp['src'] = $val->src;
                $tmp['contact_src'] = $this->getContactDetail($val->src);
                $tmp['dst'] = $val->dst;
                $tmp['contact_dst'] = $this->getContactDetail($val->dst);
                $tmp['duration'] = $this->format_seconds($val->billsec);
                $tmp['status'] = $this->translateDisposition("outgoing", $val->disposition);
            } else {
                $tmp['call_type'] = "Входящий";
                $tmp['src'] = $val->src;
                $tmp['contact_src'] = $this->getContactDetail($val->src);
                $tmp['dst'] = $val->dst;
                $tmp['contact_dst'] = $this->getContactDetail($val->dst);
                $tmp['duration'] = $this->format_seconds($val->billsec);
                $tmp['status'] = $this->translateDisposition("incoming", $val->disposition);
            }

            if ($val->dst == $data['user']->external_phone || $val->dst == $data['user']->phone) {
                $tmp['call_type'] = "Входящий";
                $tmp['src'] = $val->src;
                $tmp['contact_src'] = $this->getContactDetail($val->src);
                $tmp['dst'] = $val->dst;
                $tmp['contact_dst'] = $this->getContactDetail($val->dst);
                $tmp['duration'] = $this->format_seconds($val->billsec);
                $tmp['status'] = $this->translateDisposition("incoming", $val->disposition);
            } else {

                $tmp['call_type'] = "Исходящий";
                $tmp['src'] = $val->src;
                $tmp['contact_src'] = $this->getContactDetail($val->src);
                $tmp['dst'] = $val->dst;
                $tmp['contact_dst'] = $this->getContactDetail($val->dst);
                $tmp['duration'] = $this->format_seconds($val->billsec);
                $tmp['status'] = $this->translateDisposition("outgoing", $val->disposition);
            }

            $results[$val->id] = $tmp;
        }

        $this->generateCsv($results);
    }

    function getDataForCSV() {

        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        $this->load->model('allcalls_model');
        $call_data = $this->allcalls_model->saveToXLS($data['user']->id);
        $this->generateCsv($call_data);
    }

    function createFiles($user_id, $call_data) {
        $objPHPExcel = new PHPExcel();

        if (0 < $call_data) {

            $single_row = 1;

            $objPHPExcel->getActiveSheet()->setCellValue('A' . $single_row, 'Дата/Время');
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $single_row, 'Тип звонка');
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $single_row, 'Вызывающая сторона');
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $single_row, 'Контакт вызывающий');
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $single_row, 'Принимающая сторона');
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $single_row, 'Контакт принимающий');
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $single_row, 'Длительность');
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $single_row, 'Статус');

            foreach ($call_data as $values) {
                // Set document properties
                $single_row++;

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $single_row, date('d.m.Y H:i:s', strtotime($values['date_time'])));
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $single_row, $values['call_type']);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $single_row, $values['src']);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $single_row, $values['contact_src']);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $single_row, $values['dst']);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $single_row, $values['contact_dst']);
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $single_row, $values['duration']);
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $single_row, $values['status']);
            }
        }

        $objPHPExcel->setActiveSheetIndex(0);

        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $objPHPExcel->getActiveSheet()->getStyle('A1:H' . $single_row)->applyFromArray($styleArray);
        unset($styleArray);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        $objWriter->save($_SERVER['DOCUMENT_ROOT'] . "/file.xls");
        echo json_encode($_SERVER['DOCUMENT_ROOT'] . "/file.xls");
    }

    function getRowsCountXLSTable() {
        $this->load->model('allcalls_model');
        echo json_encode($this->allcalls_model->getRowsCountXLSTable());
    }

    function generateCsv($data, $delimiter = ',', $enclosure = '"') {
        $contents = "";

        $handle = fopen('php://temp', 'r+');
        foreach ($data as $line) {
            fputcsv($handle, $line, $delimiter, $enclosure);
        }
        rewind($handle);
        while (!feof($handle)) {
            $contents .= fread($handle, 8192);
        }
        fclose($handle);
        echo json_encode($contents);
    }

    function moduleButton($moduleName) {

        if ($this->checkModuleStatus($moduleName) === 'YES') {
            return '<a class="btn btn-default btn-mini pull-right" href="#" onclick="addRecord(); return false;" role="button"><i class="icon-plus-sign"></i></a>';
        }
    }

    function translateDisposition($call_type, $disposition) {
        if ($call_type == "incoming") {
            switch ($disposition) {
                case "ANSWERED":
                    return 'Ответили';
                case "BUSY":
                    return 'Пропущенный';
                case "NO ANSWER":
                    return 'Пропущенный';
                case "CALL INTERCEPTION":
                    return 'Перехваченный';
                case "ANSWER_BY":
                    return 'Переведенный';
                case "":
                    return 'Разговор';
                case "FAILED":
                    return 'Ошибка';
            }
        }

        if ($call_type == "outgoing") {
            switch ($disposition) {
                case "ANSWERED":
                    return 'Ответили';
                case "BUSY":
                    return 'Занято';
                case "NO ANSWER":
                    return 'Не отвеченный';
                case "CALL INTERCEPTION":
                    return 'Перехваченный';
                case "ANSWER_BY":
                    return 'Переведенный';
                case "":
                    return 'Разговор';
                case "FAILED":
                    return 'Ошибка';
            }
        }
    }

}
