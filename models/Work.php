<?php

namespace app\models;

use yii\db\ActiveRecord;

class Work extends ActiveRecord
{
    /**
     * Return the table name
     * @return string
     */
    public static function tableName() {
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

    /**
     * Function set all working time
     * and assign work's time
     */
    public function setWorkingTime() {
        $year = ($this->endYear - $this->startYear) ? ($this->endYear - $this->startYear + 2000) : '2000';
        $month = ($this->endMonth - $this->startMonth);
        if ($month != 0) {
            if ($month < 0) {
                $year = $year - 1;
                $month = $month + 12;
            }
        } else {
            $month = '00';
        }
        $this->time = $year .'-'. $month .'-01';
    }

}