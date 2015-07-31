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
class Calendar extends MX_Controller {

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
        $this->load->library('calendar');
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
    
    function getTasksWithDates(){
        $this->load->model('calendar_model');
        echo json_encode($this->calendar_model->getTasksWithDates());
    }
    
    function getUndatedRecords(){
        $this->load->model('calendar_model');
        echo json_encode($this->calendar_model->getUndatedRecords());
    }
    
    function closeRecords(){
        
        $id = trim($this->input->post('id'));
        
        $this->load->model('calendar_model');
        $this->calendar_model->closeRecords($id);
    }
}
