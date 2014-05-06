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
 * @package  Models.Allcalls_Model
 * @author   Ермашевский Денис <ermashevsky@gmail.com>
 * @access   public
 * @license  http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version  Release: 145
 * @link     http://www.ci2.lcl/
 */
class Allcalls_model extends CI_Model {

    /**
     * Унифицированный метод-конструктор __construct()
     *
     * @author Ермашевский Денис
     */
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
    }

    function getAllCall($phone_number) {
        $results = array();
        
        $this->db->select("id, src, dst, start, answer, end, billsec,disposition, uniqueid, cause", false);
        $this->db->from('cdr');
        $this->db->or_where('src', $phone_number);
        $this->db->or_where('dst', $phone_number);
        $this->db->order_by('end','asc');
      
        $res = $this->db->get();
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Allcalls_model();
                $tmp->id = $row->id;
                $tmp->uniqueid = $row->uniqueid;
                $tmp->src = $row->src;
                $tmp->dst = $row->dst;
                $tmp->start = $row->start;
                $tmp->answer = $row->answer;
                $tmp->end = $row->end;
                $tmp->billsec = $row->billsec;
                $tmp->disposition = $row->disposition;
                $tmp->cause = $row->cause;
                
                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }

}

//End of file core_model.php
//Location: ./modules/core/models/core_model.php