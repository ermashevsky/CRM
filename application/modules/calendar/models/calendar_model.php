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
class Calendar_model extends CI_Model {

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

    function getTasksWithDates() {
        $results = array();

        $this->db->select("*");
        $this->db->from('tasks');
        $res = $this->db->get();

        if (0 < $res->num_rows) {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            
            foreach ($res->result() as $row) {
                $tmp = new Calendar_model();
                if($data['user']->first_name.' '.$data['user']->last_name == $row->initiator || $data['user']->id == $row->assigned){
                $tmp->id = $row->id;
                $tmp->category = $row->category;
                $tmp->status = $row->status;
                $tmp->priority = $row->priority;
                $tmp->title = $row->task_name;
                $tmp->task_description = $row->task_description;
                $tmp->assigned = $row->assigned;
                $tmp->start = $row->create_date;
                $tmp->end = $row->end_date;

                $results[] = $tmp;
                }
            }
        }
        return $results;
    }

    function getUndatedRecords() {
        $results = array();

        $this->db->select("*");
        $this->db->from('tasks');
        $this->db->where('create_date', '0000-00-00 00:00:00');
        $this->db->where('execution_date', '0000-00-00 00:00:00');
        $res = $this->db->get();

        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Calendar_model();
                $tmp->id = $row->id;
                $tmp->title = $row->task_name;
                $tmp->task_description = $row->task_description;
                $tmp->assigned = $row->assigned;
                $tmp->start = $row->create_date;
                $tmp->end = $row->end_date;

                $results[] = $tmp;
            }
        }
        return $results;
    }

    function closeRecords($id) {
        $data = array(
            'execution_date' => date("Y-m-d H:i:s")
        );

        $this->db->where('id', $id);
        $this->db->update('tasks', $data);
    }

}

//End of file core_model.php
//Location: ./modules/core/models/core_model.php