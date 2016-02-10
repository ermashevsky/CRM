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

    function getAllCall($phone_number, $external_phone) {
        $results = array();

        $res = $this->db->query("SELECT `id` ,  `src` ,  `dst` ,  `channel`, `dstchannel`, `start` ,  `answer` ,  `end` ,  `billsec` ,  `disposition` ,  `uniqueid` ,  `cause` 
            FROM  `cdr` 
            WHERE  `end` 
            BETWEEN  '" . date('Y-m-d 00:00:00') . "'
            AND  '" . date('Y-m-d 23:59:59') . "'
            AND (
             `channel` like  '%/" . $phone_number . "%'
            OR  `dstchannel` like  '%/" . $phone_number . "%'
            OR 
             `src` like  '%" . $external_phone . "%'
            OR  `dst` like  '%" . $external_phone . "%'
            )
            order by `end` asc");

        //$res = $this->db->get();
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Allcalls_model();
                $tmp->id = $row->id;
                $tmp->uniqueid = $row->uniqueid;
                $pos23string = strripos($row->src, "%23");

                if ($pos23string !== false) {
                    list($str, $shlak) = explode("%23", $row->src);
                    $tmp->src = $this->reformatePhoneNumber($str);
                } else {
                    $tmp->src = $this->reformatePhoneNumber($row->src);
                }
                //$tmp->src = $this->reformatePhoneNumber($row->src);
                $tmp->channel = $row->channel;
                $tmp->dstchannel = $row->dstchannel;
                $tmp->start = $row->start;
                $tmp->answer = $row->answer;
                $tmp->end = $row->end;
                $tmp->billsec = $row->billsec;
                $tmp->disposition = $row->disposition;
                $tmp->cause = $row->cause;

                $pos = strripos($row->dst, "#");

                if ($pos !== false) {
                    list($str, $shlak) = explode("#", $row->dst);
                    $tmp->dst = $shlak;
                } else {
                    $tmp->dst = $this->removePrefixMera($row->dst);
                    //тута отсекаем mera/
                }

                $results[$tmp->id] = $tmp;
            }
        } else {
            $res = $this->db->query("SELECT `id` , `channel`, `dstchannel`, `src` ,  `dst` ,  `start` ,  `answer` ,  `end` ,  `billsec` ,  `disposition` ,  `uniqueid` ,  `cause` 
            FROM  `cdr` 
            WHERE  `end` 
            BETWEEN  '" . date('Y-m-d 00:00:00') . "'
            AND  '" . date('Y-m-d 23:59:59') . "'
            AND (
             `src` like  '%" . $external_phone . "%'
            OR  `dst` like  '%" . $external_phone . "%'
            OR  `channel` like  '%/" . $phone_number . "%'
            OR  `dstchannel` like  '%/" . $phone_number . "%'
            )
            order by `end` asc");

            //$res = $this->db->get();
            if (0 < $res->num_rows) {
                foreach ($res->result() as $row) {
                    $tmp = new Allcalls_model();
                    $tmp->id = $row->id;
                    $tmp->uniqueid = $row->uniqueid;
                    $tmp->channel = $this->channel;
                    $tmp->dstchannel = $this->dstchannel;
                    $pos23string2 = strripos($row->src, "%23");

                    if ($pos23string2 !== false) {
                        list($str, $shlak) = explode("%23", $row->src);
                        $tmp->src = $this->reformatePhoneNumber($str);
                    } else {
                        $tmp->src = $this->reformatePhoneNumber($row->src);
                    }
                    $tmp->start = $row->start;
                    $tmp->answer = $row->answer;
                    $tmp->end = $row->end;
                    $tmp->billsec = $row->billsec;
                    $tmp->disposition = $row->disposition;
                    $tmp->cause = $row->cause;

                    $pos = strripos($row->dst, "#");

                    if ($pos !== false) {
                        list($str, $shlak) = explode("#", $row->dst);
                        $tmp->dst = $shlak;
                    } else {
                        $tmp->dst = $this->removePrefixMera($row->dst);
                    }

                    $results[$tmp->id] = $tmp;
                }
            }
        }
        return $results;
    }

    function getAllOrganizationCall($phone_number, $external_phone, $phone_organization_list) {
        $results = array();

        $res = $this->db->query("SELECT `id` ,  `src` ,  `dst` ,  `channel`, `dstchannel`, `start` ,  `answer` ,  `end` ,  `billsec` ,  `disposition` ,  `uniqueid` ,  `cause` 
            FROM  `cdr` 
            WHERE  `end` 
            BETWEEN  '" . date('Y-m-d 00:00:00') . "'
            AND  '" . date('Y-m-d 23:59:59') . "'
            AND (
             `channel` like  '%/" . $phone_number . "%'
            OR  `dstchannel` like  '%/" . $phone_number . "%'
            OR 
             `src` like  '%" . $external_phone . "%'
            OR  `dst` like  '%" . $external_phone . "%'
            )
            AND (
            `channel` REGEXP  '".$phone_organization_list."'
            OR  `dstchannel` REGEXP  '".$phone_organization_list."'
            OR  `src` REGEXP  '".$phone_organization_list."'
            OR  `dst` REGEXP  '".$phone_organization_list."'
            )
            order by `end` asc");

        //$res = $this->db->get();
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Allcalls_model();
                $tmp->id = $row->id;
                $tmp->uniqueid = $row->uniqueid;
                $pos23string = strripos($row->src, "%23");

                if ($pos23string !== false) {
                    list($str, $shlak) = explode("%23", $row->src);
                    $tmp->src = $this->reformatePhoneNumber($str);
                } else {
                    $tmp->src = $this->reformatePhoneNumber($row->src);
                }
                //$tmp->src = $this->reformatePhoneNumber($row->src);
                $tmp->channel = $row->channel;
                $tmp->dstchannel = $row->dstchannel;
                $tmp->start = $row->start;
                $tmp->answer = $row->answer;
                $tmp->end = $row->end;
                $tmp->billsec = $row->billsec;
                $tmp->disposition = $row->disposition;
                $tmp->cause = $row->cause;

                $pos = strripos($row->dst, "#");

                if ($pos !== false) {
                    list($str, $shlak) = explode("#", $row->dst);
                    $tmp->dst = $shlak;
                } else {
                    $tmp->dst = $this->removePrefixMera($row->dst);
                    //тута отсекаем mera/
                }

                $results[$tmp->id] = $tmp;
            }
        } else {
            $res = $this->db->query("SELECT `id` , `channel`, `dstchannel`, `src` ,  `dst` ,  `start` ,  `answer` ,  `end` ,  `billsec` ,  `disposition` ,  `uniqueid` ,  `cause` 
            FROM  `cdr` 
            WHERE  `end` 
            BETWEEN  '" . date('Y-m-d 00:00:00') . "'
            AND  '" . date('Y-m-d 23:59:59') . "'
            AND (
             `src` like  '%" . $external_phone . "%'
            OR  `dst` like  '%" . $external_phone . "%'
            OR  `channel` like  '%/" . $phone_number . "%'
            OR  `dstchannel` like  '%/" . $phone_number . "%'
            )
            AND (`channel` REGEXP  '".$phone_organization_list."'
            OR  `dstchannel`REGEXP  '".$phone_organization_list."'
            OR  `src` REGEXP  '".$phone_organization_list."'
            OR  `dst` REGEXP  '".$phone_organization_list."'
            )
            order by `end` asc");

            //$res = $this->db->get();
            if (0 < $res->num_rows) {
                foreach ($res->result() as $row) {
                    $tmp = new Allcalls_model();
                    $tmp->id = $row->id;
                    $tmp->uniqueid = $row->uniqueid;
                    $tmp->channel = $this->channel;
                    $tmp->dstchannel = $this->dstchannel;
                    $pos23string2 = strripos($row->src, "%23");

                    if ($pos23string2 !== false) {
                        list($str, $shlak) = explode("%23", $row->src);
                        $tmp->src = $this->reformatePhoneNumber($str);
                    } else {
                        $tmp->src = $this->reformatePhoneNumber($row->src);
                    }
                    $tmp->start = $row->start;
                    $tmp->answer = $row->answer;
                    $tmp->end = $row->end;
                    $tmp->billsec = $row->billsec;
                    $tmp->disposition = $row->disposition;
                    $tmp->cause = $row->cause;

                    $pos = strripos($row->dst, "#");

                    if ($pos !== false) {
                        list($str, $shlak) = explode("#", $row->dst);
                        $tmp->dst = $shlak;
                    } else {
                        $tmp->dst = $this->removePrefixMera($row->dst);
                    }

                    $results[$tmp->id] = $tmp;
                }
            }
        }
        return $results;
    }

    function getOrganizationPhoneList($id) {

        $res = $this->db->query("SELECT GROUP_CONCAT( CONCAT_WS( ' ',  `contacts`.`mobile_number` ,  `contacts`.`private_phone_number` ,  `organization`.`phone_number` ) 
        SEPARATOR  ' ' ) AS m_num
        FROM  `organization` 
        LEFT JOIN  `contacts` ON  `contacts`.`organization_id` =  `organization`.`id` 
        WHERE  `organization`.`id` =" . $id);

        //$res = $this->db->get();
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $string = preg_replace("/\s{2,}/"," ",$row->m_num);
                return str_replace(' ','|', $string);
            }
        }
    }

    function cutString($text) {
        return substr($text, 0, strpos($text, '%23'));
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

    function saveToDb($date_time, $call_type, $src, $dst, $duration, $status, $contact_src, $contact_dst, $user_id) {

        $data = array(
            'date_time' => $date_time,
            'call_type' => $call_type,
            'src' => $src,
            'dst' => $dst,
            'duration' => $duration,
            'status' => $status,
            'contact_src' => $contact_src,
            'contact_dst' => $contact_dst,
            'user_id' => $user_id
        );

        $this->db->insert('datatoxls', $data);
    }

    function truncateTable($user_id) {
        //$this->db->truncate('datatoxls');
        $this->db->delete('datatoxls', array('user_id' => $user_id));
    }

    function saveToXLS($user_id) {
        $results = array();


        $this->db->select("*");
        $this->db->from('datatoxls');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('datatoxls.date_time', 'desc');

        $res = $this->db->get();
        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = array();
                $tmp['id'] = $row->id;
                $tmp['date_time'] = date('d.m.Y H:i:s', strtotime($row->date_time));
                $tmp['call_type'] = $row->call_type;
                $tmp['src'] = $row->src;
                $tmp['contact_src'] = $row->contact_src;
                $tmp['dst'] = $row->dst;
                $tmp['contact_dst'] = $row->contact_dst;
                $tmp['duration'] = $row->duration;
                $tmp['status'] = $row->status;

                $results[$row->id] = $tmp;
            }
        }
        return $results;
    }

    function getRowsCountXLSTable() {

        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();

        $this->db->from('datatoxls');
        $this->db->where('user_id', $data['user']->id);
        return $this->db->count_all_results();
    }

    function getFilteredCalls($date_time, $date_time2, $src, $dst, $status_call, $type_call, $user_phone_number, $phone_number) {
        $results = array();

        if ($type_call === 'allcall' && $status_call === 'all_status') {

            $res = $this->db->query("SELECT `id` ,  `src` ,  `dst` ,  `start` ,  `answer` ,  `end` ,  `billsec` ,  `disposition` ,  `uniqueid` ,  `cause` 
            FROM  `cdr` 
            WHERE  `end` 
            BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
            AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
            AND (
             `src` =  '" . $user_phone_number . "'
            OR  `dst` =  '" . $user_phone_number . "'
            )
            AND (
             `src` like  '%" . $phone_number . "%'
            OR  `dst` like  '%" . $phone_number . "%'
            )
            order by `end` asc");
        }
        if ($status_call !== 'all_status' && $type_call === 'allcall') {

            $res = $this->db->query("SELECT `id` ,  `src` ,  `dst` ,  `start` ,  `answer` ,  `end` ,  `billsec` ,  `disposition` ,  `uniqueid` ,  `cause` 
            FROM  `cdr` 
            WHERE  `end` 
            BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
            AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
            AND (
             `src` =  '" . $user_phone_number . "'
            OR  `dst` =  '" . $user_phone_number . "'
            )
            AND (
             `src` like  '%" . $phone_number . "%'
            OR  `dst` like  '%" . $phone_number . "%'
            )
            AND `disposition` = '" . $status_call . "'
            order by `end` asc");
        }

        if ($status_call !== 'all_status' && $type_call !== 'allcall') {

            $res = $this->db->query("SELECT `id` ,  `src` ,  `dst` ,  `start` ,  `answer` ,  `end` ,  `billsec` ,  `disposition` ,  `uniqueid` ,  `cause` 
            FROM  `cdr` 
            WHERE  `end` 
            BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
            AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
            AND `src` like  '%" . $src . "%'
            AND `dst` like  '%" . $dst . "%'
            AND `disposition` = '" . $status_call . "'
            order by `end` asc");
        }
        if ($status_call === 'all_status' && $type_call !== 'allcall') {

            $res = $this->db->query("SELECT `id` ,  `src` ,  `dst` ,  `start` ,  `answer` ,  `end` ,  `billsec` ,  `disposition` ,  `uniqueid` ,  `cause` 
            FROM  `cdr` 
            WHERE  `end` 
            BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
            AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
            AND `src` like  '%" . $src . "%'
            AND `dst` like  '%" . $dst . "%'
            order by `end` asc");
        }
        //$res = $this->db->get();

        if (0 < $res->num_rows) {
            foreach ($res->result() as $row) {
                $tmp = new Allcalls_model();
                $tmp->id = $row->id;
                $tmp->uniqueid = $row->uniqueid;
                $tmp->src = $this->reformatePhoneNumber($row->src);
                $tmp->start = $row->start;
                $tmp->answer = $row->answer;
                $tmp->end = $row->end;
                $tmp->billsec = $this->format_seconds($row->billsec);
                $tmp->disposition = $this->translateDisposition($row->disposition);
                $tmp->cause = $row->cause;
                $tmp->btn_group = '<div class="btn-group">
                        <a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" title="Добавить контакт" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow"  title="Добавить запись" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>';
                $pos = strripos($row->dst, "#");
                if ($pos === false) {
                    $tmp->dst = $this->reformatePhoneNumber($row->dst);
                } else {
                    list($str, $shlak) = explode("#", $row->dst);
                    $tmp->dst = $this->reformatePhoneNumber($shlak);
                }

                $results[$tmp->id] = $tmp;
            }
        }
        return $results;
    }

    function getFilteredCalls2($date_time, $date_time2, $src, $dst, $status_call, $type_call, $user_phone_number, $phone_number, $phone_number2, $duration, $select_duration_value, $condition) {

        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        $results = array();

        if ($status_call !== 'all_status') {
            $disposition = "AND `disposition` = '" . $status_call . "'";
        } else {
            $disposition = "";
        }

        if ($duration == 0) {
            $duration_condition = "";
        } else {
            if ($select_duration_value === "less") {
                $duration_condition = "AND `billsec` <= '$duration'";
            } else {
                $duration_condition = "AND `billsec` >= '$duration'";
            }
        }

        switch ($condition) {
            case "1-3":

                $res = $this->db->query("SELECT id, src, dst, start, answer, end, billsec, disposition, uniqueid,  cause
            FROM  cdr 
            WHERE  end
            BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
            AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
            AND src like '%" . $src . "%'
            AND dst like '%" . $dst . "%'
            " . $disposition . "
            " . $duration_condition . "
            order by end asc");

                if (0 < $res->num_rows) {
                    foreach ($res->result() as $row) {
                        $tmp = new Allcalls_model();
                        $tmp->id = $row->id;
                        $tmp->uniqueid = $row->uniqueid;
                        $tmp->src = $this->reformatePhoneNumber($row->src);
                        $tmp->src_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($this->reformatePhoneNumber($row->src)) . '</span>';
                        $tmp->start = $row->start;
                        $tmp->answer = $row->answer;
                        $tmp->end = $row->end;
                        $tmp->billsec = $this->format_seconds($row->billsec);
                        $tmp->disposition = $this->translateDisposition($row->disposition);
                        $tmp->cause = $row->cause;
                        $tmp->btn_group = '
                        <a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" title="Добавить контакт" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow"  title="Добавить запись" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>';
                        $pos = strripos($row->dst, "#");
                        if ($pos === false) {
                            $tmp->dst = $this->removePrefixMera($row->dst);
                            $tmp->dst_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($this->removePrefixMera($row->dst)) . '</span>';
                        } else {
                            list($str, $shlak) = explode("#", $row->dst);
                            $tmp->dst = $shlak;
                            $tmp->dst_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($shlak) . '</span>';
                        }

                        $results[$tmp->id] = $tmp;
                    }
                }
                break;
            case "2-4":

                if (!empty($src)) {

                    $res = $this->db->query("SELECT id, src, dst, start, answer, end, billsec, disposition, uniqueid,  cause
                FROM  cdr 
                WHERE  end
                BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
                AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
                AND src like '%" . $src . "%'
                " . $disposition . "
                " . $duration_condition . "
                order by end asc");
                } else {

                    $res = $this->db->query("SELECT id, src, dst, start, answer, end, billsec, disposition, uniqueid,  cause
                FROM  cdr 
                WHERE  end
                BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
                AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
                AND dst like '%" . $dst . "%'
                " . $disposition . "
                order by end asc");
                }

                if (0 < $res->num_rows) {
                    foreach ($res->result() as $row) {
                        $tmp = new Allcalls_model();
                        $tmp->id = $row->id;
                        $tmp->uniqueid = $row->uniqueid;
                        $tmp->src = $this->reformatePhoneNumber($row->src);
                        $tmp->src_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($this->reformatePhoneNumber($row->src)) . '</span>';
                        $tmp->start = $row->start;
                        $tmp->answer = $row->answer;
                        $tmp->end = $row->end;
                        $tmp->billsec = $this->format_seconds($row->billsec);
                        $tmp->disposition = $this->translateDisposition($row->disposition);
                        $tmp->cause = $row->cause;
                        $tmp->btn_group = '
                        <a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" title="Добавить контакт" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow"  title="Добавить запись" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>';
                        $pos = strripos($row->dst, "#");
                        if ($pos === false) {
                            $tmp->dst = $this->removePrefixMera($row->dst);
                            $tmp->dst_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($this->removePrefixMera($row->dst)) . '</span>';
                        } else {
                            list($str, $shlak) = explode("#", $row->dst);
                            $tmp->dst = $shlak;
                            $tmp->dst_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($shlak) . '</span>';
                        }

                        $results[$tmp->id] = $tmp;
                    }
                }
                break;
            case "5":

                $res = $this->db->query("SELECT id, src, dst, start, answer, end, billsec, disposition, uniqueid,  cause
            FROM  cdr 
            WHERE  end
            BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
            AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
            AND `src` like  '%" . $phone_number . "%'
            AND  `dst` like  '%" . $phone_number2 . "%'
            " . $duration_condition . "
            OR
            (
            end BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
            AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
            AND `src` like  '%" . $phone_number2 . "%'
            AND  `dst` like  '%" . $phone_number . "%'
            " . $duration_condition . "
            )
            order by end asc");

                if (0 < $res->num_rows) {
                    foreach ($res->result() as $row) {
                        $tmp = new Allcalls_model();
                        $tmp->id = $row->id;
                        $tmp->uniqueid = $row->uniqueid;
                        $tmp->src = $this->reformatePhoneNumber($row->src);
                        $tmp->src_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($this->reformatePhoneNumber($row->src)) . '</span>';
                        $tmp->start = $row->start;
                        $tmp->answer = $row->answer;
                        $tmp->end = $row->end;
                        $tmp->billsec = $this->format_seconds($row->billsec);
                        $tmp->disposition = $this->translateDisposition($row->disposition);
                        $tmp->cause = $row->cause;
                        $tmp->btn_group = '
                        <a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" title="Добавить контакт" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow"  title="Добавить запись" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>';
                        $pos = strripos($row->dst, "#");
                        if ($pos === false) {
                            $tmp->dst = $this->removePrefixMera($row->dst);
                            $tmp->dst_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($this->removePrefixMera($row->dst)) . '</span>';
                        } else {
                            list($str, $shlak) = explode("#", $row->dst);
                            $tmp->dst = $shlak;
                            $tmp->dst_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($shlak) . '</span>';
                        }

                        $results[$tmp->id] = $tmp;
                    }
                }
                break;
            case "6":

                $res = $this->db->query("SELECT id, src, dst, start, answer, end, billsec, disposition, uniqueid,  cause
                    FROM  cdr 
                    WHERE  end
                    BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
                    AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
                    AND (
                    `src` like  '%" . $phone_number . "%'
                    OR  `dst` like  '%" . $phone_number . "%'
                    )
                    " . $disposition . "
                    " . $duration_condition . "
                    order by end asc");

                if (0 < $res->num_rows) {
                    foreach ($res->result() as $row) {
                        $tmp = new Allcalls_model();
                        $tmp->id = $row->id;
                        $tmp->uniqueid = $row->uniqueid;
                        $tmp->src = $this->reformatePhoneNumber($row->src);
                        $tmp->src_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($this->reformatePhoneNumber($row->src)) . '</span>';
                        $tmp->start = $row->start;
                        $tmp->answer = $row->answer;
                        $tmp->end = $row->end;
                        $tmp->billsec = $this->format_seconds($row->billsec);
                        $tmp->disposition = $this->translateDisposition($row->disposition);
                        $tmp->cause = $row->cause;
                        $tmp->btn_group = '
                        <a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" title="Добавить контакт" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow"  title="Добавить запись" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>';
                        $pos = strripos($row->dst, "#");
                        if ($pos === false) {
                            $tmp->dst = $this->removePrefixMera($row->dst);
                            $tmp->dst_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($this->removePrefixMera($row->dst)) . '</span>';
                        } else {
                            list($str, $shlak) = explode("#", $row->dst);
                            $tmp->dst = $shlak;
                            $tmp->dst_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($shlak) . '</span>';
                        }

                        $results[$tmp->id] = $tmp;
                    }
                }
                break;
        }
        return $results;
    }

    function getFilteredCalls3($date_time, $date_time2, $src, $dst, $status_call, $type_call, $user_phone_number, $phone_number, $phone_number2, $duration, $select_duration_value, $condition) {

        $data['user'] = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        $results = array();

        if ($status_call !== 'all_status') {
            $disposition = "AND `disposition` = '" . $status_call . "'";
        } else {
            $disposition = "";
        }

        if ($duration == 0) {
            $duration_condition = "";
        } else {
            if ($select_duration_value === "less") {
                $duration_condition = "AND `billsec` <= '$duration'";
            } else {
                $duration_condition = "AND `billsec` >= '$duration'";
            }
        }

        switch ($condition) {
            case "1-3":

                $res = $this->db->query("SELECT id, channel, dstchannel, src, dst, start, answer, end, billsec, disposition, uniqueid,  cause
            FROM  cdr 
            WHERE  end
            BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
            AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
            AND (src like '%" . $src . "%'
            AND dst like '%" . $dst . "%')
            OR end
            BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
            AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
            AND src like '%" . substr($src, -6) . "%'
            AND dst like '%" . $dst . "%'
            " . $disposition . "
            " . $duration_condition . "
            order by end asc");

                if (0 < $res->num_rows) {
                    foreach ($res->result() as $row) {
                        $tmp = new Allcalls_model();
                        $tmp->id = $row->id;
                        $tmp->uniqueid = $row->uniqueid;
                        $tmp->channel = $this->cropString($row->channel);
                        $tmp->dstchannel = $this->cropString($row->dstchannel);
                        $tmp->src = $this->reformatePhoneNumber($row->src);
                        $tmp->src_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($this->reformatePhoneNumber($row->src)) . '</span>';
                        $tmp->start = $row->start;
                        $tmp->answer = $row->answer;
                        $tmp->end = $row->end;
                        $tmp->billsec = $this->format_seconds($row->billsec);
                        $tmp->disposition = $this->translateDisposition($row->disposition);
                        $tmp->cause = $row->cause;
//                        $tmp->btn_group = '
//                        <a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
//                        <a href="#" title="Добавить контакт" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>
//                        <a href="#taskWindow"  title="Добавить запись" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a>
//                    </div>';
                        $pos = strripos($row->dst, "#");
                        if ($pos === false) {
                            $tmp->dst = $this->removePrefixMera($row->dst);
                            $tmp->dst_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($this->removePrefixMera($row->dst)) . '</span>';
                        } else {
                            list($str, $shlak) = explode("#", $row->dst);
                            $tmp->dst = $shlak;
                            $tmp->dst_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($shlak) . '</span>';
                        }

                        $results[$tmp->id] = $tmp;
                    }
                }
                break;
            case "2-4":

                if (!empty($src)) {

                    $res = $this->db->query("SELECT id, channel, dstchannel, src, dst, start, answer, end, billsec, disposition, uniqueid,  cause
                FROM  cdr 
                WHERE  end
                BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
                AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
                AND (channel like '%" . $src . "%'
                OR src like '%" . $src . "%')
                " . $disposition . "
                " . $duration_condition . "
                order by end asc");
                } else {

                    $res = $this->db->query("SELECT id, channel, dstchannel, src, dst, start, answer, end, billsec, disposition, uniqueid,  cause
                FROM  cdr 
                WHERE  end
                BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
                AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
                AND (dstchannel like '%" . $dst . "%'
                OR dst like '%" . $dst . "%')
                " . $disposition . "
                " . $duration_condition . "
                order by end asc");
                }

                if (0 < $res->num_rows) {
                    foreach ($res->result() as $row) {
                        $tmp = new Allcalls_model();
                        $tmp->id = $row->id;
                        $tmp->uniqueid = $row->uniqueid;
                        $tmp->channel = $this->cropString($row->channel);
                        $tmp->dstchannel = $this->cropString($row->dstchannel);
                        $tmp->src = $this->reformatePhoneNumber($row->src);
                        $tmp->src_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($this->reformatePhoneNumber($row->src)) . '</span>';
                        $tmp->start = $row->start;
                        $tmp->answer = $row->answer;
                        $tmp->end = $row->end;
                        $tmp->billsec = $this->format_seconds($row->billsec);
                        $tmp->disposition = $this->translateDisposition($row->disposition);
                        $tmp->cause = $row->cause;
//                        $tmp->btn_group = '
//                        <a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
//                        <a href="#" title="Добавить контакт" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>
//                        <a href="#taskWindow"  title="Добавить запись" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a>
//                    </div>';
                        $pos = strripos($row->dst, "#");
                        if ($pos === false) {
                            $tmp->dst = $this->removePrefixMera($row->dst);
                            $tmp->dst_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($this->removePrefixMera($row->dst)) . '</span>';
                        } else {
                            list($str, $shlak) = explode("#", $row->dst);
                            $tmp->dst = $shlak;
                            $tmp->dst_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($shlak) . '</span>';
                        }

                        $results[$tmp->id] = $tmp;
                    }
                }
                break;
            case "5":

                $res = $this->db->query("SELECT id, channel, dstchannel, src, dst, start, answer, end, billsec, disposition, uniqueid,  cause
            FROM  cdr 
            WHERE  end
            BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
            AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
            AND (`src` like  '%" . $phone_number2 . "%'
            OR  `dst` like  '%" . $phone_number2 . "%')
            AND
            (
            `channel` like  '%" . $phone_number . "%'
            OR  `dstchannel` like  '%" . $phone_number . "%'
            )
            " . $duration_condition . "
            OR (end
            BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
            AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "')
            AND  (`src` like  '%" . substr($phone_number2, -6) . "%'
            OR  `dst` like  '%" . substr($phone_number2, -6) . "%')
            AND
            (
            `channel` like  '%" . $phone_number . "%'
            OR  `dstchannel` like  '%" . $phone_number . "%'
            )   
            " . $duration_condition . "
            order by end asc");

                if (0 < $res->num_rows) {
                    foreach ($res->result() as $row) {
                        $tmp = new Allcalls_model();
                        $tmp->id = $row->id;
                        $tmp->uniqueid = $row->uniqueid;
                        $tmp->channel = $this->cropString($row->channel);
                        $tmp->dstchannel = $this->cropString($row->dstchannel);
                        $tmp->src = $this->reformatePhoneNumber($row->src);
                        $tmp->src_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($this->reformatePhoneNumber($row->src)) . '</span>';
                        $tmp->start = $row->start;
                        $tmp->answer = $row->answer;
                        $tmp->end = $row->end;
                        $tmp->billsec = $this->format_seconds($row->billsec);
                        $tmp->disposition = $this->translateDisposition($row->disposition);
                        $tmp->cause = $row->cause;
//                        $tmp->btn_group = '
//                        <a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
//                        <a href="#" title="Добавить контакт" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>
//                        <a href="#taskWindow"  title="Добавить запись" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a>
//                    </div>';
                        $pos = strripos($row->dst, "#");
                        if ($pos === false) {
                            $tmp->dst = $this->removePrefixMera($row->dst);
                            $tmp->dst_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($this->removePrefixMera($row->dst)) . '</span>';
                        } else {
                            list($str, $shlak) = explode("#", $row->dst);
                            $tmp->dst = $shlak;
                            $tmp->dst_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($shlak) . '</span>';
                        }

                        $results[$tmp->id] = $tmp;
                    }
                }
                break;
            case "6":

                $res = $this->db->query("SELECT id, channel,dstchannel, src, dst, start, answer, end, billsec, disposition, uniqueid,  cause
                    FROM  cdr 
                    WHERE  end
                    BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
                    AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
                    AND (
                        `channel` like  '%" . $phone_number . "%'
                            OR  `dstchannel` like  '%" . $phone_number . "%'
                    )
                    " . $disposition . "
                    " . $duration_condition . "
                    order by end asc");

                if (0 < $res->num_rows) {
                    foreach ($res->result() as $row) {
                        $tmp = new Allcalls_model();
                        $tmp->id = $row->id;
                        $tmp->uniqueid = $row->uniqueid;
                        $tmp->src = $this->reformatePhoneNumber($row->src);
                        $tmp->src_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($this->reformatePhoneNumber($row->src)) . '</span>';
                        $tmp->channel = $this->cropString($row->channel);
                        $tmp->dstchannel = $this->cropString($row->dstchannel);
                        $tmp->start = $row->start;
                        $tmp->answer = $row->answer;
                        $tmp->end = $row->end;
                        $tmp->billsec = $this->format_seconds($row->billsec);
                        $tmp->disposition = $this->translateDisposition($row->disposition);
                        $tmp->cause = $row->cause;
//                        $tmp->btn_group = '
//                        <a href="#" title="Добавить в календарь" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
//                        <a href="#" title="Добавить контакт" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-success btn-mini"><i class="icon-white icon-pencil"></i></a>
//                        <a href="#taskWindow"  title="Добавить запись" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-danger btn-mini"><i class="icon-white icon-tasks"></i></a>
//                    </div>';
                        $pos = strripos($row->dst, "#");
                        if ($pos === false) {
                            $tmp->dst = $this->removePrefixMera($row->dst);
                            $tmp->dst_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($this->removePrefixMera($row->dst)) . '</span>';
                        } else {
                            list($str, $shlak) = explode("#", $row->dst);
                            $tmp->dst = $shlak;
                            $tmp->dst_contact = '<br/><span style="color:#2f96b4;">' . $this->getContactDetail($shlak) . '</span>';
                        }

                        $results[$tmp->id] = $tmp;
                    }
                }
                break;
        }
        return $results;
    }

    function cropString($channel) {
        $str = strripos($channel, "-");
        if ($str !== false) {
            $row = substr($channel, 0, $str);
            list($str_2, $shlak) = explode("/", $row);
        }
        $str2 = strripos($channel, "@");
        if ($str2 !== false) {
            $row = substr($channel, 0, $str);
            list($str_3, $shlak) = explode("/", $row);
        }
        return $shlak;
    }

    function removePrefixMera($dst) {
        $pos = strripos($dst, "/");
        if ($pos === false) {
            return $dst;
        } else {
            list($str, $shlak) = explode("/", $dst);
            return $shlak;
        }
    }

    function getContactDetail($phone_number) {
        $results = array();

        $this->db->select("id, organization_name as contact_name", false);
        $this->db->from('organization');
        $this->db->where('phone_number', $phone_number);
        $this->db->or_where('alt_phone_number', $phone_number);

        $res = $this->db->get();
        if (0 < $res->num_rows) {
            $ret = $res->row();
            return $ret->contact_name;
        } else {
            $this->db->select("id,contact_name", false);
            $this->db->from('contacts');
            $this->db->where('private_phone_number', $phone_number);
            $this->db->or_where('mobile_number', $phone_number);

            $res = $this->db->get();
            if (0 < $res->num_rows) {
                $ret = $res->row();
                return $ret->contact_name;
            } else {
                $this->db->select("id,first_name, last_name", false);
                $this->db->from('users');
                $this->db->where('phone', $phone_number);
                $this->db->or_where('external_phone', $phone_number);
                //$this->db->or_where('mobile_number', $phone_number);

                $res = $this->db->get();
                if (0 < $res->num_rows) {
                    $ret = $res->row();
                    return $ret->first_name . " " . $ret->last_name;
                }
            }
        }
    }

    function searchOrganization($query) {

        $this->db->select('id as data,organization_name as value');
        $this->db->from('organization');
        $this->db->like('organization_name', $query);

        $res = $this->db->get();
        $arr1 = array();
        if (0 < $res->num_rows) {
            foreach ($res->result() as $result):
                array_push($arr1, array("value" => $result->value, "data" => $result->data));
            endforeach;
        }

        $arr2 = array();
        $arr2['suggestions'] = $arr1;
        echo json_encode($arr2);
    }

    function translateDisposition($disposition) {

        switch ($disposition) {
            case "ANSWERED":
                return 'Ответили';
            case "BUSY":
                return 'Занято';
            case "NO ANSWER":
                return 'Пропущенный';
            case "CALL INTERCEPTION":
                return 'Перехваченный';
            case "ANSWER_BY":
                return 'Переведенный';
            case "":
                return 'Разговор';
            case "FAILED":
                return 'Ошибка';
        }
    }

}

//End of file core_model.php
//Location: ./modules/core/models/core_model.php