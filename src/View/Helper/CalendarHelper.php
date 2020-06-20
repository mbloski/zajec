<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\View\Helper;
use Cake\View\View;

/**
 * Calendar helper
 */
class CalendarHelper extends Helper
{
    public $helpers = ['Html'];

    private function getWeeks($date, $rollover)
    {
        $cut = substr($date, 0, 8);
        $daylen = 86400;

        $timestamp = strtotime($date);
        $first = strtotime($cut . "00");
        $elapsed = ($timestamp - $first) / $daylen;

        $weeks = 1;

        for ($i = 1; $i <= $elapsed; $i++) {
            $dayfind = $cut . (strlen(strval($i)) < 2 ? '0' . $i : $i);
            $daytimestamp = strtotime($dayfind);

            $day = strtolower(date("l", $daytimestamp));

            if ($day == strtolower($rollover)) $weeks++;
        }

        return $weeks;
    }

    public function render($date = 0)
    {
        $ret = '';
        $p = Router::getRequest()->getQueryParams();
        if (array_key_exists('date', $p)) {
            unset($p['date']);
        }

        if ($date == 0) {
            $date = time();
        }
        $day = (int)date('d', $date);
        $month = (int)date('m', $date);
        $year = (int)date('Y', $date);

        $first_day = mktime(0, 0, 0, $month, 1, $year);
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $title = date('M Y', $first_day);

        $day_list = array('Mon' => array_fill(0, 6, ''), 'Tue' => array_fill(0, 6, ''), 'Wed' => array_fill(0, 6, ''), 'Thu' => array_fill(0, 6, ''), 'Fri' => array_fill(0, 6, ''), 'Sat' => array_fill(0, 6, ''), 'Sun' => array_fill(0, 6, ''));

        for ($i = 1; $i <= $days; $i++) {
            $d = mktime(0, 0, 0, $month, $i, $year);
            $weeknum = $this->getWeeks(date('Y-m-d', $d), "sunday") - 1;
            $dayname = date('D', $d);
            $aclass = '';
            if (date('Y-m-d', $date) == date('Y-m-d', $d)) {
                $aclass = 'current';
            }
            $day_list[$dayname][$weeknum] = $this->Html->link(str_pad((string)$i, 2, ' ', STR_PAD_LEFT), ['?' => $p + ['date' => date('Y-m-d', $d)]], ['class' => $aclass]);
        }


        $ret .= '<pre>';

        $prevmonth = date('Y-m-d', mktime(0, 0, 0, intval($month) - 1, $day, $year));
        $nextmonth = date('Y-m-d', mktime(0, 0, 0, intval($month) + 1, $day, $year));

        $prevyear = date('Y-m-d', mktime(0, 0, 0, intval($month), $day, $year - 1));
        $nextyear = date('Y-m-d', mktime(0, 0, 0, intval($month), $day, $year + 1));

        $ret .= $this->Html->link('<<', ['?' => $p + ['date' => $prevyear]]);
        $ret .= ' ';
        $ret .= $this->Html->link('<', ['?' => $p + ['date' => $prevmonth]]);
        $ret .= ' ';
        $ret .= $this->Html->link($title, ['?' => $p + ['date' => date('Y-m-d')]]);
        $ret .= ' ';
        $ret .= $this->Html->link('>', ['?' => $p + ['date' => $nextmonth]]);
        $ret .= ' ';
        $ret .= $this->Html->link('>>', ['?' => $p + ['date' => $nextyear]]);
        $ret .= '<br />';

        $ret .= 'Su Mo Tu We Th Fr Sa<br>';
        for ($i = 0; $i < 6; $i++) {
            $ret .= str_pad($day_list['Sun'][$i], 2, ' ', STR_PAD_LEFT) . ' ';
            $ret .= str_pad($day_list['Mon'][$i], 2, ' ', STR_PAD_LEFT) . ' ';
            $ret .= str_pad($day_list['Tue'][$i], 2, ' ', STR_PAD_LEFT) . ' ';
            $ret .= str_pad($day_list['Wed'][$i], 2, ' ', STR_PAD_LEFT) . ' ';
            $ret .= str_pad($day_list['Thu'][$i], 2, ' ', STR_PAD_LEFT) . ' ';
            $ret .= str_pad($day_list['Fri'][$i], 2, ' ', STR_PAD_LEFT) . ' ';
            $ret .= str_pad($day_list['Sat'][$i], 2, ' ', STR_PAD_LEFT) . ' ' . "<br />";
        }
        $ret .= '</pre>';

        return $ret;
    }
}
