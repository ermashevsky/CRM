<?php

/**
 * Upload
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Controllers.Core
 * @author   Ермашевский Денис <ermashevsky@gmail.com>
 * @license  http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link     http://www.crm-a.lcl/
 */
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * Класс Upload содержит методы работы с файлами CSV (загрузка, чтение, поиск и запись данных в БД)
 *
 * @category PHP
 * @package  Controllers.Core
 * @author   Ермашевский Денис <ermashevsky@gmail.com>
 * @access   public
 * @license  http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version  Release: 145
 * @link     http://www.crm-a.lcl/
 */
class AddressBook extends MX_Controller {

    /**
     * Унифицированный метод-конструктор __construct()
     *
     * @author Ермашевский Денис
     */
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('url', 'form');
    }

    function getAllContacts() {
        $this->load->model('addressbook_model');
        $data = $this->addressbook_model->getAllContacts();
        echo json_encode($data);
    }

    function getAllOrganizations() {
        $this->load->model('addressbook_model');
        $data = $this->addressbook_model->getAllOrganizations();
        echo json_encode($data);
    }

    function getContactById() {
        $id = trim($this->input->post('id'));

        $this->load->model('addressbook_model');
        $data = $this->addressbook_model->getContactById($id);
        echo json_encode($data);
    }

    function getContactDetails($id) {
        $this->load->model('addressbook_model');
        return $this->addressbook_model->getContactById($id);
    }

    function searchOrganizationId() {
        $organization_name = trim($this->input->post('organization_name'));

        $this->load->model('addressbook_model');
        $data = $this->addressbook_model->searchOrganizationId($organization_name);
        echo json_encode($data);
    }

    function addOrganizationData() {

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="alert alert-error">', '</div>');

        $this->form_validation->set_rules('organization_name', 'Наименование', 'required|xss_clean|trim');
        $this->form_validation->set_rules('phone_number', 'Телефон (основной)', 'required|xss_clean|trim');
        $this->form_validation->set_rules('address', 'Адрес', 'required|xss_clean|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim');

        if ($this->form_validation->run() === TRUE) {
            $organization_name = $this->input->post('organization_name');
            $address = $this->input->post('address');
            $phone_number = $this->input->post('phone_number');
            $email = $this->input->post('email');
            $comment = $this->input->post('comment');
            $short_organization_name = $this->input->post('short_organization_name');
            $full_organization_name = $this->input->post('full_organization_name');
            $alt_address = $this->input->post('alt_address');
            $inn = $this->input->post('inn');
            $alt_phone_number = $this->input->post('alt_phone_number');
            $fax = $this->input->post('fax');
            $web_url = $this->input->post('web_url');
            $selectContact = $this->input->post('token');

            $data = array(
                'organization_name' => $organization_name,
                'address' => $address,
                'phone_number' => $phone_number,
                'email' => $email,
                'comment' => $comment,
                'short_organization_name' => $short_organization_name,
                'full_organization_name' => $full_organization_name,
                'alt_address' => $alt_address,
                'inn' => $inn,
                'alt_phone_number' => $alt_phone_number,
                'fax' => $fax,
                'web_url' => $web_url
            );




            $this->load->model('addressbook_model');
            $last_id = $this->addressbook_model->addOrganizationData($data);
            foreach ($selectContact as $value):
            $this->addressbook_model->updateContactOrganizationIdField($last_id, $value);
            endforeach;

            redirect('addressbook/index', 'refresh');
            
        } else {
            $this->load->helper('form');
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $this->load->module('menu');
            $menu = array('menu' => $this->menu->render('header'));
            $this->load->view('header', $menu);
            $this->load->view('addOrganization');
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }

    function insertNewContactRow() {

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="alert alert-error">', '</div>');

        $this->form_validation->set_rules('contact_name', 'ФИО', 'required|xss_clean|trim');
        $this->form_validation->set_rules('job_position', 'Должность', 'required|xss_clean|trim');
        $this->form_validation->set_rules('private_phone_number', 'Телефон (основной)', 'required|xss_clean|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim');

        if ($this->form_validation->run() === TRUE) {

            $contact_name = $this->input->post('contact_name');
            $job_position = $this->input->post('job_position');
            $private_phone_number = $this->input->post('private_phone_number');
            $mobile_number = $this->input->post('mobile_number');
            $email = $this->input->post('email');
            $address = $this->input->post('address');
            $birthday = $this->input->post('birthday');
            $comment = $this->input->post('comment');
            $organization_id = $this->input->post('organization_id');
            
            if(is_numeric($this->input->post('organization_id'))){

            $data = array(
                'contact_name' => $contact_name,
                'job_position' => $job_position,
                'private_phone_number' => $private_phone_number,
                'mobile_number' => $mobile_number,
                'email' => $email,
                'address' => $address,
                'birthday' => $birthday,
                'comment' => $comment,
                'organization_id' => $organization_id,

            );

            $this->load->model('addressbook_model');
            $this->addressbook_model->insertNewContactRow($data);
            
            }else{

            $organization_note = $this->input->post('organization_id');
            
            $data = array(
                'contact_name' => $contact_name,
                'job_position' => $job_position,
                'private_phone_number' => $private_phone_number,
                'mobile_number' => $mobile_number,
                'email' => $email,
                'address' => $address,
                'birthday' => $birthday,
                'comment' => $comment,
                'organization_note' => $organization_note
            );

            $this->load->model('addressbook_model');
            $this->addressbook_model->insertNewContactRow($data);
            
            }
            
            redirect('addressbook/index', 'refresh');
            
        } else {
            $this->load->helper('form');
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $this->load->module('menu');
            $menu = array('menu' => $this->menu->render('header'));
            $this->load->view('header', $menu);
            $this->load->view('addContact');
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }

    function updateContactOrganizationIdField($id, $contact_name) {

        $this->load->model('addressbook_model');
        $last_id = $this->addressbook_model->updateContactOrganizationIdField($id, $contact_name);
    }

    function createGroupButton() {
        $groupButton = array(
            array('name' => 'Организация',
                'slug' => 'addOrganization',
                'class' => 'addressbook'),
            array('name' => 'Контакт',
                'slug' => 'addContact',
                'class' => 'addressbook'));

        echo '<div class="btn-toolbar pull-left">
                    <div class="btn-group">
                        <button class="btn btn-info btn-small dropdown-toggle" data-toggle="dropdown">
                            Добавить
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">';
        foreach ($groupButton as $value) {
            echo '<li>
                <a href=' . site_url($value['class'] . '/' . $value['slug']) . ' >' . $value['name'] . '</a>
              </li>';
        }
        echo '</ul></div></div>';
    }

    function index() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $test = $this->ion_auth->get_users_groups($this->session->userdata('user_id'))->result(); // Return array groups 
            $data['user']->group = $test[0]->name;
            $this->load->module('menu');

            $this->load->model('addressbook_model');
            $tableData['table'] = $this->addressbook_model->getAllTableData();

            $menu = array('menu' => $this->menu->render('header'));
            $this->load->view('header', $menu);
            $this->load->view('index', $tableData);
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }
    
    function allContacts() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $test = $this->ion_auth->get_users_groups($this->session->userdata('user_id'))->result(); // Return array groups 
            $data['user']->group = $test[0]->name;
            $this->load->module('menu');

            $this->load->model('addressbook_model');
            $tableData['table'] = $this->addressbook_model->getAllContactsTableData();

            $menu = array('menu' => $this->menu->render('header'));
            $this->load->view('header', $menu);
            $this->load->view('allContacts', $tableData);
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }

    function viewOrganizationDetails($id) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $test = $this->ion_auth->get_users_groups($this->session->userdata('user_id'))->result(); // Return array groups 
            $data['user']->group = $test[0]->name;
            $this->load->module('menu');
            $this->load->library('googlemaps');
            foreach ($this->getOrganizationDetails($id) as $rows):
                $config['center'] = $this->getCoordinates($rows->address);
                $config['zoom'] = 16;
                $config['places'] = TRUE;
                $this->googlemaps->initialize($config);

                $marker = array();
                $coordinates = $this->getCoordinates($rows->address);
                $marker['position'] = $coordinates;
                $this->googlemaps->add_marker($marker);
                $details['map'] = $this->googlemaps->create_map();
            endforeach;
            $details['organization'] = $this->getOrganizationDetails($id);
            $details['contacts'] = $this->getContactsListById($id);

            $menu = array('menu' => $this->menu->render('header'));
            $this->load->view('header', $menu);
            $this->load->view('viewOrganizationDetails', $details);
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }

    function getCoordinates($address) {

        $address = str_replace(" ", "+", $address); // replace all the white space with "+" sign to match with google search pattern
        $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=$address";
        $response = file_get_contents($url);
        $json = json_decode($response, TRUE); //generate array object from the response from the web

        return ($json['results'][0]['geometry']['location']['lat'] . "," . $json['results'][0]['geometry']['location']['lng']);
    }

    function viewContactDetails($id) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $test = $this->ion_auth->get_users_groups($this->session->userdata('user_id'))->result(); // Return array groups 
            $data['user']->group = $test[0]->name;
            $this->load->module('menu');
            $this->load->library('googlemaps');
            foreach ($this->getContactDetails($id) as $rows):
                $config['center'] = $this->getCoordinates($rows->address);
                $config['zoom'] = 16;
                $config['places'] = TRUE;
                $this->googlemaps->initialize($config);

                $marker = array();
                $coordinates = $this->getCoordinates($rows->address);
                $marker['position'] = $coordinates;
                $this->googlemaps->add_marker($marker);
                $details['map'] = $this->googlemaps->create_map();
            endforeach;

            $details['contactDetail'] = $this->getContactDetails($id);

            $menu = array('menu' => $this->menu->render('header'));
            $this->load->view('header', $menu);
            $this->load->view('viewContactDetails', $details);
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }

    function editOrganizationDetails($id) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $this->load->module('menu');

            $details['organizationDetail'] = $this->getOrganizationDetails($id);
            $details['contactDetail'] = $this->getContactsListById($id);

            $menu = array('menu' => $this->menu->render('header'));
            $this->load->view('header', $menu);
            $this->load->view('editOrganizationDetails', $details);
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }

    function editContactDetails($id) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $this->load->module('menu');

            $details['contactDetail'] = $this->getContactDetails($id);

            $menu = array('menu' => $this->menu->render('header'));
            $this->load->view('header', $menu);
            $this->load->view('editContactDetails', $details);
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }

    function updateOrganizationData() {

        $organization_name = $this->input->post('organization_name');
        $address = $this->input->post('address');
        $phone_number = $this->input->post('phone_number');
        $email = $this->input->post('email');
        $comment = $this->input->post('comment');
        $short_organization_name = $this->input->post('short_organization_name');
        $full_organization_name = $this->input->post('full_organization_name');
        $alt_address = $this->input->post('alt_address');
        $inn = $this->input->post('inn');
        $alt_phone_number = $this->input->post('alt_phone_number');
        $fax = $this->input->post('fax');
        $web_url = $this->input->post('web_url');
        $organization_id = $this->input->post('organization_id');
        $selectContact = $this->input->post('token');

        $data = array(
            'organization_name' => $organization_name,
            'address' => $address,
            'phone_number' => $phone_number,
            'email' => $email,
            'comment' => $comment,
            'short_organization_name' => $short_organization_name,
            'full_organization_name' => $full_organization_name,
            'alt_address' => $alt_address,
            'inn' => $inn,
            'alt_phone_number' => $alt_phone_number,
            'fax' => $fax,
            'web_url' => $web_url
        );

        $this->load->model('addressbook_model');
        $this->addressbook_model->updateOrganizationData($data, $organization_id);
        
        foreach ($selectContact as $value):
            $this->addressbook_model->updateContactOrganizationIdField($organization_id, $value);
        endforeach;
        
        redirect('addressbook/index', 'refresh');
    }

    function updateContactDetails() {
        $contact_name = $this->input->post('contact_name');
        $job_position = $this->input->post('job_position');
        $private_phone_number = $this->input->post('private_phone_number');
        $mobile_number = $this->input->post('mobile_number');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $birthday = $this->input->post('birthday');
        $comment = $this->input->post('comment');
        $organizationid = $this->input->post('organizationid');
        $organization_note = $this->input->post('organization_note');
        $contact_id = $this->input->post('contact_id');
        if(empty($organizationid)){
        $data = array(
            'contact_name' => $contact_name,
            'job_position' => $job_position,
            'private_phone_number' => $private_phone_number,
            'mobile_number' => $mobile_number,
            'email' => $email,
            'address' => $address,
            'birthday' => $birthday,
            'comment' => $comment,
            'organization_id' => NULL,
            'organization_note' => $organization_note
        );
        }else{
          $data = array(
            'contact_name' => $contact_name,
            'job_position' => $job_position,
            'private_phone_number' => $private_phone_number,
            'mobile_number' => $mobile_number,
            'email' => $email,
            'address' => $address,
            'birthday' => $birthday,
            'comment' => $comment,
            'organization_id' => $organizationid,
            'organization_note' => $organization_note
        );  
        }
        $this->load->model('addressbook_model');
        $this->addressbook_model->updateContactData($data, $contact_id);
        redirect('addressbook/index', 'refresh');
    }

    function getOrganizationDetails($id) {
        $this->load->model('addressbook_model');
        return $this->addressbook_model->getOrganizationDetails($id);
    }

    function getContactsListById($id) {
        $this->load->model('addressbook_model');
        return $this->addressbook_model->getContactsListById($id);
    }

    function addContact() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $this->load->module('menu');
            $menu = array('menu' => $this->menu->render('header'));
            $this->load->view('header', $menu);
            $this->load->view('addContact');
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }

    function addOrganization() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $this->load->module('menu');
            $menu = array('menu' => $this->menu->render('header'));
            $this->load->view('header', $menu);
            $this->load->view('addOrganization');
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }
    
    function getContactsOrganization(){
        $id = $this->input->post('id');
        $this->load->model('addressbook_model');
        $data = $this->addressbook_model->getContactsOrganization($id);
        echo json_encode($data);
    }

    function deleteFromOrganization() {
        $id = $this->input->post('id');
        $this->load->model('addressbook_model');
        $data = $this->addressbook_model->deleteFromOrganization($id);
        echo json_encode($data);
    }
    
    function deleteOrganization(){
        $id = $this->input->post('id');
        $this->load->model('addressbook_model');
        $data = $this->addressbook_model->deleteOrganization($id);
        echo json_encode($data);
    }
    
    function deleteOrganizationWithContacts(){
        $id = $this->input->post('id');
        $this->load->model('addressbook_model');
        $data = $this->addressbook_model->deleteOrganizationWithContacts($id);
        echo json_encode($data);
    }
    
    function deleteOrganizationWithoutContacts(){
        $id = $this->input->post('id');
        $this->load->model('addressbook_model');
        $data = $this->addressbook_model->deleteOrganizationWithContacts($id);
        echo json_encode($data);
    }

}
