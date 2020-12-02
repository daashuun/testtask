<?php

namespace app\models;
use yii\db\ActiveRecord;

class Resume extends ActiveRecord 
{
    public $foto;
    public $last;
    const SCENARIO_NEW = 'new';

    public static function tableName()
    {
        return 'resume';
    }

    public function getWork()
    {
        return $this->hasMany(Work::className(), ['resumeId' => 'id']);
    }

    public function attributeLabels() {
        return [
            'foto' => 'Изменить фото',
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'middlename' => 'Отчество',
            'birtday' => 'Дата рождения',
            'sex' => 'Пол',
            'sity' => 'Город проживания',
            'email' => 'Электронная почта',
            'phone' => 'Телефон',
            'specialization' => 'Специализация',
            'salary' => 'Зарплата',
            'employment' => 'Занятость',
            'schedule' => 'График работы',
            'experience' => 'Опыт работы',
            'about' => 'О себе',
        ];
    }

    public function rules() {
        return [
            [ 'foto', 'required', 'on' => self::SCENARIO_NEW],
            [ ['id', 'view', 'foto', 'exp'], 'safe'],
            [ 'email', 'email'],
            [ ['surname', 'name', 'middlename'], 'match', 'pattern' => '/^[А-Яа-яЁё]+$/'],
            [ ['photo', 'name', 'surname', 'middlename', 'birtday', 'sex', 'sity', 'email', 'phone', 'specialization', 'salary', 'employment', 'schedule', 'experience'], 'required'], 
            [ 'about', 'string'],
            [ 'salary', 'number', 'min' => 1], 
        ];
    }

    public function setExp() {
        $year = 0;
        $month = 0;
        foreach ($this->work as $work) {
            $month = $month + $work['endMonth']+(12-$work['startMonth']);
            $year = $year + ($work['endYear']-$work['startYear']);
            if ($month>=12) {
                $year++;
                $month = $month - 12;
            } else {
                if ($work['endYear']!=$work['startYear'])
                    $year--;
            }
        }
        $this->exp = 3;
        if ($year<6) {
            if (($year>1)&&($year<3)) {
                $this->exp = 1;
            } else {
                if ($year<1) {
                    $this->exp = 4;
                } else {
                    $this->exp = 2;
                }
            }
        }
    }

    public function sortWorks() {
        foreach ($this->work as $id=>$work) {
            foreach ($this->work as $i=>$work) {
                if (($this->work[$id])&&($this->work[$id])) {
                    if ($this->work[$id]['startYear']>$this->work[$i]['startYear']) {
                        $var = $this->work[$id];
                        $this->work[$id] = $this->work[$i];
                        $this->work[$i] = $var;
                    } else {
                        if (($this->work[$id]['startYear']==$this->work[$i]['startYear'])&&($this->work[$id]['startMonth']<$this->work[$i]['startMonth'])) {
                            $var = $this->work[$id];
                            $works[$id] = $this->work[$i];
                            $works[$i] = $var;
                        }
                    }
                }
            }
        }
    }
    
};