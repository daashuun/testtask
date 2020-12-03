<?php

namespace app\models;

use yii\db\ActiveRecord;

class Resume extends ActiveRecord 
{
    /**
     * This var for change photo
     * @var string
     */
    public $foto;

    /**
     * Scenario only if you create 
     * a new resume
     */
    const SCENARIO_NEW = 'new';

    /**
     * Return the table name
     * @return string
     */
    public static function tableName()
    {
        return 'resume';
    }

    /**
     * Return connect "one-many" with Works
     * @return array(Work)
     */
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
    
}