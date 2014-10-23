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
class Addressbook_model extends CI_Model {

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
    
    function getAllContacts() {
        $results = array();
        
        $this->db->select("*");
        $this->db->from('contacts');
        $res = $this->db->get();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Addressbook_model();
                $tmp->id = $row->id;
                $tmp->contact_name = $row->contact_name;
                $tmp->private_phone_number = $row->private_phone_number;
                $tmp->mobile_number = $row->mobile_number;
                $tmp->job_position = $row->job_position;
                $tmp->email = $row->email;
                $tmp->birthday = $row->birthday;
                $tmp->address = $row->address;
                $tmp->comment = $row->comment;
                $tmp->organization_id = $row->organization_id;
                
                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }
    
    function getAllOrganizations() {
        $results = array();
        
        $this->db->select("*");
        $this->db->from('organization');
        $res = $this->db->get();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Addressbook_model();
                $tmp->id = $row->id;
                $tmp->organization_name = $row->organization_name;
//                $tmp->private_phone_number = $row->private_phone_number;
//                $tmp->mobile_number = $row->mobile_number;
//                $tmp->job_position = $row->job_position;
//                $tmp->email = $row->email;
//                $tmp->birthday = $row->birthday;
//                $tmp->address = $row->address;
//                $tmp->comment = $row->comment;
//                $tmp->organization_id = $row->organization_id;
                
                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }
    
    function getAllTableData(){
        $results = array();
        
        $this->db->select("*, organization.id, organization.email, organization.address,COUNT( contacts.id ) AS counter_members", FALSE);
        $this->db->from('organization');
        $this->db->join('contacts','contacts.organization_id = organization.id','left');
        $this->db->group_by('organization.id');
        $this->db->order_by('organization.organization_name, organization.phone_number');
        $res = $this->db->get();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {

                $tmp = new Addressbook_model();
                $tmp->id = $row->id;
                $tmp->organization_name = $row->organization_name;
                $tmp->short_organization_name = $row->short_organization_name;
                $tmp->full_organization_name = $row->full_organization_name;
                $tmp->address = $row->address;
                $tmp->alt_address = $row->alt_address;
                $tmp->inn = $row->inn;
                $tmp->phone_number = $row->phone_number;
                $tmp->alt_phone_number = $row->alt_phone_number;
                $tmp->comment = $row->comment;
                $tmp->email = $row->email;
                $tmp->fax = $row->fax;
                $tmp->web_url = $row->web_url;
                $tmp->counter_members = $row->counter_members;
                
                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }
    
    function getAllContactsTableData(){
        $results = array();
        
        $this->db->select("*, contacts.id, contacts.email, contacts.address, contacts.comment, organization.organization_name", FALSE);
        $this->db->from('contacts');
        $this->db->join('organization','contacts.organization_id = organization.id','left');
        $this->db->group_by('contacts.id');
        $this->db->order_by('contacts.contact_name, contacts.private_phone_number');
        $res = $this->db->get();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {

                $tmp = new Addressbook_model();
                $tmp->id = $row->id;
                $tmp->organization_name = $row->organization_name;
                $tmp->address = $row->address;
                $tmp->private_phone_number = $row->private_phone_number;
                $tmp->mobile_number = $row->mobile_number;
                $tmp->comment = $row->comment;
                $tmp->email = $row->email;
                $tmp->contact_name = $row->contact_name;
                $tmp->job_position = $row->job_position;
                $tmp->organization_id = $row->organization_id;
                
                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }
    
    function getOrganizationDetails($id){
        $results = array();
        
        $this->db->select("*");
        $this->db->from('organization');
        $this->db->where('id', $id);
        $res = $this->db->get();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {

                $tmp = new Addressbook_model();
                $tmp->id = $row->id;
                $tmp->organization_name = $row->organization_name;
                $tmp->short_organization_name = $row->short_organization_name;
                $tmp->full_organization_name = $row->full_organization_name;
                $tmp->address = $row->address;
                $tmp->alt_address = $row->alt_address;
                $tmp->inn = $row->inn;
                $tmp->phone_number = $row->phone_number;
                $tmp->alt_phone_number = $row->alt_phone_number;
                $tmp->comment = $row->comment;
                $tmp->email = $row->email;
                $tmp->fax = $row->fax;
                $tmp->web_url = $row->web_url;
                
                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }
    
    function getContactById($id) {
        $results = array();
        
        $this->db->select("*");
        $this->db->from('contacts');
        $this->db->where('id', $id);
        $res = $this->db->get();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Addressbook_model();
                $tmp->id = $row->id;
                $tmp->contact_name = $row->contact_name;
                $tmp->private_phone_number = $row->private_phone_number;
                $tmp->mobile_number = $row->mobile_number;
                $tmp->job_position = $row->job_position;
                $tmp->email = $row->email;
                $tmp->birthday = $row->birthday;
                $tmp->address = $row->address;
                $tmp->comment = $row->comment;
                $tmp->organization_id = $row->organization_id;
                
                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }
    
    function getContactsListById($id) {
        $results = array();
        
        $this->db->select("*");
        $this->db->from('contacts');
        $this->db->where('organization_id', $id);
        $res = $this->db->get();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Addressbook_model();
                $tmp->id = $row->id;
                $tmp->contact_name = $row->contact_name;
                $tmp->private_phone_number = $row->private_phone_number;
                $tmp->mobile_number = $row->mobile_number;
                $tmp->job_position = $row->job_position;
                $tmp->email = $row->email;
                $tmp->birthday = $row->birthday;
                $tmp->address = $row->address;
                $tmp->comment = $row->comment;
                $tmp->organization_id = $row->organization_id;
                
                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }
    
    function searchOrganizationId($organization_name){
        $results = array();
        
        $this->db->select("*");
        $this->db->from('organization');
        $this->db->where('organization_name', $organization_name);
        $res = $this->db->get();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                return $row->id;
            }
        }else{
            return null;
        }
        
    }
    
    function addOrganizationData($data) {
        $this->db->trans_start();
        $this->db->insert('organization', $data);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }
    
    function insertNewContactRow($data) {
        $this->db->trans_start();
        $this->db->insert('contacts', $data);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }
    
    function updateContactOrganizationIdField($id, $contact_name){
        $data = array(
               'organization_id' => $id
            );
        $this->db->trans_start();
        $this->db->where('contact_name', $contact_name);
        $this->db->update('contacts', $data);
        $this->db->trans_complete();
        return 'ok';
    }
    
    function updateOrganizationData($data, $organization_id){
        $this->db->where('id', $organization_id);
        $this->db->update('organization', $data); 
    }
    
    function updateContactData($data, $contact_id){
        $this->db->where('id', $contact_id);
        $this->db->update('contacts', $data); 
    }
    
    function getContactsOrganization($id){
        
        $this->db->where('organization_id', $id);
        $this->db->from('contacts');
        return $this->db->count_all_results();
    }
    
    function deleteFromOrganization($id){
        
        $data = array(
               'organization_id' => null
            );
        
        $this->db->where('id', $id);
        $this->db->update('contacts', $data);
        
    }
    
    function deleteOrganization($id){
        
        $this->db->where('id', $id);
        $this->db->delete('organization');
        
    }
    
    function deleteOrganizationWithContacts($id){
        
        $this->db->where('id', $id);
        $this->db->delete('organization');
        
        $this->db->where('organization_id', $id);
        $this->db->delete('contacts');
        
    }
    
    function deleteOrganizationWithoutContacts($id){
        
        $this->db->where('id', $id);
        $this->db->delete('organization');
        
        $data = array(
               'organization_id' => null
            );
        
        $this->db->where('organization_id', $id);
        $this->db->update('contacts',$data);
        
    }
    
    function deleteContactDetails($id){
        
        $this->db->where('id', $id);
        $this->db->delete('contacts');
    }
}

//End of file core_model.php
//Location: ./modules/core/models/core_model.php