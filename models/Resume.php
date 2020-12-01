<?php

namespace app\models;
use yii\db\ActiveRecord;
use app\models\enums\Sity;
use app\models\enums\Specialization;
use app\models\enums\Employment;
use app\models\enums\Schedule;
use app\models\enums\Month;

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

    public function setResume($output, $works=[]) {
        $this['birtday'] = date('d.n.Y', strtotime($this['birtday']));
        $this['schedule'] = json_decode($this['schedule']);
        $this['employment'] = json_decode($this['employment']);
        if ($output) {
            $this['specialization'] = Specialization::getLabel($this['specialization']);
            $this['sity'] = Sity::getLabel($this['sity']);
            // $this['employment'] = Employment::getLabel($this['employment']);
            // $this['schedule'] = Schedule::getLabel($this['schedule']);
            $this['experience'] = ($this['experience']) ? Work::allTime($works) : 'Нет опыта работы';
            $changed = date('d',strtotime($this['changed']));
            $n = date('n',strtotime($this['changed']));
            $m = Month::getLabel($n);
            $m = substr($m, 0, (strlen($m)-2));
            if (($n!=3)&&($n!=8)) {
                $m .= 'я';
            } else {
                $m .= 'та';
            };
            $changed .= ' '.$m;
            $changed .= ' '.date('Y',strtotime($this['changed']));
            $changed .= ' '.date('H:i',strtotime($this['changed']));
            $this['changed'] = $changed;
            $year = date('Y') - date('Y', strtotime($this['birtday']));
            $last = substr($year, (strlen($year)-1), strlen($year));
            if ((($year<21)&&($year>4))||($last>4)||($last==0)) {
                $year .= ' лет';
            } else {
                if ($last==1) {
                    $year .= ' год';
                } else {
                    $year .= ' года';
                }
            }
            $this['birtday'] = $year;
        }
    }

    static function saveImg($name) {
        if (file_exists('images/changed/'.$name.''))
            rename('images/changed/'.$name.'', 'images/photo/'.$name.'');
        $files = glob('images/changed/*');
        foreach($files as $file){
          if(is_file($file))
            unlink($file);
        }
    }

    static function changeImg($foto) {
        $name = uniqid().$foto['Resume']['name']['foto'];
        (move_uploaded_file($foto['Resume']['tmp_name']['foto'], 'images/changed/'.$name.'')) ;
        return $name;
    }

};