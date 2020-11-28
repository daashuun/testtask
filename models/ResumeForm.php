<?php

namespace app\models;
use yii\db\ActiveRecord;
use yii\helpers\Html;

class ResumeForm extends ActiveRecord 
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
        return $this->hasMany(WorkForm::className(), ['resumeId' => 'id']);
    }

    public function attributeLabels() {
        return [
            'photo' => 'Изменить фото',
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
            [ 'photo', 'required', 'on' => self::SCENARIO_NEW],
            [ ['foto', 'photo', 'exp'], 'safe'],
            [ 'email', 'email'],
            [ ['surname', 'name', 'middlename'], 'match', 'pattern' => '/^[А-Яа-яЁё]+$/'],
            [ ['name', 'surname', 'middlename', 'birtday', 'sex', 'sity', 'email', 'phone', 'specialization', 'salary', 'employment', 'schedule', 'experience'], 'required'], 
            [ 'about', 'string'],
            [ 'salary', 'number', 'min' => 1], 
        ];
    }

    public function setResume($output, $works=[]) {
        $this['birtday'] = date('d.n.Y', strtotime($this['birtday']));
        $this['schedule'] = json_decode($this['schedule']);
        $this['employment'] = json_decode($this['employment']);
        if ($output) {
            $this['specialization'] = $this->SpecList()[$this['specialization']];
            $this['sity'] = $this->SityList()[$this['sity']];
            $this['employment'] = $this->Employment($this['employment']);
            $this['schedule'] = $this->Schedule($this['schedule']);
            $this['experience'] = ($this['experience']) ? WorkForm::allTime($works) : 'Нет опыта работы';
            $changed = date('d',strtotime($this['changed']));
            $n = date('n',strtotime($this['changed']));
            $m = $this->MonthList()[$n];
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

    static function saveImg($foto) {
        $files = glob('images/changed/*');
        foreach($files as $file){
          if(is_file($file))
            unlink($file);
        }
        $name = uniqid().$foto['ResumeForm']['name']['photo'];
        (move_uploaded_file($foto['ResumeForm']['tmp_name']['photo'], 'images/photo/'.$name.'')) ;
        return $name;
    }

    static function changeImg($foto) {
        $name = uniqid().$foto['name'];
        (move_uploaded_file($foto['ResumeForm']['tmp_name']['photo'], 'images/changed/'.$name.'')) ;
        return $name;
    }

    static function Employment($keys) {
        $emp = ResumeForm::EmploymentList();
        $res = '';
        foreach ($keys as $id=>$key) {
            $res .= ' '.$emp[$key];
            if ($id!=count($keys)-1) {
                $res .=  ',';
            } 
        }
        return $res;
    }

    static function Schedule($keys) {
        $sch = ResumeForm::ScheduleList();
        $res = '';
        foreach ($keys as $id=>$key) {
            $res .= ' '.$sch[$key];
            if ($id!=count($keys)-1) {
                $res .=  ',';
            } 
        }
        return $res;
    }

    static function EmploymentList($list = false) {
        if ($list) return [ 'Полная занятость', 'Частичная занятость',
            'Проектная/Временная работа', 'Волонтёрство', 'Стажировка'];
        return [
            '1' => 'Полная занятость', 
            '2' => 'Частичная занятость',
            '3' => 'Проектная/Временная работа',
            '4' => 'Волонтёрство', 
            '5' => 'Стажировка',
        ];
    }

    static function ScheduleList($list = false) {
        if ($list) return [ 'Полный день', 'Сменный график', 
        'Гибкий график', 'Удалённая работа', 'Вахтовый метод'];
        return [
            '1' => 'Полный день',
            '2' => 'Сменный график',
            '3' => 'Гибкий график',
            '4' => 'Удалённая работа',
            '5' => 'Вахтовый метод',
        ];
    }
    
    static function SityList() {
        return [
            '1' => 'Москва',
            '2' => 'Санкт-Петербург',
            '3' => 'Новосибирск',
            '4' => 'Екатеринбург',
            '5' => 'Нижний Новгород',
            '6' => 'Самара',
            '7' => 'Омск',
            '8' => 'Казань',
            '9' => 'Челябинск',
            '10' => 'Ростов-на-Дону',
            '11' => 'Уфа',
            '12' => 'Волгоград',
            '13' => 'Пермь',
            '14' => 'Красноярск',
            '15' => 'Воронеж',
            '16' => 'Саратов',
            '17' => 'Краснодар',
            '18' => 'Тольятти',
            '19' => 'Ижевск',
            '20' => 'Ульяновск',
            '21' => 'Барнаул',
            '22' => 'Владивосток',
            '23' => 'Ярославль',
            '24' => 'Иркутск',
            '25' => 'Тюмень',
            '26' => 'Махачкала',
        ];
    }

    static function SpecList() {
        return [
            '1' => 'Администратор баз данных',
            '2' => 'Аналитик',
            '3' => 'Арт-директор',
            '4' => 'Инженер',
            '5' => 'Компьютерная безопасность',
            '6' => 'Контент',
            '7' => 'Маркетинг',
            '8' => 'Мультимедиа',
            '9' => 'Оптимизация сайта (SEO)',
            '10' => 'Передача данных и доступ в интернет',
            '11' => 'Программирование, Разработка',
            '12' => 'Продажи',
            '13' => 'Продюсер',
            '14' => 'Развитие бизнеса',
            '15' => 'Системный администратор',
            '16' => 'Системы управления предприятием (ERP)',
            '17' => 'Сотовые, Беспроводные технологии',
            '18' => 'Стартапы',
            '19' => 'Телекоммуникации',
            '20' => 'Тестирование',
            '21' => 'Технический писатель',
            '22' => 'Управление проектами',
            '23' => 'Электронная коммерция',
            '24' => 'CRM системы',
            '25' => 'Web инженер',
            '26' => 'Web мастер',
        ];
    }

    static function MonthList() {
        return [
            '1' => 'Январь',
            '2' => 'Февраль',
            '3' => 'Март',
            '4' => 'Апрель',
            '5' => 'Май',
            '6' => 'Июнь',
            '7' => 'Июль',
            '8' => 'Август',
            '9' => 'Сентябрь',
            '10' => 'Октябрь',
            '11' => 'Ноябрь',
            '12' => 'Декабрь',
        ];
    }

};