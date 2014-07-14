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
class Tasks extends MX_Controller {

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

            $this->load->model('tasks_model');
            $tableData['table'] = $this->tasks_model->getAllTasks();

            $menu = array('menu' => $this->menu->render('header'));
            $this->load->view('header', $menu);
            $this->load->view('index', $tableData);
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }
    
    function addTask() {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="alert alert-error">', '</div>');

        //$this->form_validation->set_rules('task_name', 'Заголовок', 'required|xss_clean|trim');
        $this->form_validation->set_rules('task_description', 'Описание', 'required|xss_clean|trim');

        if ($this->form_validation->run() === TRUE) {
            
            $status = trim($this->input->post('selectStatus'));
            $priority = trim($this->input->post('selectPriority'));
            $assigned = trim($this->input->post('selectAssigned'));
            $category = trim($this->input->post('selectCategory'));
            $task_name = trim($this->input->post('task_name'));
            $task_description = trim($this->input->post('task_description'));
            $reminder_date = trim($this->input->post('reminder_date'));
            $id_call = trim($this->input->post('id_call'));
            
            $user = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $fullname = $user->first_name." ".$user->last_name;
            
            if($reminder_date !== ""){
            $data = array(
                'status' => $status,
                'priority'=>$priority,
                'assigned'=>$assigned,
                'category'=>$category,
                'task_name'=>$task_name,
                'task_description'=>$task_description,
                'reminder_date'=>date('Y-m-d H:i:s',strtotime($reminder_date)),
                'end_date'=>NULL,
                'initiator' => $fullname,
                'id_call' => $id_call
            );
            }else{
                $data = array(
                    'status' => $status,
                    'priority'=>$priority,
                    'assigned'=>$assigned,
                    'category'=>$category,
                    'task_name'=>$task_name,
                    'task_description'=>$task_description,
                    'reminder_date'=> NULL,
                    'end_date'=>NULL,
                    'initiator' => $fullname,
                    'id_call' => $id_call
                );    
            }
            $this->load->model('tasks_model');
            $this->tasks_model->addTask($data);
            
            redirect('tasks/index', 'refresh');
            
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $this->load->module('menu');
            
            $menu = array('menu' => $this->menu->render('header'));
            $this->load->model('tasks_model');
            $dataTask['users'] = $this->tasks_model->getAllCRMUsers();
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
            $this->load->module('menu');
            $this->load->model('tasks_model');
            $dataTask['task'] = $this->tasks_model->getTaskByID($id);
            
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
            $this->load->module('menu');
            $this->load->model('tasks_model');
            $dataTask['task'] = $this->tasks_model->editTaskByID($id);
            $dataTask['users'] = $this->tasks_model->getAllCRMUsers();
                    
            $menu = array('menu' => $this->menu->render('header'));
            $this->load->view('header', $menu);
            $this->load->view('editTask', $dataTask);
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }
    
    function deleteTask($id){
        $this->load->model('tasks_model');
        $this->tasks_model->deleteTask($id);
        redirect('tasks/index', 'refresh');
    }
    
    function getUserById($id){
        $this->load->model('tasks_model');
        return $this->tasks_model->getCRMUserById($id);
    }
    
    function updateTaskParameters(){
        
        $id = trim($this->input->post('id'));
        $status = trim($this->input->post('status'));
        $priority = trim($this->input->post('selectPriority'));
        $assigned = trim($this->input->post('selectAssigned'));
        $category = trim($this->input->post('selectCategory'));
        $task_name = trim($this->input->post('task_name'));
        $task_description = trim($this->input->post('task_description'));
        $reminder_date = trim($this->input->post('reminder_date'));
        
        
        $this->load->model('tasks_model');
        $this->tasks_model->updateTaskParameters($id, $status, $priority, $assigned, $category, $task_description, $task_name, $reminder_date);
        redirect('tasks/viewTask/'.$id, 'refresh');
    }
    
    function closeTask($id){
        $this->load->model('tasks_model');
        $this->tasks_model->closeTask($id);
        redirect('tasks/viewTask/'.$id, 'refresh');
    }
    
    function reopenTask($id){
        $this->load->model('tasks_model');
        $this->tasks_model->reopenTask($id);
        redirect('tasks/viewTask/'.$id, 'refresh');
    }
    
    function getCRMUsers(){
        $this->load->model('tasks_model');
        echo json_encode($this->tasks_model->getAllCRMUsers());
    }
}
