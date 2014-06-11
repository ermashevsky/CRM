<?php

/**
 * Clients_model
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Models.Clients_Model
 * @author   Ермашевский Денис <ermashevsky@gmail.com>
 * @license  http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link     http://www.ci2.lcl/
 */
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * Класс Addressbook_model содержит методы работы  с адресными данными
 *
 * @category PHP
 * @package  Models.Addressbook_Model
 * @author   Ермашевский Денис <ermashevsky@gmail.com>
 * @access   public
 * @license  http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version  Release: 145
 * @link     http://www.ci2.lcl/
 */
class Tasks_model extends CI_Model {

    /**
     * Унифицированный метод-конструктор __construct()
     *
     * @author Ермашевский Денис
     */
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
    }

    function format_seconds($seconds) {
        $t = round($seconds);
        return sprintf('%02d:%02d:%02d', ($t / 3600), ($t / 60 % 60), $t % 60);
    }

    function getAllTasks() {
        $results = array();

        $this->db->select("*");
        $this->db->from('tasks');
        $res = $this->db->get();

        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Tasks_model();
                $tmp->id = $row->id;
                $tmp->category = $row->category;
                $tmp->status = $row->status;
                $tmp->priority = $row->priority;
                $tmp->task_name = $row->task_name;
                $tmp->assigned = $row->assigned;
                $tmp->create_date = $row->create_date;
                $tmp->end_date = $row->end_date;

                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }
    
    function getTaskByID($id){
        $this->db->select("*");
        $this->db->from('tasks');
        $this->db->where('id',$id);
        $res = $this->db->get();

        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Tasks_model();
                $tmp->id = $row->id;
                $tmp->category = $row->category;
                $tmp->status = $row->status;
                $tmp->priority = $row->priority;
                $tmp->task_name = $row->task_name;
                $tmp->task_description = $row->task_description;
                $tmp->assigned = $row->assigned;
                $tmp->create_date = $row->create_date;
                $tmp->end_date = $row->end_date;
                $tmp->reminder_date = $row->reminder_date;
                
                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }
    
    function editTaskByID($id){
        $this->db->select("*");
        $this->db->from('tasks');
        $this->db->where('id',$id);
        $res = $this->db->get();

        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Tasks_model();
                $tmp->id = $row->id;
                $tmp->category = $row->category;
                $tmp->status = $row->status;
                $tmp->priority = $row->priority;
                $tmp->task_name = $row->task_name;
                $tmp->task_description = $row->task_description;
                $tmp->assigned = $row->assigned;
                $tmp->create_date = $row->create_date;
                $tmp->end_date = $row->end_date;
                $tmp->reminder_date = $row->reminder_date;
                
                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }
    
    function getAllCRMUsers(){
        $this->db->select("*");
        $this->db->from('users');
        $res = $this->db->get();

        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Tasks_model();
                $tmp->id = $row->id;
                $tmp->first_name = $row->first_name;
                $tmp->last_name = $row->last_name;
               
                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }
    
    function getCRMUserById($id){
        $this->db->select("*");
        $this->db->from('users');
        $this->db->where('id', $id);
        $res = $this->db->get();

        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $row->first_name;
                $row->last_name;
            }
        }
        return $row->first_name." ".$row->last_name; 
    }
    
    
    function updateTaskParameters($id, $status, $priority, $assigned, $category, $task_description, $task_name, $reminder_date){
        if($reminder_date === ''){
            $data = array(
                'status' => $status,
                'priority'=>$priority,
                'assigned'=>$assigned,
                'category'=>$category,
                'task_name'=>$task_name,
                'task_description'=>$task_description,
                'reminder_date'=>NULL
            );
        }else{
        $data = array(
                'status' => $status,
                'priority'=>$priority,
                'assigned'=>$assigned,
                'category'=>$category,
                'task_name'=>$task_name,
                'task_description'=>$task_description,
                'reminder_date'=>date('Y-m-d H:i:s',strtotime($reminder_date))
            );
        }
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->update('tasks', $data);
        $this->db->trans_complete();
        
    }
    
    function addTask($data){
        $this->db->trans_start();
        $this->db->insert('tasks', $data);
        $this->db->trans_complete();
    }
    
    function deleteTask($id){
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->delete('tasks'); 
        $this->db->trans_complete();
    }
    
    function closeTask($id){
        date_default_timezone_set('Europe/Moscow');
        $data = array(
            'end_date' => date("Y-m-d H:i:s"),
            'status' => 'Решена'
        );
        
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->update('tasks', $data); 
        $this->db->trans_complete();
        //echo $this->db->last_query(); 
    }
    
    function reopenTask($id){
        
        $data = array(
            'end_date' => NULL,
            'status' => 'В работе',
        );
        
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->update('tasks', $data); 
        $this->db->trans_complete();
    }
}

//End of file core_model.php
//Location: ./modules/core/models/core_model.php