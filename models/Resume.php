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