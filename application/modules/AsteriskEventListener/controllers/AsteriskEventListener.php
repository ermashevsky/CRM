<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AsteriskEventListener
 *
 * @author denic
 */
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors', 1);
error_reporting(E_ALL);


use PAMI\Client\Impl\ClientImpl as PamiClient;
use PAMI\Message\Event\EventMessage;

class AsteriskEventListener extends MX_Controller {

    function __construct() {
        parent::__construct();
        
        $this->output->enable_profiler(TRUE);
    }

    function index() {
        
        $pamiClientOptions = array(
            'host' => '91.196.5.133',
            'port' => '5038',
            'username' => 'admin2',
            'secret' => 'admin2',
            'connect_timeout' => 10000,
            'read_timeout' => 10000
        );

        $pamiClient = new PamiClient($pamiClientOptions);

// Open the connection
        $pamiClient->open();

        $pamiClient->registerEventListener(function (EventMessage $event) {
            var_dump($event);
        });

        $running = true;

// Main loop
        while ($running) {
            $pamiClient->process();
            usleep(1000);
        }

// Close the connection
        $pamiClient->close();
        //put your code here
        $this->load->view('index');
        
    }

}
