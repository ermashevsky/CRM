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
 * Класс Clients содержит методы работы  с данными клиентов
 *
 * @category PHP
 * @package  Models.Core_Model
 * @author   Ермашевский Денис <ermashevsky@gmail.com>
 * @access   public
 * @license  http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version  Release: 145
 * @link     http://www.ci2.lcl/
 */
class Core_model extends CI_Model {

    /**
     * Унифицированный метод-конструктор __construct()
     *
     * @author Ермашевский Денис
     */
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
    }

    function getCallEvent($phone_number) {
        $results = array();
        
        $this->db->select("id, src, dst, start,billsec,disposition, uniqueid, cause", false);
        $this->db->from('cdr');
        $this->db->where_in('disposition', '16, 17, 19');
        $this->db->or_where('src', $phone_number);
        $this->db->or_where('dst', $phone_number);
        $this->db->order_by('start','desc');
        $this->db->limit(10);

        $res = $this->db->get();
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Core_model();
                $tmp->id = $row->id;
                $tmp->uniqueid = $row->uniqueid;
                $tmp->src = $row->src;
                $tmp->dst = $row->dst;
                $tmp->start = $row->start;
                $tmp->billsec = $row->billsec;
                $tmp->disposition = $row->disposition;
                $tmp->cause = $row->cause;
                
                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }
    
    function getContactDetail($phone_number){
        $results = array();
        
        $this->db->select("id, organization_name as contact_name", false);
        $this->db->from('organization');
        $this->db->where('phone_number', $phone_number);
        $this->db->or_where('alt_phone_number', $phone_number);
        
        $res = $this->db->get();
        if (0 < $res->num_rows) {
            $ret = $res->row();
            echo $ret->contact_name;
        }else{
        $this->db->select("id,contact_name", false);
        $this->db->from('contacts');
        $this->db->where('private_phone_number', $phone_number);
        $this->db->or_where('mobile_number', $phone_number);
        
        $res = $this->db->get();
        if (0 < $res->num_rows) {
            $ret = $res->row();
            echo $ret->contact_name;
        }else{
            $this->db->select("id,first_name, last_name", false);
            $this->db->from('users');
            $this->db->where('phone', $phone_number);
            //$this->db->or_where('mobile_number', $phone_number);
        
        $res = $this->db->get();
        if (0 < $res->num_rows) {
            $ret = $res->row();
            echo $ret->first_name." ".$ret->last_name ." (внутр.)";
        } 
        }
    }
    }

}

//End of file core_model.php
//Location: ./modules/core/models/core_model.php