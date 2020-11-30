<?php

namespace app\Models;

use yii\base\Model;

class Search extends Model
{
    public $sex;
    public $sity;
    public $salary;
    public $specialization;
    public $start;
    public $end;
    public $exp;
    public $employment;
    public $schedule;

    public function attributeLabels()
    {
        return [
            'sity' => 'Город',
            'salary' => 'Зарплата',
            'specialization' => 'Специализация',
            'exp' => 'Опыт работы',
            'employment' => 'Тип занятости',
            'schedule' => 'График работы',
        ];
    }
}