<?php

namespace app\Models;

use yii\base\Model;
use app\models\enums\Month;
use app\models\enums\Schedule;
use app\models\enums\Employment;
use app\models\enums\Specialization;
use app\models\enums\Sity;
use yii\helpers\Helper;

/**
 * Function set for Resume output
 */
class ViewResume extends Model 
{
    public $birtday;
    public $sity;
    public $specialization;
    public $employment;
    public $schedule;
    public $experience;
    public $changed;
    public $last;
    public $works;

    /**
     * Construct Resume for view
     * @param Resume $resume
     * @param array(Work) $works
     */
    public function __construct($resume, $works = [])
    {
        $this->birtday = $resume['birtday'];
        $this->sity = $resume['sity'];
        $this->specialization = $resume['specialization'];
        $this->employment = $resume['employment'];
        $this->schedule = $resume['schedule'];
        $this->experience = $resume['experience'];
        $this->changed = $resume['changed'];
        if ($works) {
            $this->last = $works[0];
        }
        $this->works = $works;
    }

    /**
     * Function return age of resume's person
     * @return string
     */
    public function getFullYears() {
        $year = date('Y') - date('Y', strtotime($this->birtday));
        $last = substr($year, (strlen($year) - 1), strlen($year));
        if ((($year < 21) && ($year > 4)) || ($last > 4) || ($last == 0)) {
            $year .= ' лет';
        } elseif ($last == 1) {
            $year .= ' год';
        } else {
            $year .= ' года';
        }
        return $year;
    }

    /**
     * Function return resume's specialization label
     * @return string
     */
    public function getSpecialization() {
        return Specialization::getLabel($this->specialization);
    }

    /**
     * Function return resume's sity label
     * @return string
     */
    public function getSity() {
        return Sity::getLabel($this->sity);
    }

    /**
     * Function return resume's employment labels as string
     * @return string
     */
    public function getEmployment() {
        $employment = '';
        $this->employment = json_decode($this->employment);
        foreach ($this->employment as $id => $emp) {
            $employment .= Employment::getLabel($emp);
            if ($id != count($this->employment) - 1) {
                $employment .= ', ';
            }
        }
        return $employment;
    }

    /**
     * Function return resume's schedule labels as string
     * @return string
     */
    public function getSchedule() {
        $schedule = '';
        $this->schedule = json_decode($this->schedule);
        foreach ($this->schedule as $id => $sch) {
            $schedule .= Schedule::getLabel($sch);
            if ($id != count($this->schedule) - 1) {
                $schedule .= ', ';
            }
        }
        return $schedule;
    }

    /**
     * Function return resume's experiens: if it's isset - 
     * return how much is it, else - no experiens
     * @return string
     */
    public function getExperience() {
        if (! $this->experience) {
            return 'Нет опыта работы';
        }
        $year = 0;
        $month = 0;
        foreach ($this->works as $work) {
            $year = $year + date('y', strtotime($work->time));
            $month = $month + date('m', strtotime($work->time));
            if ($month > 12) {
                $year++;
                $month = $month - 12;
            }
        }
        $year = $year + 2000;
        return 'Опыт работы '. Helper::dateToString($year.'-'.$month.'-01');
    }

    /**
     * Function return last resume's changing as string
     * @return string
     */
    public function getChanged() {
        $changed = date('d', strtotime($this->changed));
        $n = date('n', strtotime($this->changed));
        $m = Month::getLabel($n);
        $m = substr($m, 0, (strlen($m) - 2));
        if (($n != 3) && ($n != 8)) {
            $m .= 'я';
        } else {
            $m .= 'та';
        }
        $changed .= ' '. $m;
        $changed .= ' '. date('Y', strtotime($this->changed));
        $changed .= ' '. date('H:i', strtotime($this->changed));
        return $changed;
    }

    /**
     * Function return resume's last work's: position, company, 
     * start and and of work as string
     * @return string
     */
    public function getLastWork() {
        if (! $this->experience) {
            return;
        }
        if ($this->last->now) {
            $this->last->endYear = 'По настоящее время';
        }
        return ($this->last->position .' в '. $this->last->company .', '. $this->last->startYear .' - '. $this->last->endYear);
    }

    /**
     * Function return resume's work's end, expected work's id
     * @param int $id 
     * @return string
     */
    public function getWorkEnd($id) {
        if ($this->works[$id]->now) {
            return 'По настоящее время';
        } else {
            return Month::getLabel($this->works[$id]->endMonth) .' '. $this->works[$id]->endYear;
        } 
    }

    /**
     * Function return resume's work's start, expected work's id
     * @param int $id 
     * @return string
     */
    public function getWorkStart($id) {
        return Month::getLabel($this->works[$id]->startMonth) .' '. $this->works[$id]->startYear;
    }

}