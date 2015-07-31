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
class Records extends MX_Controller {

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

            $this->load->model('records_model');
            $tableData['table'] = $this->records_model->getAllTasks();
            $this->load->module('core');
            $checkModuleStatus = new Core();
            $count = $checkModuleStatus->checkModuleStatus('records');
            if ($count === "YES") {

                $menu = array('menu' => $this->menu->render('header'));
                $this->load->view('header', $menu);
                $this->load->view('index', $tableData);
                $this->load->view('rightsidebar', $data);
                $this->load->view('footer');
            } else {
                redirect('/', 'refresh');
            }
        }
    }

    function getContactIDByPhoneNum($phone_num) {
        $this->load->model('records_model');
        return $this->records_model->getContactIDByPhoneNum($phone_num);
    }

    function addTask() {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="alert alert-error">', '</div>');

        //$this->form_validation->set_rules('task_name', 'Заголовок', 'required|xss_clean|trim');
        $this->form_validation->set_rules('task_description', 'Описание', 'required|xss_clean|trim');

        if ($this->form_validation->run() === TRUE) {

            $phone_num = trim($this->input->post('phone_num'));
            
            foreach ($this->getContactIDByPhoneNum($this->input->post('phone_num')) as $val) {
                if($val->id){
                $id_contact = $val->id;
                $table_name = $val->table_name;
                }else{
                    $id_contact = NULL;
                    $table_name = '';
                }
            }
            
            if ($this->input->post('checkboxes-report')) {
                $checkboxes_report = 1;
            } else {
                $checkboxes_report = 0;
            }
            $assigned = trim($this->input->post('selectAssigned'));
            $task_name = trim($this->input->post('task_name'));
            $task_description = trim($this->input->post('task_description'));
            $create_date = trim($this->input->post('create_date'));
            $end_date = trim($this->input->post('end_date'));
            $id_call = trim($this->input->post('id_call'));
            $source_records = trim($this->input->post('source_records'));


            $user = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $fullname = $user->first_name . " " . $user->last_name;


            $data = array(
                'phone_num' => $phone_num,
                'id_contact' => $id_contact,
                'table_name' => $table_name,
                'report' => $checkboxes_report,
                'assigned' => $assigned,
                'task_name' => $task_name,
                'task_description' => $task_description,
                'create_date' => date('Y-m-d H:i:s', strtotime($create_date)),
                'end_date' => date('Y-m-d H:i:s', strtotime($end_date)),
                'initiator' => $fullname,
                'id_call' => $id_call,
                'source_records' => $source_records
            );

            $this->load->model('records_model');
            $this->records_model->addTask($data);

            redirect('records/index', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $test = $this->ion_auth->get_users_groups($this->session->userdata('user_id'))->result(); // Return array groups 
            $data['user']->group = $test[0]->name;
            $this->load->module('menu');

            $menu = array('menu' => $this->menu->render('header'));
            $this->load->model('records_model');
            $dataTask['users'] = $this->records_model->getAllCRMUsers();
            $this->load->view('header', $menu);
            $this->load->view('addTask', $dataTask);
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }

    function viewTask($id) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $test = $this->ion_auth->get_users_groups($this->session->userdata('user_id'))->result(); // Return array groups 
            $data['user']->group = $test[0]->name;
            $this->load->module('menu');
            $this->load->model('records_model');
            $dataTask['task'] = $this->records_model->getTaskByID($id);

            $menu = array('menu' => $this->menu->render('header'));
            $this->load->view('header', $menu);
            $this->load->view('viewTask', $dataTask);
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }

    function editTask($id) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $test = $this->ion_auth->get_users_groups($this->session->userdata('user_id'))->result(); // Return array groups 
            $data['user']->group = $test[0]->name;
            $this->load->module('menu');
            $this->load->model('records_model');
            $dataTask['task'] = $this->records_model->editTaskByID($id);
            $dataTask['users'] = $this->records_model->getAllCRMUsers();

            $menu = array('menu' => $this->menu->render('header'));
            $this->load->view('header', $menu);
            $this->load->view('editTask', $dataTask);
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }

    function deleteTask($id) {
        $this->load->model('records_model');
        $this->records_model->deleteTask($id);
        redirect('records/index', 'refresh');
    }

    function getUserById($id) {
        $this->load->model('records_model');
        return $this->records_model->getCRMUserById($id);
    }

    function updateTaskParameters() {

        $id = trim($this->input->post('id'));
        $status = trim($this->input->post('status'));
        $priority = trim($this->input->post('selectPriority'));
        $assigned = trim($this->input->post('selectAssigned'));
        $category = trim($this->input->post('selectCategory'));
        $task_name = trim($this->input->post('task_name'));
        $task_description = trim($this->input->post('task_description'));
        $reminder_date = trim($this->input->post('reminder_date'));


        $this->load->model('records_model');
        $this->records_model->updateTaskParameters($id, $status, $priority, $assigned, $category, $task_description, $task_name, $reminder_date);
        redirect('records/viewTask/' . $id, 'refresh');
    }

    function closeTask($id) {
        $this->load->model('records_model');
        $this->records_model->closeTask($id);
        redirect('records/viewTask/' . $id, 'refresh');
    }

    function reopenTask($id) {
        $this->load->model('records_model');
        $this->records_model->reopenTask($id);
        redirect('records/viewTask/' . $id, 'refresh');
    }

    function getCRMUsers() {
        $this->load->model('records_model');
        echo json_encode($this->records_model->getAllCRMUsers());
    }

}
