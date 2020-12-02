<?php

namespace app\Models;

use yii\base\Model;
use app\models\enums\Month;
use app\models\enums\Schedule;
use app\models\enums\Employment;
use app\models\enums\Specialization;
use app\models\enums\Sity;
use yii\helpers\Helper;

class ViewResume extends Model 
{
    public $id;
    public $photo;
    public $surname;
    public $name;
    public $middlename;
    public $birtday;
    public $sity;
    public $email;
    public $phone;
    public $specialization;
    public $salary;
    public $employment;
    public $schedule;
    public $experience;
    public $about;
    public $changed;
    public $view;
    public $last = 0;
    public $works;

    public function __construct($resume, $works = [])
    {
        $this->id = $resume['id'];
        $this->photo = $resume['photo'];
        $this->surname = $resume['surname'];
        $this->name = $resume['name'];
        $this->middlename = $resume['middlename'];
        $this->birtday = $resume['birtday'];
        $this->sity = $resume['sity'];
        $this->email = $resume['email'];
        $this->phone = $resume['phone'];
        $this->specialization = $resume['specialization'];
        $this->salary = $resume['salary'];
        $this->employment = $resume['employment'];
        $this->schedule = $resume['schedule'];
        $this->experience = $resume['experience'];
        $this->about = $resume['about'];
        $this->changed = $resume['changed'];
        $this->view = $resume['view'];
        if ($works)
            $this->last = $works[0];
        $this->works = $works;
    }

    public function getFullYears() {
        $year = date('Y') - date('Y', strtotime($this->birtday));
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
        return $year;
    }

    public function getSpecialization() {
        return Specialization::getLabel($this->specialization);
    }

    public function getSity() {
        return Sity::getLabel($this->sity);
    }

    public function getEmployment() {
        $employment = '';
        $this->employment = json_decode($this->employment);
        foreach ($this->employment as $id=>$emp) {
            $employment .= Employment::getLabel($emp);
            if ($id!=count($this->employment))
                $employment .= ', ';
        }
        return $employment;
    }

    public function getSchedule() {
        $schedule = '';
        $this->schedule = json_decode($this->schedule);
        foreach ($this['schedule'] as $id=>$sch) {
            $schedule .= Schedule::getLabel($sch);
            if ($id!=count($this->schedule))
                $schedule .= ', ';
        }
        return $schedule;
    }

    public function getExperience() {
        if (!$this->experience) {
            return 'Нет опыта работы';
        };
        $year = 0;
        $month = 0;
        foreach ($this->works as $id=>$work) {
            $year = $year + date('y', strtotime($work['time']));
            $month = $month + date('m', strtotime($work['time']));
            if ($month>12) {
                $year++;
                $month = $month - 12;
            }
        }
        $year = $year + 2000;
        return 'Опыт работы '.Helper::dateToString($year.'-'.$month.'-01');
    }

    public function getChanged() {
        $changed = date('d',strtotime($this->changed));
        $n = date('n',strtotime($this->changed));
        $m = Month::getLabel($n);
        $m = substr($m, 0, (strlen($m)-2));
        if (($n!=3)&&($n!=8)) {
            $m .= 'я';
        } else {
            $m .= 'та';
        };
        $changed .= ' '.$m;
        $changed .= ' '.date('Y',strtotime($this->changed));
        $changed .= ' '.date('H:i',strtotime($this->changed));
        return $changed;
    }

    public function getLastWork() {
        if (!$this->experience)
            return;
        if ($this->last['now']) 
            $this->last['endYear'] = 'По настоящее время';
        return ($this->last['position'].' в '.$this->last['company'].', '.$this->last['startYear'].' - '.$this->last['endYear']);
    }

    public function getWorkEnd($id) {
        return  ($this->works[$id]['now']) ? 
            'По настоящее время' : 
            Month::getLabel($this->works[$id]['endMonth']).' '.$this->works[$id]['endYear'];
    }

    public function getWorkStart($id) {
        return Month::getLabel($this->works[$id]['startMonth']).' '.$this->works[$id]['startYear'];
    }

}