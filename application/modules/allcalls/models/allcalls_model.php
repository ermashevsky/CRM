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

//        $this->db->select("id, src, dst, start, answe, end, billsec,disposition, uniqueid, cause", false);
//        $this->db->from('cdr');
////        $this->db->or_where('src', $phone_number);
////        $this->db->or_where('dst', $phone_number);
//        $this->db->where("start BETWEEN " .date("Y-m-d 00:00:00"). " AND ". date("Y-m-d 23:59:59"));
//        $this->db->where()
//        $this->db->order_by('end','asc');
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
                $tmp->src = $this->reformatePhoneNumber($row->src);
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
            $res = $this->db->query("SELECT `id` ,  `src` ,  `dst` ,  `start` ,  `answer` ,  `end` ,  `billsec` ,  `disposition` ,  `uniqueid` ,  `cause` 
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
                    $tmp->src = $this->reformatePhoneNumber($row->src);
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
                $tmp->disposition = $row->disposition;
                $tmp->cause = $row->cause;
                $tmp->btn_group = '<div class="btn-group">
                        <a href="#" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-info btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-info btn-mini"><i class="icon-white icon-tasks"></i></a>
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

    function getFilteredCalls2($date_time, $date_time2, $src, $dst, $status_call, $type_call, $user_phone_number, $phone_number, $phone_number2, $condition) {

        $results = array();

        if ($status_call !== 'all_status') {
            $disposition = "AND `disposition` = '" . $status_call . "'";
        } else {
            $disposition = "";
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
            order by end asc");

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
                        $tmp->disposition = $row->disposition;
                        $tmp->cause = $row->cause;
                        $tmp->btn_group = '<div class="btn-group">
                        <a href="#" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-info btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-info btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>';
                        $pos = strripos($row->dst, "#");
                        if ($pos === false) {
                            $tmp->dst = $this->removePrefixMera($row->dst);
                        } else {
                            list($str, $shlak) = explode("#", $row->dst);
                            $tmp->dst = $shlak;
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
                        $tmp->start = $row->start;
                        $tmp->answer = $row->answer;
                        $tmp->end = $row->end;
                        $tmp->billsec = $this->format_seconds($row->billsec);
                        $tmp->disposition = $row->disposition;
                        $tmp->cause = $row->cause;
                        $tmp->btn_group = '<div class="btn-group">
                        <a href="#" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-info btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-info btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>';
                        $pos = strripos($row->dst, "#");
                        if ($pos === false) {
                            $tmp->dst = $this->removePrefixMera($row->dst);
                        } else {
                            list($str, $shlak) = explode("#", $row->dst);
                            $tmp->dst = $shlak;
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
            OR
            (
            end BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
            AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
            AND `src` like  '%" . $phone_number2 . "%'
            AND  `dst` like  '%" . $phone_number . "%' 
            )
            order by end asc");

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
                        $tmp->disposition = $row->disposition;
                        $tmp->cause = $row->cause;
                        $tmp->btn_group = '<div class="btn-group">
                        <a href="#" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-info btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-info btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>';
                        $pos = strripos($row->dst, "#");
                        if ($pos === false) {
                            $tmp->dst = $this->removePrefixMera($row->dst);
                        } else {
                            list($str, $shlak) = explode("#", $row->dst);
                            $tmp->dst = $shlak;
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
                    order by end asc");

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
                        $tmp->disposition = $row->disposition;
                        $tmp->cause = $row->cause;
                        $tmp->btn_group = '<div class="btn-group">
                        <a href="#" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-info btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-info btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>';
                        $pos = strripos($row->dst, "#");
                        if ($pos === false) {
                            $tmp->dst = $this->removePrefixMera($row->dst);
                        } else {
                            list($str, $shlak) = explode("#", $row->dst);
                            $tmp->dst = $shlak;
                        }

                        $results[$tmp->id] = $tmp;
                    }
                }
                break;
        }
        return $results;
    }

    function getFilteredCalls3($date_time, $date_time2, $src, $dst, $status_call, $type_call, $user_phone_number, $phone_number, $phone_number2, $condition) {

        $results = array();

        if ($status_call !== 'all_status') {
            $disposition = "AND `disposition` = '" . $status_call . "'";
        } else {
            $disposition = "";
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
            order by end asc");

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
                        $tmp->disposition = $row->disposition;
                        $tmp->cause = $row->cause;
                        $tmp->btn_group = '<div class="btn-group">
                        <a href="#" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-info btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-info btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>';
                        $pos = strripos($row->dst, "#");
                        if ($pos === false) {
                            $tmp->dst = $this->removePrefixMera($row->dst);
                        } else {
                            list($str, $shlak) = explode("#", $row->dst);
                            $tmp->dst = $shlak;
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
                        $tmp->start = $row->start;
                        $tmp->answer = $row->answer;
                        $tmp->end = $row->end;
                        $tmp->billsec = $this->format_seconds($row->billsec);
                        $tmp->disposition = $row->disposition;
                        $tmp->cause = $row->cause;
                        $tmp->btn_group = '<div class="btn-group">
                        <a href="#" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-info btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-info btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>';
                        $pos = strripos($row->dst, "#");
                        if ($pos === false) {
                            $tmp->dst = $this->removePrefixMera($row->dst);
                        } else {
                            list($str, $shlak) = explode("#", $row->dst);
                            $tmp->dst = $shlak;
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
            OR
            (
            end BETWEEN  '" . date('Y-m-d H:i:s', strtotime($date_time)) . "'
            AND  '" . date('Y-m-d H:i:s', strtotime($date_time2)) . "'
            AND `src` like  '%" . $phone_number2 . "%'
            AND  `dst` like  '%" . $phone_number . "%'
            )
            
            order by end asc");

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
                        $tmp->disposition = $row->disposition;
                        $tmp->cause = $row->cause;
                        $tmp->btn_group = '<div class="btn-group">
                        <a href="#" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-info btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-info btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>';
                        $pos = strripos($row->dst, "#");
                        if ($pos === false) {
                            $tmp->dst = $this->removePrefixMera($row->dst);
                        } else {
                            list($str, $shlak) = explode("#", $row->dst);
                            $tmp->dst = $shlak;
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
                    order by end asc");

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
                        $tmp->disposition = $row->disposition;
                        $tmp->cause = $row->cause;
                        $tmp->btn_group = '<div class="btn-group">
                        <a href="#" onclick="setCalendar();return false;" class="btn btn-info btn-mini"><i class="icon-white icon-calendar"></i></a>
                        <a href="#" onclick="setContactItem(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . ');return false;" class="btn btn-info btn-mini"><i class="icon-white icon-pencil"></i></a>
                        <a href="#taskWindow" onclick="setTask(' . $row->id . ',' . $this->reformatePhoneNumber($row->src) . '); return false;" data-toggle="modal" class="btn btn-info btn-mini"><i class="icon-white icon-tasks"></i></a>
                    </div>';
                        $pos = strripos($row->dst, "#");
                        if ($pos === false) {
                            $tmp->dst = $this->removePrefixMera($row->dst);
                        } else {
                            list($str, $shlak) = explode("#", $row->dst);
                            $tmp->dst = $shlak;
                        }

                        $results[$tmp->id] = $tmp;
                    }
                }
                break;
        }
        return $results;
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
        $this->db->like('phone_number', $phone_number);
        $this->db->or_like('alt_phone_number', $phone_number);

        $res = $this->db->get();
        if (0 < $res->num_rows) {
            $ret = $res->row();
            return $ret->contact_name;
        } else {
            $this->db->select("id,contact_name", false);
            $this->db->from('contacts');
            $this->db->like('private_phone_number', $phone_number);
            $this->db->or_like('mobile_number', $phone_number);

            $res = $this->db->get();
            if (0 < $res->num_rows) {
                $ret = $res->row();
                return $ret->contact_name;
            } else {
                $this->db->select("id,first_name, last_name", false);
                $this->db->from('users');
                $this->db->like('phone', $phone_number);
                $this->db->or_like('external_phone', $phone_number);
                //$this->db->or_where('mobile_number', $phone_number);

                $res = $this->db->get();
                if (0 < $res->num_rows) {
                    $ret = $res->row();
                    return $ret->first_name . " " . $ret->last_name;
                }
            }
        }
    }

}

//End of file core_model.php
//Location: ./modules/core/models/core_model.php