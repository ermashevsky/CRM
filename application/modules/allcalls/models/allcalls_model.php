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
    
    function format_seconds($seconds) {
        $t = round($seconds);
        return sprintf('%02d:%02d:%02d', ($t / 3600), ($t / 60 % 60), $t % 60);
    }
    
    function getAllCall($phone_number) {
        $results = array();
        
//        $this->db->select("id, src, dst, start, answe, end, billsec,disposition, uniqueid, cause", false);
//        $this->db->from('cdr');
////        $this->db->or_where('src', $phone_number);
////        $this->db->or_where('dst', $phone_number);
//        $this->db->where("start BETWEEN " .date("Y-m-d 00:00:00"). " AND ". date("Y-m-d 23:59:59"));
//        $this->db->where()
//        $this->db->order_by('end','asc');
      $res = $this->db->query("SELECT `id` ,  `src` ,  `dst` ,  `start` ,  `answer` ,  `end` ,  `billsec` ,  `disposition` ,  `uniqueid` ,  `cause` 
            FROM  `cdr` 
            WHERE  `end` 
            BETWEEN  '".date('Y-m-d 00:00:00')."'
            AND  '".date('Y-m-d 23:59:59')."'
            AND (
             `channel` like  '%/".$phone_number."%'
            OR  `dstchannel` like  '%/".$phone_number."%'
            )
            order by `end` asc");
      
        //$res = $this->db->get();
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Allcalls_model();
                $tmp->id = $row->id;
                $tmp->uniqueid = $row->uniqueid;
                $tmp->src = $row->src;
                $tmp->start = $row->start;
                $tmp->answer = $row->answer;
                $tmp->end = $row->end;
                $tmp->billsec = $row->billsec;
                $tmp->disposition = $row->disposition;
                $tmp->cause = $row->cause;
                
                $pos = strripos($row->dst, "#");
                
                if($pos !== false){
                    list($str, $shlak) = explode("#", $row->dst);
                    $tmp->dst = $shlak;
                }else{
                    $tmp->dst = $row->dst;
                }
                
                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }

    function getFilteredCalls($date_time, $date_time2, $src, $dst, $status_call,$type_call,$user_phone_number,$phone_number){
        $results = array();
        
        if($type_call === 'allcall' && $status_call === 'all_status'){

            $res = $this->db->query("SELECT `id` ,  `src` ,  `dst` ,  `start` ,  `answer` ,  `end` ,  `billsec` ,  `disposition` ,  `uniqueid` ,  `cause` 
            FROM  `cdr` 
            WHERE  `end` 
            BETWEEN  '".date('Y-m-d H:i:s', strtotime($date_time))."'
            AND  '".date('Y-m-d H:i:s', strtotime($date_time2))."'
            AND (
             `src` =  '".$user_phone_number."'
            OR  `dst` =  '".$user_phone_number."'
            )
            AND (
             `src` like  '%".$phone_number."%'
            OR  `dst` like  '%".$phone_number."%'
            )
            order by `end` asc");

        }
        if($status_call !== 'all_status' && $type_call === 'allcall'){
            
            $res = $this->db->query("SELECT `id` ,  `src` ,  `dst` ,  `start` ,  `answer` ,  `end` ,  `billsec` ,  `disposition` ,  `uniqueid` ,  `cause` 
            FROM  `cdr` 
            WHERE  `end` 
            BETWEEN  '".date('Y-m-d H:i:s', strtotime($date_time))."'
            AND  '".date('Y-m-d H:i:s', strtotime($date_time2))."'
            AND (
             `src` =  '".$user_phone_number."'
            OR  `dst` =  '".$user_phone_number."'
            )
            AND (
             `src` like  '%".$phone_number."%'
            OR  `dst` like  '%".$phone_number."%'
            )
            AND `disposition` = '".$status_call."'
            order by `end` asc");
        }
        
        if($status_call !== 'all_status' && $type_call !== 'allcall'){
            
            $res = $this->db->query("SELECT `id` ,  `src` ,  `dst` ,  `start` ,  `answer` ,  `end` ,  `billsec` ,  `disposition` ,  `uniqueid` ,  `cause` 
            FROM  `cdr` 
            WHERE  `end` 
            BETWEEN  '".date('Y-m-d H:i:s', strtotime($date_time))."'
            AND  '".date('Y-m-d H:i:s', strtotime($date_time2))."'
            AND `src` like  '%".$src."%'
            AND `dst` like  '%".$dst."%'
            AND `disposition` = '".$status_call."'
            order by `end` asc");
        }
        if($status_call === 'all_status' && $type_call !== 'allcall'){
            
            $res = $this->db->query("SELECT `id` ,  `src` ,  `dst` ,  `start` ,  `answer` ,  `end` ,  `billsec` ,  `disposition` ,  `uniqueid` ,  `cause` 
            FROM  `cdr` 
            WHERE  `end` 
            BETWEEN  '".date('Y-m-d H:i:s', strtotime($date_time))."'
            AND  '".date('Y-m-d H:i:s', strtotime($date_time2))."'
            AND `src` like  '%".$src."%'
            AND `dst` like  '%".$dst."%'
            order by `end` asc");
        }
        //$res = $this->db->get();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Allcalls_model();
                $tmp->id = $row->id;
                $tmp->uniqueid = $row->uniqueid;
                $tmp->src = $row->src;
                $tmp->start = $row->start;
                $tmp->answer = $row->answer;
                $tmp->end = $row->end;
                $tmp->billsec = $this->format_seconds($row->billsec);
                $tmp->disposition = $row->disposition;
                $tmp->cause = $row->cause;
                $tmp->btn_group = '<div class="btn-group">
                        <a href="#" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" onclick="setContactItem('.$row->id.','.$row->src.');return false;" class="btn btn-info btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" onclick="setTask('.$row->id.'); return false;" data-toggle="modal" class="btn btn-info btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>';
                $pos = strripos($row->dst, "#");
                if($pos === false){
                    $tmp->dst = $row->dst;
                }else{
                list($str, $shlak) = explode("#", $row->dst);
                    $tmp->dst = $shlak;
                }
                
                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }
}

//End of file core_model.php
//Location: ./modules/core/models/core_model.php