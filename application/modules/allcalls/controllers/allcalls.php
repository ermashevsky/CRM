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
                $pos = strripos($calls->dst, "mera/");

                if ($pos !== false) {
                    list($str, $shlak) = explode("mera/", $calls->dst);
                    $dst = $shlak;
                } else {
                    $dst = $calls->dst;
                }

                $date = new DateTime($calls->end);

                if ($calls->dst === $data['user']->phone) {
                    echo '<tr><td>' . $date->format('d.m.Y H:i:s') . '</td><td>Входящий</td><td>' . $calls->src . '</td><td>' . $dst . '</td><td>' . $this->format_seconds($calls->billsec) . '</td><td>' . $calls->disposition . '</td><td><div class="btn-toolbar">
                    <div class="btn-group">
                        <a href="#" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" onclick="setContactItem(' . $calls->id . ',' . $calls->src . ');return false;" class="btn btn-info btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" onclick="setTask(' . $calls->id . '); return false;" data-toggle="modal" class="btn btn-info btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>
                </div></td></tr>';
                }

                if ($calls->src === $data['user']->phone) {
                    echo '<tr><td>' . $date->format('d.m.Y H:i:s') . '</td><td>Исходящий</td><td>' . $calls->src . '</td><td>' . $dst . '</td><td>' . $this->format_seconds($calls->billsec) . '</td><td>' . $calls->disposition . '</td><td><div class="btn-group">
                        <a href="#" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" class="btn btn-info btn-mini" disabled="true" data-role="button"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" onclick="setTask(' . $calls->id . '); return false;" data-toggle="modal" class="btn btn-info btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div></td></tr>';
                }

                if ($calls->dst === $data['user']->external_phone) {
                    echo '<tr><td>' . $date->format('d.m.Y H:i:s') . '</td><td>Входящий</td><td>' . $calls->src . '</td><td>' . $dst . '</td><td>' . $this->format_seconds($calls->billsec) . '</td><td>' . $calls->disposition . '</td><td><div class="btn-toolbar">
                    <div class="btn-group">
                        <a href="#" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" onclick="setContactItem(' . $calls->id . ',' . $calls->src . ');return false;" class="btn btn-info btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" onclick="setTask(' . $calls->id . '); return false;" data-toggle="modal" class="btn btn-info btn-mini"><i class="icon-white icon-tasks"></i></a>
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
        
        $user_phone_number = $data['user']->phone;

        $this->load->model('allcalls_model');

        if ($this->ion_auth->is_admin()) {
            if ($phone_number2 > 0 && $phone_number > 0) {
                $condition = "5";
            }
            if ($phone_number2 === "" && $phone_number !== 0) {
                $condition = "6";
            }

            if ($src>0 && $dst>0 && $phone_number2  === "" && $phone_number === "") {
                $condition = "1-3";
            }
            
            if($dst>0 && $src === "" && $phone_number2  === "" && $phone_number === ""){
                $condition = "2-4";
            }
            
            if($dst === "" && $src>0 && $phone_number2  === "" && $phone_number === ""){
                $condition = "2-4";
            }
            
            $filtered_call_data = $this->allcalls_model->getFilteredCalls2($date_time, $date_time2, $src, $dst, $status_call, $type_call, $user_phone_number, $phone_number, $phone_number2, $condition);
        } else {
            if ($phone_number2 > 0 && $phone_number > 0) {
                $condition = "5";
            }
            if ($phone_number2 === "" && $phone_number !== 0) {
                $condition = "6";
            }

            if ($src>0 && $dst>0 && $phone_number2  === "" && $phone_number === "") {
                $condition = "1-3";
            }
            
            if($dst>0 && $src === "" && $phone_number2  === "" && $phone_number === ""){
                $condition = "2-4";
            }
            
            if($dst === "" && $src>0 && $phone_number2  === "" && $phone_number === ""){
                $condition = "2-4";
            }

            $filtered_call_data = $this->allcalls_model->getFilteredCalls3($date_time, $date_time2, $src, $dst, $status_call, $type_call, $user_phone_number, $phone_number, $phone_number2, $condition);
        }

        echo json_encode($filtered_call_data);
    }

}
