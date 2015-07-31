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

class Menu extends MX_Controller {

    var $target = NULL;
    var $output = NULL;
    var $full_tag_open = '<div class="navbar navbar-fixed-top"><div class="navbar-inner"><div class="container"><a class="brand" href="#"><i class="icon-cog"></i> Office WebCRM 0.1</a><ul class="nav">';
    var $full_tag_close = '</ul></div></div></div>';
    var $item_tag_open = '<li>';
    var $item_tag_close = '</li>';
    var $cur_page = '';
    var $cur_class = 'active';
    var $cur_display = TRUE;
    private $_ci;

    public function __construct() {
        //log_message('debug', "Menu Library Initialized");
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('session');
        $this->load->database();
    }

    private function _data() {

        if ($this->load->ion_auth->is_admin()) {

            $this->load->model('menu_model');
            $getMenuData = $this->menu_model->getAllMenuItemData();



            $menu_data = array(
                'header' => array(
                    array('name' => 'Главная',
                        'slug' => '',
                        'class' => '')
                ),
                'footer' => array(
                    array('name' => 'About Us',
                        'slug' => 'about',
                        'class' => '')
                )
            );

            foreach ($getMenuData as $menuRow) {


                array_push($menu_data['header'], array('name' => $menuRow->plugin_menu_name,
                    'slug' => $menuRow->plugin_uri,
                    'class' => ''));
            }
            array_push($menu_data['header'], array('name' => 'Выход',
                'slug' => 'auth/logout',
                'class' => 'pull-right'));

            //print_r($data);
        } else {
            $this->load->model('menu_model');
            $getMenuData = $this->menu_model->getAllMenuItemData();



            $menu_data = array(
                'header' => array(
                    array('name' => 'Главная',
                        'slug' => '',
                        'class' => '')
                ),
                'footer' => array(
                    array('name' => 'About Us',
                        'slug' => 'about',
                        'class' => '')
                )
            );

            foreach ($getMenuData as $menuRow) {

                if ($menuRow->plugin_uri !== 'auth') {
                    array_push($menu_data['header'], array('name' => $menuRow->plugin_menu_name,
                        'slug' => $menuRow->plugin_uri,
                        'class' => ''));
                }
            }
            array_push($menu_data['header'], array('name' => 'Выход',
                'slug' => 'auth/logout',
                'class' => 'pull-right'));

            //print_r($data);
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