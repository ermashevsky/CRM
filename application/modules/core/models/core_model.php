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

    function insertCallData($data) {
        $this->db->insert('cdr', $data);
    }

    function updateLinkCallData($uniqueid, $answer) {
        $data = array(
            'answer' => $answer
        );

        $this->db->where('uniqueid', $uniqueid);
        $this->db->update('cdr', $data);
    }

    function updateEndCallData($uniqueid, $end, $cause, $disposition) {
        $this->db->select('start,answer');
        $this->db->from('cdr');
        $this->db->where('uniqueid', $uniqueid);
        $dates = $this->db->get();

        if (0 < $dates->num_rows) {
            foreach ($dates->result() as $date) {
                $date->start;
                $date->answer;
            }
            $start_call = strtotime($date->start);
            $end_call = strtotime($end);
            $duration_in_sec = $end_call - $start_call;

            if ($date->answer !== '0000-00-00 00:00:00') {
                $answer_call = strtotime($date->answer);
                $end_call = strtotime($end);
                $billsec_in_sec = $end_call - $answer_call;
            }
            $data = array(
            'end' => $end,
            'cause' => $cause,
            'duration' => abs($duration_in_sec),
            'billsec' => abs($billsec_in_sec),
            'disposition' => $disposition
        );
        }else{
            
            $data = array(
            'end' => $end,
            'cause' => $cause,
            'duration' => '0',
            'billsec' => '0',
            'disposition' => $disposition
        );
            
        }


        

        $this->db->where('uniqueid', $uniqueid);
        $this->db->update('cdr', $data);
    }

}

//End of file core_model.php
//Location: ./modules/core/models/core_model.php