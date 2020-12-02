<?php

namespace app\Models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Resume;
use app\models\Work;
use yii\helpers\Html;

class SearchResume extends Model
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
    public $text;

    public function attributeLabels()
    {
        return [
            'sity' => 'Город',
            'salary' => 'Зарплата',
            'specialization' => 'Специализация',
            'exp' => 'Опыт работы',
            'employment' => 'Занятость',
            'schedule' => 'График работы',
        ];
    }

    public function rules()
    {
        return [
            [ 'text', 'string' ],
            [ [ 'sex', 'sity', 'salary', 'specialization', 'start', 'end', 'exp', 'employment', 'schedule' ], 'integer'], 
        ];
    }

    public function search($params)
    {
        $query = Resume::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, '');

        if (!$this->validate()) {
            return $dataProvider;
        }

        $q = [];

        if ($this->sex) {
            $sex = ($this->sex == 2) ? 0 : 1;
            $q += ['sex' => $sex];
        }

        if ($this->sity) 
            $q += ['sity' => $this->sity];

        if ($this->specialization) 
            $q += ['specialization' => $this->specialization];

        if (empty($q))
            $q = ['WhereAll' => '1'];

        $query->andFilterWhere($q);

        if ($this->salary) 
            $query->andFilterWhere(['<=', 'salary', $this->salary]);

        if ($this->start) {
            $this->start = "". (date('Y') - $this->start) ."-01-01";
            $query->andFilterWhere(['<=', 'birtday', $this->start]);
        }

        if ($this->end) {
            $this->end = "". (date('Y') - $this->end) ."-01-01";
            $query->andFilterWhere(['>=', 'birtday', $this->end]);
        }

        if ($this->exp) {
            for ($i = 0; $i < strlen($this->exp); $i++) 
                $exp[] = $this->exp[$i];
            $query->andFilterWhere(['in', 'exp', $exp]);
        }

        if ($this->employment) {
            for ($i = 0; $i < strlen($this->employment); $i++) 
                $employment[] = $this->employment[$i];
            $query->andFilterWhere(['or like', 'employment', $employment]);
        }

        if ($this->schedule) {
            for ($i = 0; $i < strlen($this->schedule); $i++) 
                $schedule[] = $this->schedule[$i];
            $query->andFilterWhere(['or like', 'schedule', $schedule]);
        }

        if ($this->text) {
            $this->text = Html::encode(trim($this->text));
            $text =  explode(' ', $this->text);
            foreach ($text as $word)
                $word = str_replace($word, ' ', '');

            $works = Work::find()->where(['or', ['or like', 'position', $text], ['or like', 'company', $text], ['or like', 'duties', $text] ])->all();
            foreach ($works as $work) 
                $id[] = $work['resumeId'];

            $query->andFilterWhere(['or', ['or like', 'about', $text], ['or like', 'name', $text], ['or like', 'surname', $text], ['or like', 'middlename', $text], ['id' => $id] ]);
        }
        
        $query->with('work');
        return $dataProvider;
    }
}
