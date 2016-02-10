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
class Core extends MX_Controller {

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
        $this->load->helper('url', 'form', 'directory');
    }

    function index() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $this->load->module('menu');
            //$menu = array('menu' => $this->menu->render('header'));
            $this->load->view('header'); //,$menu
            $this->load->view('index');
            $this->load->view('rightsidebar', $data);
            $this->load->view('footer');
        }
    }

    function format_seconds($seconds) {
        $t = round($seconds);
        return sprintf('%02d:%02d:%02d', ($t / 3600), ($t / 60 % 60), $t % 60);
    }

    function viewCallEvent($user_phone, $external_phone) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $this->load->model('core_model');
            $data = $this->core_model->getCallEvent($data['user']->phone, $data['user']->external_phone);
        }
        //return $data;
        echo '<div class="nav-header" style="color:#3a87ad;">История звонков</div>'
        . '<div style = "width: 200px; height: 370px; overflow: auto;" id = "scrollCall">';

        foreach ($data as $item) {
            $position = strripos($item->channel, 'SIP/' . $user_phone);
            if ($item->src === $user_phone || $position !== false) {
                $date = date_create($item->end, timezone_open("Europe/Moscow"));
                $formatted_date = date_format($date, "d.m.Y H:i:s");

                $pos = strripos($item->dst, "#");
                if ($pos === false) {
                    $dst = $item->dst;
                } else {
                    list($str, $shlak) = explode("#", $item->dst);
                    $dst = $shlak;
                }

                echo '<address style="background: #FFDB58;">' . $this->moduleButton('records', $dst, $user_phone, "outgoing") . '
                    <small><strong>Исходящий <i class="icon-arrow-right"></i></strong></small><br/>
                    <small>' . $formatted_date . '</small><br/>
                    <small>На номер:' . $dst . '</small><br/>
                    <input type="hidden" id="phone_num_hide' . $dst . '" value="' . $dst . '" />
                    <input type="hidden" id="id_call' . $dst . '" value="' . $item->id . '" />    
                    <small>Длительность:' . $this->format_seconds($item->billsec) . '</small><br/>';
                switch ($item->disposition) {
                    case "ANSWERED":
                        echo '<small style="color:#51a351;"><b>Статус: Ответили </b></small><br/></address>';
                        break;
                    case "BUSY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Занят </b></small><br/></address>';
                        break;
                    case "NO ANSWER":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Не ответили </b></small><br/></address>';
                        break;
                    case "CALL INTERCEPTION":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Перехваченный </b></small><br/></address>';
                        break;
                    case "ANSWER_BY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Переведенный </b></small><br/></address>';
                        break;
                    case "":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Разговор </b></small><br/></address>';
                        break;
                    case "FAILED":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Ошибка </b></small><br/></address>';
                        break;
                }
            }

            if ($item->dst === $user_phone) {
                $date = date_create($item->end, timezone_open("Europe/Moscow"));
                $formatted_date = date_format($date, "d.m.Y H:i:s");

                echo '<address style="background:#98FF98;">' . $this->moduleButton('records', $item->src, $user_phone, "incomming") . '
                    <small><strong>Входящий <i class="icon-arrow-left"></i></strong></small><br/>
                    <small>' . $formatted_date . '</small><br/>
                    <small>С номера:' . $item->src . '</small><br/>
                    <input type="hidden" id="phone_num_hide' . $item->src . '" value="' . $item->src . '" />
                    <input type="hidden" id="id_call' . $item->src . '" value="' . $item->id . '" />    
                    <small>Длительность:' . $this->format_seconds($item->billsec) . '</small><br/>';
                switch ($item->disposition) {
                    case "ANSWERED":
                        echo '<small style="color:#51a351;"><b>Статус: Ответили </b></small><br/></address>';
                        break;
                    case "BUSY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Пропущенный </b></small><br/></address>';
                        break;
                    case "NO ANSWER":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Пропущенный </b></small><br/></address>';
                        break;
                    case "CALL INTERCEPTION":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Перехваченный </b></small><br/></address>';
                        break;
                    case "ANSWER_BY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Переведенный </b></small><br/></address>';
                        break;
                    case "":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Разговор </b></small><br/></address>';
                        break;
                    case "FAILED":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Ошибка </b></small><br/></address>';
                        break;
                }
            }
            $pos = strripos($item->dst, "#");
            if ($pos === false) {
                $dst = $item->dst;
            } else {
                list($str, $shlak) = explode("#", $item->dst);
                $dst = $shlak;
            }

            if ($dst === $external_phone) {
                $date = date_create($item->end, timezone_open("Europe/Moscow"));
                $formatted_date = date_format($date, "d.m.Y H:i:s");

                echo '<address style="background:#98FF98;">' . $this->moduleButton('records', $item->src, $user_phone, "incomming") . '
                    <small><strong>Входящий <i class="icon-arrow-left"></i></strong></small><br/>
                    <small>' . $formatted_date . '</small><br/>
                    <small>С номера:' . $item->src . '</small><br/>
                    <input type="hidden" id="phone_num_hide' . $item->src . '" value="' . $item->src . '" />
                    <input type="hidden" id="id_call' . $item->src . '" value="' . $item->id . '" />    
                    <small>Длительность:' . $this->format_seconds($item->billsec) . '</small><br/>';
                switch ($item->disposition) {
                    case "ANSWERED":
                        echo '<small style="color:#51a351;"><b>Статус: Ответили </b></small><br/></address>';
                        break;
                    case "BUSY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Пропущенный </b></small><br/></address>';
                        break;
                    case "NO ANSWER":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Пропущенный </b></small><br/></address>';
                        break;
                    case "CALL INTERCEPTION":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Перехваченный </b></small><br/></address>';
                        break;
                    case "ANSWER_BY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Переведенный </b></small><br/></address>';
                        break;
                    case "":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Разговор </b></small><br/></address>';
                        break;
                    case "FAILED":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Ошибка </b></small><br/></address>';
                        break;
                }
            }
        }
        echo '</div>';
    }
    
    function reformatePhoneNumber($string) {

        if (strlen($string) == 12) {
            $str = substr($string, 2);
            return '7' . $str;
        }
        if (strlen($string) == 7) {
            $str = substr($string, 1);
            return '78452' . $str;
        }
        if (mb_strlen($string) === 6) {
            //$str = substr($string, 1);
            return '78452' . $string;
        }
        if (strlen($string) > 12 || strlen($string) == 2) {
            return $string;
        }
        if (strlen($string) == 11) {
            $str = substr($string, 1);
            return '7' . $str;
        }
    }
    
    
    function viewCallEventUniversal() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
            $this->load->model('core_model');
            $user_phone = $data['user']->phone;
            $external_phone = $data['user']->external_phone;
            $data = $this->core_model->getCallEvent($data['user']->phone, $data['user']->external_phone);
            
        }
        //return $data;
        echo '<div class="nav-header" style="color:#3a87ad;">История звонков</div>'
        . '<div style = "width: 200px; height: 370px; overflow: auto;" id = "scrollCall">';

        foreach ($data as $item) {
            $position = strripos($item->channel, 'SIP/' . $user_phone);
            if ($item->src === $user_phone || $position !== false) {
                $date = date_create($item->end, timezone_open("Europe/Moscow"));
                $formatted_date = date_format($date, "d.m.Y H:i:s");

                $pos = strripos($item->dst, "#");
                if ($pos === false) {
                    $dst = $item->dst;
                } else {
                    list($str, $shlak) = explode("#", $item->dst);
                    $dst = $shlak;
                }

                echo '<address style="background: #FFDB58;">' . $this->moduleButton('records', $dst, $user_phone, "outgoing") . '
                    <small><strong>Исходящий <i class="icon-arrow-right"></i></strong></small><br/>
                    <small>' . $formatted_date . '</small><br/>
                    <small>На номер:' . $this->reformatePhoneNumber($dst) . '</small><br/>
                    <input type="hidden" id="phone_num_hide' . $dst . '" value="' . $dst . '" />
                    <input type="hidden" id="id_call' . $dst . '" value="' . $item->id . '" />    
                    <small>Длительность:' . $this->format_seconds($item->billsec) . '</small><br/>';
                switch ($item->disposition) {
                    case "ANSWERED":
                        echo '<small style="color:#51a351;"><b>Статус: Ответили </b></small><br/></address>';
                        break;
                    case "BUSY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Занят </b></small><br/></address>';
                        break;
                    case "NO ANSWER":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Не ответили </b></small><br/></address>';
                        break;
                    case "CALL INTERCEPTION":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Перехваченный </b></small><br/></address>';
                        break;
                    case "ANSWER_BY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Переведенный </b></small><br/></address>';
                        break;
                    case "":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Разговор </b></small><br/></address>';
                        break;
                    case "FAILED":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Ошибка </b></small><br/></address>';
                        break;
                }
            }
            
            if ($item->dst === $user_phone) {
                $date = date_create($item->end, timezone_open("Europe/Moscow"));
                $formatted_date = date_format($date, "d.m.Y H:i:s");

                echo '<address style="background:#98FF98;">' . $this->moduleButton('records', $item->src, $user_phone, "incomming") . '
                    <small><strong>Входящий <i class="icon-arrow-left"></i></strong></small><br/>
                    <small>' . $formatted_date . '</small><br/>
                    <small>С номера:' . $this->reformatePhoneNumber($item->src) . '</small><br/>
                    <input type="hidden" id="phone_num_hide' . $this->reformatePhoneNumber($item->src) . '" value="' . $this->reformatePhoneNumber($item->src) . '" />
                    <input type="hidden" id="id_call' . $this->reformatePhoneNumber($item->src) . '" value="' . $item->id . '" />    
                    <small>Длительность:' . $this->format_seconds($item->billsec) . '</small><br/>';
                switch ($item->disposition) {
                    case "ANSWERED":
                        echo '<small style="color:#51a351;"><b>Статус: Ответили </b></small><br/></address>';
                        break;
                    case "BUSY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Пропущенный </b></small><br/></address>';
                        break;
                    case "NO ANSWER":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Пропущенный </b></small><br/></address>';
                        break;
                    case "CALL INTERCEPTION":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Перехваченный </b></small><br/></address>';
                        break;
                    case "ANSWER_BY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Переведенный </b></small><br/></address>';
                        break;
                    case "":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Разговор </b></small><br/></address>';
                        break;
                    case "FAILED":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Ошибка </b></small><br/></address>';
                        break;
                }
            }
            $pos = strripos($item->dst, "#");
            if ($pos === false) {
                $dst = $item->dst;
            } else {
                list($str, $shlak) = explode("#", $item->dst);
                $dst = $shlak;
            }

            if ($dst === $external_phone) {
                $date = date_create($item->end, timezone_open("Europe/Moscow"));
                $formatted_date = date_format($date, "d.m.Y H:i:s");

                echo '<address style="background:#98FF98;">' . $this->moduleButton('records', $item->src, $user_phone, "incomming") . '
                    <small><strong>Входящий <i class="icon-arrow-left"></i></strong></small><br/>
                    <small>' . $formatted_date . '</small><br/>
                    <small>С номера:' . $this->reformatePhoneNumber($item->src) . '</small><br/>
                    <input type="hidden" id="phone_num_hide' . $this->reformatePhoneNumber($item->src) . '" value="' . $this->reformatePhoneNumber($item->src) . '" />
                    <input type="hidden" id="id_call' . $this->reformatePhoneNumber($item->src) . '" value="' . $item->id . '" />    
                    <small>Длительность:' . $this->format_seconds($item->billsec) . '</small><br/>';
                switch ($item->disposition) {
                    case "ANSWERED":
                        echo '<small style="color:#51a351;"><b>Статус: Ответили </b></small><br/></address>';
                        break;
                    case "BUSY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Пропущенный </b></small><br/></address>';
                        break;
                    case "NO ANSWER":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Пропущенный </b></small><br/></address>';
                        break;
                    case "CALL INTERCEPTION":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Перехваченный </b></small><br/></address>';
                        break;
                    case "ANSWER_BY":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Переведенный </b></small><br/></address>';
                        break;
                    case "":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Разговор </b></small><br/></address>';
                        break;
                    case "FAILED":
                        echo '<small style="color:#ee5f5b;"><b>Статус: Ошибка </b></small><br/></address>';
                        break;
                }
            }
        }
        echo '</div>';
    }

    function getContactDetail() {

        $phone_number = $this->input->post('phone_number');

        $this->load->model('core_model');
        $contactDetail = $this->core_model->getContactDetail($phone_number);

        return $contactDetail;
    }

    function installModule($moduleName) {
        $this->load->model('core_model');
        $this->load->helper('file');

        $confModule = simplexml_load_file(APPPATH . 'modules/' . $moduleName . '/configDescription.xml');
        echo "Check setup " . $confModule->plugin_name;
        $res = $this->core_model->checkModuleInstallBefore($confModule->plugin_system_name);

        if ($res === 'YES') {
            //INSERT TO DB
            //echo "Setup " . $confModule->moduleName . " Proccessing ...";
            $this->core_model->installModule($confModule);
            if ($confModule->plugin_sql_file !== "") {
                //$dbPluginStructure = simplexml_load_file(APPPATH . 'modules/' . $moduleName . '/configDescription.xml');
                //echo "Тут апдейтим базу данных";
                $command = "mysql -uroot -p11235813 sprint_db < /home/denic/server/sprint.crm64.ru/" . APPPATH . 'modules/' . $moduleName . '/backup.sql';
                echo $command;
                //exec($command);
            }
        } else {
            echo "Модуль установлен ранее.";
        }
    }

    function checkModuleStatus($moduleName) {
        $this->load->model('core_model');
        return $this->core_model->checkModuleStatus($moduleName);
    }

    function moduleButton($moduleName, $phone_numb,$phone,$phone_type) {
        
        $clear_phone_number = $this->reformatePhoneNumber($phone_numb);

        if ($this->checkModuleStatus($moduleName) === 'YES') {
            if($phone_type === "outgoing"){
            return '<div class="btn-group pull-right">
            <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li><a tabindex="-1" href="#" onclick="addRecord(' . $clear_phone_number . '); return false;"><i class="icon-tasks"></i>Добавить запись</a></li>
            <li><a tabindex="-1" href="#" onclick="getFullClientInfo(' . $phone_numb . '); return false;"><i class="icon-info-sign"></i>Информация</a></li>
            <li><a tabindex="-1" href="#" onclick=prepareOriginateCall('.$phone.','.$phone.','.$phone_numb.',"outgoing"); return false;><i class="icon-phone"></i>Перезвонить</a></li>
          </ul>
        </div>';
    }else{
        return '<div class="btn-group pull-right">
            <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li><a tabindex="-1" href="#" onclick="addRecord(' . $clear_phone_number . '); return false;"><i class="icon-tasks"></i>Добавить запись</a></li>
            <li><a tabindex="-1" href="#" onclick="getFullClientInfo(' . $phone_numb . '); return false;"><i class="icon-info-sign"></i>Информация</a></li>
            <li><a tabindex="-1" href="#" onclick=prepareOriginateCall('.$phone.','.$phone_numb.','.$phone.',"incomming"); return false;><i class="icon-phone"></i>Перезвонить</a></li>
          </ul>
        </div>';
    }
//            return '<a class="btn btn-danger btn-mini pull-right" href="#" onclick="addRecord(' . $phone_numb . '); return false;" role="button"><i class="icon-plus-sign"></i></a>'
//                    . '<a class="btn btn-info btn-mini pull-right" href="#" onclick="getFullClientInfo(' . $phone_numb . '); return false;" role="button"><i class="icon-info-sign"></i></a>';
        }
    }

    function getUserParamsByID() {

        $id = $this->input->post('user_id');

        $this->load->model('core_model');
        echo json_encode($this->core_model->getCRMUserById($id));
    }

    public function sendReminderLetter() {

        $address = $this->input->post('address');
        $msg = $this->input->post('msg');

        $this->load->library('PHPMailer');

        $mail = new PHPMailer(true);

        $mail->IsSMTP(); // we are going to use SMTP
        $mail->SMTPAuth = true; // enabled SMTP authentication
        //$mail->SMTPSecure = "ssl";  // prefix for secure protocol to connect to the server

        $mail->Host = 'smtp.crm64.ru';
        $mail->Port = '25';
        $mail->Username = 'notify@crm64.ru';
        $mail->Password = '5d6773Pf3';
        $mail->CharSet = 'utf-8';
        $mail->Subject = 'Оповещение';
        $mail->SetFrom('notify@crm64.ru', 'Office CRM System');

        $mail->AddAddress($address);
        $mail->MsgHTML($msg);


        $mail->Send();
    }

    function originateCall() {
        $strHost = "91.196.5.133";
        //адрес сервера asterisk
        $strUser = "admin2";
        //логин для подключения к ami
        $strSecret = "admin2";
        //пароль кот. Указали в manager.conf
        $strChannel = "SIP/" . $this->input->post('internalNumb');
        /*         * канал с которого будет создаваться звонок,
         * тут можно по разному написать, если у вас всего один оператор
         * будет совершать звонок то можно просто указать SIP/1234,
         * но в таком случае вы не сможете дополнительно обрабатывать звонок,
         * удобнее указать did (номер назначения) и контекст, в моём примере контекст
         * callback а номер 1234
         */
        $strContext = "from-internal";
        //контекст в которому будет совершаться исходящий звонок
        $strWaitTime = "30";
        $strPriority = "1";
        $strMaxRetry = "2";

        $strExten = $this->formatPhoneNumber($this->input->post('originateDst'));
        #specify the caller id for the call
        $strCallerId = "Call From CRM<$strExten>";
        //$length = strlen($strExten);


        $oSocket = fsockopen($strHost, 5038, $errnum, $errdesc) or die("Connection to host failed");
        fputs($oSocket, "Action: login\r\n");
        fputs($oSocket, "Events: off\r\n");
        fputs($oSocket, "Username: $strUser\r\n");
        fputs($oSocket, "Secret: $strSecret\r\n\r\n");
        fputs($oSocket, "Action: originate\r\n");
        fputs($oSocket, "Channel: $strChannel\r\n");
        fputs($oSocket, "WaitTime: $strWaitTime\r\n");
        fputs($oSocket, "CallerId: $strCallerId\r\n");
        fputs($oSocket, "Exten: $strExten\r\n");
        fputs($oSocket, "Context: $strContext\r\n");
        fputs($oSocket, "Priority: $strPriority\r\n\r\n");
        fputs($oSocket, "Action: Logoff\r\n\r\n");
        sleep(1);
        fclose($oSocket);

        //print_r($oSocket);
    }

    function formatPhoneNumber($string) {

        if (mb_strlen($string) === 6) {
            //$str = substr($string, 1);
            return '78452' . $string;
        }

        if (strlen($string) == 11) {
            $str = substr($string, 1);
            return '7' . $str;
        }

        if (mb_strlen($string) < 6 || mb_strlen($string) > 11) {
            return $string;
        }
        
    }

}
