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
class Records_model extends CI_Model {

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
        $this->db->where('deleted_date','0000-00-00 00:00:00');
        $res = $this->db->get();
        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Records_model();
                if($data['user']->first_name.' '.$data['user']->last_name == $row->initiator || $this->getCRMUserById($row->assigned) == $data['user']->first_name.' '.$data['user']->last_name){
                $tmp->id = $row->id;
                $tmp->initiator = $row->initiator;
                $tmp->category = $row->category;
                $tmp->status = $row->status;
                $tmp->priority = $row->priority;
                $tmp->task_name = $row->task_name;
                $tmp->task_description = $row->task_description;
                $tmp->assigned = $row->assigned;
                $tmp->create_date = $row->create_date;
                $tmp->end_date = $row->end_date;
                if($row->execution_date !== "0000-00-00 00:00:00"){
                    $tmp->td_color = "#90ee90;";
                }else{
                    $tmp->td_color = "#87cefa;";
                }
                
                if(date("Y-m-d H:i:s",strtotime($row->end_date)) < date("Y-m-d H:i:s", now()) && $row->execution_date === "0000-00-00 00:00:00" && $row->end_date !== "0000-00-00 00:00:00"){
                    $tmp->td_color = "#ffc0cb;";
                }
                
                $results[$tmp->id] = $tmp;
                }
            }
        }
        return $results;
    }
    
    function getRecordsByPhoneNum($phone_num){
        $results = array();

        $this->db->select("*");
        $this->db->from('tasks');
        $this->db->where('deleted_date','0000-00-00 00:00:00');
        $this->db->where('phone_num',$phone_num);
        $res = $this->db->get();
        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Records_model();
                if($data['user']->first_name.' '.$data['user']->last_name == $row->initiator || $this->getCRMUserById($row->assigned) == $data['user']->first_name.' '.$data['user']->last_name){
                $tmp->id = $row->id;
                $tmp->initiator = $row->initiator;
                $tmp->category = $row->category;
                $tmp->status = $row->status;
                $tmp->priority = $row->priority;
                $tmp->task_name = $row->task_name;
                $tmp->task_description = $row->task_description;
                $tmp->assigned = $row->assigned;
                $tmp->create_date = $row->create_date;
                $tmp->end_date = $row->end_date;

                $results[$tmp->id] = $tmp;
                }
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
                $tmp = new Records_model();
                $tmp->id = $row->id;
                $tmp->category = $row->category;
                $tmp->status = $row->status;
                $tmp->priority = $row->priority;
                $tmp->task_name = $row->task_name;
                $tmp->task_description = $row->task_description;
                $tmp->assigned = $row->assigned;
                $tmp->create_date = $row->create_date;
                $tmp->end_date = $row->end_date;
                $tmp->initiator = $row->initiator;
                
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
                $tmp = new Records_model();
                $tmp->id = $row->id;
                $tmp->category = $row->category;
                $tmp->status = $row->status;
                $tmp->priority = $row->priority;
                $tmp->task_name = $row->task_name;
                $tmp->task_description = $row->task_description;
                $tmp->assigned = $row->assigned;
                $tmp->create_date = $row->create_date;
                $tmp->end_date = $row->end_date;
                
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
                $tmp = new Records_model();
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
//                echo $row->first_name;
//                echo $row->last_name;
            }
            return $row->first_name.' '.$row->last_name; 
        }
    }
    
    function getCRMAssignedUserById($id){
        $this->db->select("*");
        $this->db->from('users');
        $this->db->where('id', $id);
        $res = $this->db->get();

        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
//                echo $row->first_name;
//                echo $row->last_name;
            }
            echo $row->first_name.' '.$row->last_name; 
        }
    }
    
    
    function updateTaskParameters($id, $status, $priority, $assigned, $category, $task_description, $task_name, $reminder_date, $create_date, $end_date){

        $data = array(
                'status' => $status,
                'priority'=>$priority,
                'assigned'=>$assigned,
                'category'=>$category,
                'task_name'=>$task_name,
                'task_description'=>$task_description,
                'create_date'=>date('Y-m-d H:i:s', strtotime($create_date)),
                'end_date'=>date('Y-m-d H:i:s', strtotime($end_date))
            );

        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->update('tasks', $data);
        $this->db->trans_complete();
        
    }
    
    function getActiveRec($date){
        $results = array();
        $crmUser = new Records();
        
        $this->db->select("*");
        $this->db->from('tasks');
        $this->db->where('deleted_date','0000-00-00 00:00:00');
        $this->db->where('execution_date','0000-00-00 00:00:00');
        $this->db->where('DATE_FORMAT(create_date,"%Y-%m-%d") <= ', date('Y-m-d', strtotime($date)));
        $this->db->where('DATE_FORMAT(end_date,"%Y-%m-%d") >= ', date('Y-m-d', strtotime($date)));
        $this->db->or_where('(create_date', '"0000-00-00 00:00:00"',FALSE);
        $this->db->where('end_date','"0000-00-00 00:00:00"',FALSE);
        $this->db->where('deleted_date','"0000-00-00 00:00:00"',FALSE);
        $this->db->where('execution_date','"0000-00-00 00:00:00")',FALSE);
//        $this->db->or_where('(create_date !=', '"0000-00-00 00:00:00"',FALSE);
//        $this->db->where('end_date','"0000-00-00 00:00:00"',FALSE);
//        $this->db->where('deleted_date','"0000-00-00 00:00:00"',FALSE);
//        $this->db->where('execution_date','"0000-00-00 00:00:00")',FALSE);
        $res = $this->db->get();
        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Records_model();
                if($data['user']->first_name.' '.$data['user']->last_name == $row->initiator || $this->getCRMUserById($row->assigned) == $data['user']->first_name.' '.$data['user']->last_name){
                $tmp->id = $row->id;
                $tmp->initiator = $row->initiator;
                $tmp->category = $row->category;
                $tmp->status = $row->status;
                $tmp->priority = $row->priority;
                $tmp->task_name = $row->task_name;
                $tmp->task_description = $row->task_description;
                $tmp->assigned = $crmUser->getUserById($row->assigned);
                $tmp->create_date = $row->create_date;
                $tmp->end_date = $row->end_date;

                $results[$tmp->id] = $tmp;
                }
            }
        }
        return $results;
    }
    
    function getOverDueRec(){
        $results = array();
        $crmUser = new Records();
        
        $this->db->select("*");
        $this->db->from('tasks');
        $this->db->where('deleted_date','0000-00-00 00:00:00');
        $this->db->where('execution_date','0000-00-00 00:00:00');
        $this->db->where('DATE_FORMAT(end_date,"%Y-%m-%d") < ', date('Y-m-d', now()));
        $this->db->where('end_date !=', '0000-00-00 00:00:00');
        
        $res = $this->db->get();
        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Records_model();
                if($data['user']->first_name.' '.$data['user']->last_name == $row->initiator || $this->getCRMUserById($row->assigned) == $data['user']->first_name.' '.$data['user']->last_name){
                $tmp->id = $row->id;
                $tmp->initiator = $row->initiator;
                $tmp->category = $row->category;
                $tmp->status = $row->status;
                $tmp->priority = $row->priority;
                $tmp->task_name = $row->task_name;
                $tmp->task_description = $row->task_description;
                $tmp->assigned = $crmUser->getUserById($row->assigned);
                $tmp->create_date = $row->create_date;
                $tmp->end_date = $row->end_date;

                $results[$tmp->id] = $tmp;
                }
            }
        }
        return $results;
    }
    
    function getExecutionEndRec(){
        $results = array();
        
        $crmUser = new Records();
        
        $this->db->select("*");
        $this->db->from('tasks');
        $this->db->where('deleted_date','0000-00-00 00:00:00');
        $this->db->where('execution_date != ','0000-00-00 00:00:00');
        
        $res = $this->db->get();
        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Records_model();
                if($data['user']->first_name.' '.$data['user']->last_name == $row->initiator || $this->getCRMUserById($row->assigned) == $data['user']->first_name.' '.$data['user']->last_name){
                $tmp->id = $row->id;
                $tmp->initiator = $row->initiator;
                $tmp->category = $row->category;
                $tmp->status = $row->status;
                $tmp->priority = $row->priority;
                $tmp->task_name = $row->task_name;
                $tmp->task_description = $row->task_description;
                $tmp->assigned = $crmUser->getUserById($row->assigned);
                $tmp->create_date = $row->create_date;
                $tmp->end_date = $row->end_date;

                $results[$tmp->id] = $tmp;
                }
            }
        }
        return $results;
    }
    
    function getInWorkRec(){
        $results = array();
        
        $crmUser = new Records();
        
        $this->db->select("*");
        $this->db->from('tasks');
        $this->db->where('deleted_date','0000-00-00 00:00:00');
        $this->db->where('execution_date','0000-00-00 00:00:00');
        
        $res = $this->db->get();
        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Records_model();
                if($data['user']->first_name.' '.$data['user']->last_name == $row->initiator || $this->getCRMUserById($row->assigned) == $data['user']->first_name.' '.$data['user']->last_name){
                $tmp->id = $row->id;
                $tmp->initiator = $row->initiator;
                $tmp->category = $row->category;
                $tmp->status = $row->status;
                $tmp->priority = $row->priority;
                $tmp->task_name = $row->task_name;
                $tmp->task_description = $row->task_description;
                $tmp->assigned = $crmUser->getUserById($row->assigned);
                $tmp->create_date = $row->create_date;
                $tmp->end_date = $row->end_date;

                $results[$tmp->id] = $tmp;
                }
            }
        }
        return $results;
    }
    
    function getAllRec(){
        $results = array();
        
        $crmUser = new Records();
        
        $this->db->select("*");
        $this->db->from('tasks');
        $this->db->where('deleted_date','0000-00-00 00:00:00');
        
        $res = $this->db->get();
        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Records_model();
                if($data['user']->first_name.' '.$data['user']->last_name == $row->initiator || $this->getCRMUserById($row->assigned) == $data['user']->first_name.' '.$data['user']->last_name){
                $tmp->id = $row->id;
                $tmp->initiator = $row->initiator;
                $tmp->category = $row->category;
                $tmp->status = $row->status;
                $tmp->priority = $row->priority;
                $tmp->task_name = $row->task_name;
                $tmp->task_description = $row->task_description;
                $tmp->assigned = $crmUser->getUserById($row->assigned);
                $tmp->create_date = $row->create_date;
                $tmp->end_date = $row->end_date;

                $results[$tmp->id] = $tmp;
                }
            }
        }
        return $results;
    }
    
    function addTask($data){
        $this->db->trans_start();
        $this->db->insert('tasks', $data);
        $this->db->trans_complete();
    }
    
    function deleteTask($id){
        $this->db->trans_start();
        
        $data = array(
               'deleted_date' => date("Y-m-d H:i:s")
            );

        $this->db->where('id', $id);
        $this->db->update('tasks', $data); 

        $this->db->trans_complete();
    }
    
    function doneRecord($id){
       $this->db->trans_start();
        
        $data = array(
               'execution_date' => date("Y-m-d H:i:s")
            );

        $this->db->where('id', $id);
        $this->db->update('tasks', $data); 

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
    
    function getContactIDByPhoneNum($phone_num){
        $results = array();
        
        $this->db->select("id, organization_name as contact_name", false);
        $this->db->from('organization');
        $this->db->like('phone_number', $phone_num);
        $this->db->or_like('alt_phone_number', $phone_num);
        
        $res = $this->db->get();
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Records_model();
                $tmp->id = $row->id;
                $tmp->table_name = 'organization';
                $results[$tmp->id] = $tmp;
            }
            return $results;
        }else{
        $this->db->select("id,contact_name", false);
        $this->db->from('contacts');
        $this->db->like('private_phone_number', $phone_num);
        $this->db->or_like('mobile_number', $phone_num);
        
        $res = $this->db->get();
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Records_model();
                $tmp->id = $row->id;
                $tmp->table_name = 'contacts';
                $results[$tmp->id] = $tmp;
            }
            return $results;
        }else{
            $this->db->select("id,first_name, last_name", false);
            $this->db->from('users');
            $this->db->like('phone', $phone_num);
            $this->db->or_like('external_phone', $phone_num);
            //$this->db->or_where('mobile_number', $phone_number);
        
        $res = $this->db->get();
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Records_model();
                $tmp->id = $row->id;
                $tmp->table_name = 'users';
                $results[$tmp->id] = $tmp;
            }
            return $results;
        } 
        }
    }
    }
}

//End of file core_model.php
//Location: ./modules/core/models/core_model.php