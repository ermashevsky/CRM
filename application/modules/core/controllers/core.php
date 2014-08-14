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
class Core extends MX_Controller {

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
            $this->load->module('menu');
            $menu = array('menu' => $this->menu->render('header') );
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

    function viewCallEvent($user_phone, $external_phone) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $this->load->model('core_model');
            $data = $this->core_model->getCallEvent($data['user']->phone,$data['user']->external_phone);
        }
        //return $data;
        echo '<li class="nav-header" style="color:#3a87ad;">История звонков</li>'
        . '<div style = "width: 200px; height: 370px; overflow: auto;" id = "scrollCall">';

        foreach ($data as $item) {
            if ($item->src === $user_phone) {
                $date = date_create($item->end, timezone_open("Europe/Moscow"));
                $formatted_date = date_format($date, "d.m.Y H:i:s");
                
                $pos = strripos($item->dst, "#");
                if($pos === false){
                    $dst = $item->dst;
                }else{
                list($str, $shlak) = explode("#", $item->dst);
                    $dst = $shlak;
                }
                
                echo '<address style="background: #FFDB58;">
                    <small><strong>Исходящий <i class="icon-arrow-right"></i></strong></small><br/>
                    <small>' . $formatted_date . '</small><br/>
                    <small>Номер:' . $dst . '</small><br/>
                    <small>Длительность:'. $this->format_seconds($item->billsec) .'</small><br/>';
                switch ($item->disposition){
                    case "ANSWERED":
                        echo '<small style="color:#51a351;"><b>Статус: Ответили </b></small><br/></address>';
                        break;
                    case "BUSY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Занят </b></small><br/></address>';
                        break;
                    case "NO ANSWER":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Не ответили </b></small><br/></address>';
                        break;
                    case "CALL INTERCEPTION":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Перехваченный </b></small><br/></address>';
                        break;
                    case "ANSWER_BY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Переведенный </b></small><br/></address>';
                        break;
                    case "":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Разговор </b></small><br/></address>';
                        break;
                }
            }

            if ($item->dst === $user_phone) {
                $date = date_create($item->end, timezone_open("Europe/Moscow"));
                $formatted_date = date_format($date, "d.m.Y H:i:s");
                
                echo '<address style="background:#98FF98;">
                    <small><strong>Входящий <i class="icon-arrow-left"></i></strong></small><br/>
                    <small>' . $formatted_date . '</small><br/>
                    <small>Номер:' . $item->src . '</small><br/>
                    <small>Длительность:' . $this->format_seconds($item->billsec) . '</small><br/>';
                switch ($item->disposition){
                    case "ANSWERED":
                        echo '<small style="color:#51a351;"><b>Статус: Ответили </b></small><br/></address>';
                        break;
                    case "BUSY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Пропущенный </b></small><br/></address>';
                        break;
                    case "NO ANSWER":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Пропущенный </b></small><br/></address>';
                        break;
                    case "CALL INTERCEPTION":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Перехваченный </b></small><br/></address>';
                        break;
                    case "ANSWER_BY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Переведенный </b></small><br/></address>';
                        break;
                    case "":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Разговор </b></small><br/></address>';
                        break;
                        
                }
            }
            $pos = strripos($item->dst, "#");
                if($pos === false){
                    $dst = $item->dst;
                }else{
                list($str, $shlak) = explode("#", $item->dst);
                    $dst = $shlak;
                }
                
            if ($dst === $external_phone) {
                $date = date_create($item->end, timezone_open("Europe/Moscow"));
                $formatted_date = date_format($date, "d.m.Y H:i:s");
                
                echo '<address style="background:#98FF98;">
                    <small><strong>Входящий <i class="icon-arrow-left"></i></strong></small><br/>
                    <small>' . $formatted_date . '</small><br/>
                    <small>Номер:' . $item->src . '</small><br/>
                    <small>Длительность:' . $this->format_seconds($item->billsec) . '</small><br/>';
                switch ($item->disposition){
                    case "ANSWERED":
                        echo '<small style="color:#51a351;"><b>Статус: Ответили </b></small><br/></address>';
                        break;
                    case "BUSY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Пропущенный </b></small><br/></address>';
                        break;
                    case "NO ANSWER":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Пропущенный </b></small><br/></address>';
                        break;
                    case "CALL INTERCEPTION":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Перехваченный </b></small><br/></address>';
                        break;
                    case "ANSWER_BY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Переведенный </b></small><br/></address>';
                        break;
                    case "":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Разговор </b></small><br/></address>';
                        break;
                        
                }
            }
        }
        echo '</div>';
    }
    
    function getContactDetail(){
        
        $phone_number = $this -> input -> post('phone_number');
        
        $this->load->model('core_model');
        $contactDetail = $this->core_model->getContactDetail($phone_number);
        
        return $contactDetail;
    }
}
