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
class Menu_model extends CI_Model {

    /**
     * Унифицированный метод-конструктор __construct()
     *
     * @author Ермашевский Денис
     */
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
    }

    function getAllMenuItemData(){
        
        $results = array();
        
        $this->db->select("id, plugin_menu_visible, plugin_menu_name, plugin_menu_item_order, plugin_uri");
        $this->db->from('system_plugins');
        $this->db->where('plugin_menu_visible','true');
        $this->db->where('plugin_state','checked');
        $this->db->order_by('plugin_menu_item_order','ASC');
        $res = $this->db->get();
        
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Menu_model();
                $tmp->id = $row->id;
                $tmp->plugin_menu_visible = $row->plugin_menu_visible;
                $tmp->plugin_menu_name = $row->plugin_menu_name;
                $tmp->plugin_menu_item_order = $row->plugin_menu_item_order;
                $tmp->plugin_uri = $row->plugin_uri;
                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }
    

}

//End of file core_model.php
//Location: ./modules/core/models/core_model.php