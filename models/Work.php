<?php

namespace app\models;

use yii\db\ActiveRecord;

class Work extends ActiveRecord {

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

    static function dateToString($time) {
            $month = date('m', strtotime($time));
            $time = date('Y', strtotime($time))-2000;
            $between = ' и';
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