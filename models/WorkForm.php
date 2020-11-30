<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\enums\Month;

class WorkForm extends ActiveRecord {

    public static function tableName()
    {
        return 'work';
    }

    public function rules() {
        return [
            [ ['id', 'now', 'startMonth', 'endMonth'], 'safe'],
            [ ['company', 'position', 'startYear', 'endYear'], 'required'], 
            [ ['position', 'company', 'duties'], 'string'],
            [ ['startYear', 'endYear'], 'number', 'min' => 1900, 'max' => date('Y')], 
        ];
    }

    public function setWork() {
            $this['time'] = strtotime($this['time']);
            $this['time'] = WorkForm::dateToTime($this['time']);
            $this['endMonth'] = Month::getLabel($this['endMonth']);
            $this['endYear'] = ($this['now']) ? 'По настоящее время' : $this['endMonth'].' '.$this['endYear'];
            $this['startYear'] = Month::getLabel($this['startMonth']).' '.$this['startYear'];
    }

    static function dateToTime($time, $between = '') {
            $month = date('m', $time);
            $time = date('Y', $time)-2000;
            if ($month==12) {
                $month = '';
                $between = '';
                $time++;
            } else {
                if ($month<10) 
                    $month = substr($month, (strlen($month)-1), strlen($month));
                if (($month!=1)&&($month<5)) {
                    $month .= ' месяца';
                } else {
                    if ($month!=1) {
                        $month .= ' месяцев';
                    } else {
                        $month .= ' месяц';
                    }
                };
            }
            if ($time==0) {
                $time = '';
                $between = '';
            } else {
                $last = substr($time, (strlen($time)-1), strlen($time));
                if ((($time<21)&&($time>4))||($last>4)||($last==0)) {
                    $time .= ' лет';
                } else {
                    if ($last==1) {
                        $time .= ' год';
                    } else {
                        $time .= ' года';
                    }
                }
            }
            
            $time .= $between.' '.$month;
            return $time;
    }

    static function allTime($works) {
        $time = 0;
        $month = 0;
        $year = 0;
        foreach ($works as $work) {
            $t = strtotime($work['time']);
            $month = $month + date('m', $t);
            $year = $year + date('Y', $t)-2000;
            if ($month>12) {
                $year++;
                $month = $month - 12;
            }
        }
        $year = $year + 2000;
        $time = $year.'-'.$month.'-01';
        $time = strtotime($time);
        $time = 'Опыт работы '.WorkForm::dateToTime($time, ' и ');
        return $time;
    }

    static function sortWorks($works) {
        foreach ($works as $id=>$work) {
            foreach ($works as $i=>$work) {
                if (($works[$id])&&($works[$id])) {
                    if ($works[$id]['startYear']>$works[$i]['startYear']) {
                        $var = $works[$id];
                        $works[$id] = $works[$i];
                        $works[$i] = $var;
                    } else {
                        if (($works[$id]['startYear']==$works[$i]['startYear'])&&($works[$id]['startMonth']<$works[$i]['startMonth'])) {
                            $var = $works[$id];
                            $works[$id] = $works[$i];
                            $works[$i] = $var;
                        }
                    }
                }
            }
        }
        return $works;
    }

};