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
    
    function findNumber($text) {
        $pos = strrpos($text, '/'); // поиск позиции точки с конца строки
        if (!$pos) {
            return $text; // если точка не найдена - возвращаем строку
        }
        return substr($text, $pos+1, 20); // обрезаем строку используя количество 
        // символов до точки + 1 (сама точка, 
        // если она не нужна "+1" нужно убрать) 
    }

    function getCallEvent($phone_number, $external_phone) {
        $results = array();
        
//        $this->db->select('id, src, dst, start,end, billsec,disposition, uniqueid, cause', false);
//        $this->db->from('cdr');
//        $this->db->where_in('disposition', array('ANSWERED', 'BUSY', 'NO ANSWER'));
//        $this->db->where('src', $phone_number);
//        $this->db->or_where('dst', $phone_number);
//        $this->db->like('dst', $external_phone);
//        $this->db->or_like('dst', $external_phone);
//        $this->db->order_by('start','desc');
//        $this->db->limit(10);
        if(empty($external_phone)){
            $external_phone = '000';
        }
        
        $res = $this->db->query("SELECT id, src, dst, 
            start , end, billsec, disposition, uniqueid, cause, channel, dstchannel
            FROM (
             `cdr`
            )
            WHERE  `disposition` 
            IN (
             'ANSWERED',  'BUSY',  'NO ANSWER',''
            )
            AND (
             `src` =  '".$phone_number."'
            OR  `dst` =  '".$phone_number."'
            
            )
            OR (
             `dst` LIKE  '%".$external_phone."%'
            OR  `dst` LIKE  '%".$external_phone."%'
            
            )
            OR (
             `channel` like  'SIP/".$phone_number."%'
            OR  `dstchannel` =  'SIP/".$phone_number."%'
            
            )
            ORDER BY  `start` DESC
            limit 10
            ");
        //$res = $this->db->get();
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Core_model();
                $tmp->id = $row->id;
                $tmp->uniqueid = $row->uniqueid;
                $tmp->src = $row->src;
                $tmp->dst = $this->findNumber($row->dst);
                $tmp->start = $row->start;
                $tmp->end = $row->end;
                $tmp->billsec = $row->billsec;
                $tmp->disposition = $row->disposition;
                $tmp->channel = $row->channel;
                $tmp->dstchannel = $row->dstchannel;
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
            $this->db->or_where('external_phone', $phone_number);
            //$this->db->or_where('mobile_number', $phone_number);
        
        $res = $this->db->get();
        if (0 < $res->num_rows) {
            $ret = $res->row();
            echo $ret->first_name." ".$ret->last_name;
        } 
        }
    }
    }
    
    function checkModuleInstallBefore($system_name){
        
        $this->db->select("*");
        $this->db->from('system_plugins');
        $this->db->where('plugin_system_name', "$system_name");
        $res = $this->db->get();
        if (0 < $res->num_rows) {
            return "NO";
        }else{
            return "YES";
        }
    }
    
    function installModule($confModule){
        $this->db->insert('system_plugins', $confModule); 
    }
    
    function checkModuleStatus($moduleName){
      
    $this->db->select('*');
    $this->db->from('system_plugins');
    $this->db->where('plugin_state', "checked");
    $this->db->where('plugin_system_name', "$moduleName");
    $res = $this->db->get();
    if (0 < $res->num_rows) {
            return "YES";
        }else{
            return "NO";
        }
    }
    
     function getCRMUserById($id){
        $this->db->select("*");
        $this->db->from('users');
        $this->db->where('id', $id);
        $res = $this->db->get();

        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Core_model();
                $tmp->id = $row->id;
                $tmp->username = $row->username;
                $tmp->email = $row->email;
                $tmp->last_name = $row->last_name;
                $tmp->first_name = $row->first_name;
                $tmp->external_phone = $row->external_phone;
                $tmp->phone = $row->phone;
                $tmp->sms_notification = $row->sms_notification;
                $tmp->call_notification = $row->call_notification;
                $tmp->email_notification = $row->email_notification;
                $tmp->display_notification = $row->display_notification;
                
                $results[$tmp->id] = $tmp;
            }
            return $results; 
        }
    }
    
    function addReminder($data){
        
        if($data['user_id2'] != ''){
            
            $new_data = array(
                'reminder_date' => $data['reminder_date'],
                'reminder_description' => $data['reminder_description'],
                'user_id' => $data['user_id2'],
                'status' => '0'     
            );
            
            $this->db->trans_start();
            $this->db->insert('reminders', $new_data);
            $this->db->trans_complete();
            
            $new_data2 = array(
                'reminder_date' => $data['reminder_date'],
                'reminder_description' => $data['reminder_description'],
                'user_id' => $data['user_id'],
                'status' => '0'     
            );
            
            $this->db->trans_start();
            $this->db->insert('reminders', $new_data2);
            $this->db->trans_complete();
            
        }else{
            
            $new_data3 = array(
                'reminder_date' => $data['reminder_date'],
                'reminder_description' => $data['reminder_description'],
                'user_id' => $data['user_id'],
                'status' => '0'     
            );
            
            $this->db->trans_start();
            $this->db->insert('reminders', $new_data3);
            $this->db->trans_complete();
        }
        
        
    }

}

//End of file core_model.php
//Location: ./modules/core/models/core_model.php