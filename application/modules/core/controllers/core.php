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

            $this->load->view('header');
            $this->load->view('index', $data);
            $this->load->view('rightsidebar');
        }
    }

    function insertCallData() {

        $data[] = $this->input->post('data');
        
        $tmp = array();
        
        foreach ($data as $val) {

            $tmp['src'] = $val['calleridnum'];
            $tmp['clid'] = $val['calleridname'];
            $tmp['dst'] = $val['dialstring'];
            $tmp['channel'] = $val['channel'];
            $tmp['dstchannel'] = $val['destination'];
            $tmp['uniqueid'] = $val['uniqueid'];

        }

        $this->load->model('core_model');
        return $this->core_model->insertCallData($tmp);
    }
    
    function updateLinkCallData() {
        
        date_default_timezone_set('Europe/Moscow');
        
        $data[] = $this->input->post('data');
        
        $tmp = array();
        
        foreach ($data as $val) {
            date_default_timezone_set('Europe/Moscow');
            $tmp['uniqueid'] = $val['uniqueid1'];
            $tmp['answer'] = date("Y-m-d H:i:s", now());

        }

        $this->load->model('core_model');
        return $this->core_model->updateLinkCallData($tmp['uniqueid'], $tmp['answer']);
    }
    
    function updateEndCallData() {
        
        
        $data[] = $this->input->post('data');
        print_r($data);
        
        $tmp = array();
        
        foreach ($data as $val) {
            
                $tmp['cause'] = $val['cause'];
                
            if($val['cause'] ==='17'){
                $tmp['disposition'] = 'BUSY';
            }
            
            if($val['cause'] ==='16'){
                $tmp['disposition'] = 'ANSWERED';
            }
            
            if($val['cause'] ==='19'){
                $tmp['disposition'] = 'NO ANSWER';
            }
            
            if($val['cause'] ==='34'){
                $tmp['disposition'] = 'FAILED';
            }
            
            if($val['cause'] ==='21'){
                $tmp['disposition'] = 'FAILED';
            }
            
            if($val['cause'] ==='1'){
                $tmp['disposition'] = 'FAILED';
            }
            date_default_timezone_set('Europe/Moscow');
                $tmp['uniqueid'] = $val['uniqueid'];
                $tmp['end'] = date("Y-m-d H:i:s", now());

        }

        $this->load->model('core_model');
        return $this->core_model->updateEndCallData($tmp['uniqueid'], $tmp['end'], $tmp['cause'], $tmp['disposition']);
    }

}
