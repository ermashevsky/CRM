<?php

defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors', 1);
error_reporting(E_ALL);

/*
  | Controller::
  | $data = array('menu' => $this->menu->render('header') );
  | $this->load->view('my_view', $data);
  |
  | View::
  | echo $menu;
  |
  | Parser::
  | {menu}
 */

class Menu extends CI_Controller {

    var $target = NULL;
    var $output = NULL;
    var $full_tag_open = '<ul class="nav nav-pills">';
    var $full_tag_close = '</ul>';
    var $item_tag_open = '<li>';
    var $item_tag_close = '</li>';
    var $cur_page = '';
    var $cur_class = 'active';
    var $cur_display = TRUE;
    private $_ci;
    
    public function __construct() {
        $this->_ci =& get_instance();
        log_message('debug', "Menu Library Initialized");
        $this->_ci->load->library('ion_auth');
        $this->_ci->load->library('session');
        $this->_ci->load->database();
        
        
    }

    private function _data() {
    
        if ($this->_ci->load->ion_auth->is_admin()) {
            $menu_data = array(
                'header' => array(
                    array('name' => 'Главная',
                        'slug' => '',
                        'class' => ''),
                    array('name' => 'Все звонки',
                        'slug' => 'allcalls',
                        'class' => ''),
                    array('name' => 'Адресная книга',
                        'slug' => 'addressbook',
                        'class' => ''),
//                    array('name' => 'Календарь',
//                        'slug' => 'calendar',
//                        'class' => ''),
                    array('name' => 'Задачи',
                        'slug' => 'tasks',
                        'class' => ''),
                    array('name' => 'Админка',
                        'slug' => 'auth',
                        'class' => ''),
                    array('name' => 'Выход',
                        'slug' => 'auth/logout',
                        'class' => 'nav pull-right')
                ),
                'footer' => array(
                    array('name' => 'About Us',
                        'slug' => 'about',
                        'class' => '')
                )
            );
        } else {
            $menu_data = array(
                'header' => array(
                    array('name' => 'Главная',
                        'slug' => '',
                        'class' => ''),
                    array('name' => 'Все звонки',
                        'slug' => 'allcalls',
                        'class' => ''),
                    array('name' => 'Адресная книга',
                        'slug' => 'addressbook',
                        'class' => ''),
//                    array('name' => 'Календарь',
//                        'slug' => 'calendar',
//                        'class' => ''),
//                    array('name' => 'Задачи',
//                        'slug' => 'tasks',
//                        'class' => ''),
                    array('name' => 'Выход',
                        'slug' => 'auth/logout',
                        'class' => 'nav pull-right')
                ),
                'footer' => array(
                    array('name' => 'About Us',
                        'slug' => 'about',
                        'class' => '')
                )
            );
        }

        if (!empty($menu_data)) {
            if (array_key_exists($this->target, $menu_data)) {
                return $menu_data[$this->target];
            }
        }
    }

    private function _create_links() {
        $CI = & get_instance();

        $this->cur_page = trim_slashes($CI->uri->ruri_string());
        $li = NULL;

        foreach ($this->_data() as $line) {
            if ($this->cur_display == TRUE) {
                ($this->cur_page == $line['slug']) ? $line['class'] = $line['class'] . ' ' . $this->cur_class : $line['class'];
            }

            $li .= $this->item_tag_open . anchor($line['slug'], $line['name'], array('class' => $line['class'])) . $this->item_tag_close;
        }

        $this->output = $this->full_tag_open . $li . $this->full_tag_close;

        return $this->output;
    }

    public function render($input) {
        $this->target = $input;

        if ($this->target != NULL) {
            return $this->_create_links();
        }
    }

}

/* End of file Menu.php */
/* Location: ./application/libraries/Menu.php */