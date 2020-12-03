<?php 

namespace yii\helpers;

/**
 * My helper class
 */
class Helper 
{
    /**
     * Convert date to string format 
     * "y years and m months" with
     * declensions. Expected date 
     * format "Y-m-d"
     * @param string $time
     * @return string
     */
    static function dateToString($time) {
        $month = date('m', strtotime($time));
        $time = date('Y', strtotime($time)) - 2000;
        $between = ' и';

        if ($month == 12) {
            $month = '';
            $between = '';
            $time++;
        } else {
            if ($month < 10) {
                $month = substr($month, (strlen($month) - 1), strlen($month));
            }
            if (($month != 1) && ($month < 5)) {
                $month .= ' месяца';
            } elseif ($month != 1) {
                $month .= ' месяцев';
            } else {
                $month .= ' месяц';
            }
        }

        if ($time == 0) {
            $time = '';
            $between = '';
        } else {
            $last = substr($time, (strlen($time) - 1), strlen($time));
            if ((($time < 21) && ($time > 4)) || ($last > 4) || ($last == 0)) {
                $time .= ' лет';
            } elseif ($last==1) {
                $time .= ' год';
            } else {
                $time .= ' года';
            }
        }
        
        $time .= $between .' '. $month;
        return $time;
    }

    /**
     * Function save image in "photo" directory,
     * delete previous photo if that isset,
     * clean the "changed" directory. Expected
     * only names, without directories
     * @param string $name
     * @param string $oldName
     */
    static function saveImg($name, $oldName = '') {
        if (file_exists('images/changed/'. $name .'')) {
            rename('images/changed/'. $name .'', 'images/photo/'. $name .'');
        } else {
            $oldName = '';
        }
        if ((strlen($oldName) > 0) && (file_exists('images/photo/'. $oldName))) {
            unlink ('images/photo/'. $oldName);
        }
        $files = glob('images/changed/*');
        foreach($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Funtion save image in "changed" directory
     * and generate name. Usage for ajax change
     * image on view "new". Expected file
     * @param array $foto
     * @return string
     */
    static function changeImg($foto) {
        $name = uniqid() . $foto['Resume']['name']['foto'];
        (move_uploaded_file($foto['Resume']['tmp_name']['foto'], 'images/changed/'. $name .'')) ;
        return $name;
    }

    /**
     * Sorting array of works as date of start
     * @param array $works
     * @return array
     */
    static function sortWorks($works) {
        foreach ($works as $id => $work) {
            for ($i = $id; $i < count($works); $i++) {
                if (($works[$id]) && ($works[$i])) {
                    if ($works[$id]->startYear < $works[$i]->startYear) {
                        $var = $works[$id]->id;
                        $works[$id]->id = $works[$i]->id;
                        $works[$i]->id = $var;
                    } else {
                        if (
                            (
                                $works[$id]->startYear
                                == $works[$i]->startYear
                            ) && (
                                $works[$id]->startMonth
                                < $works[$i]->startMonth
                            )
                        ) {
                            $var = $works[$i]->id;
                            $works[$i]->id = $works[$id]->id;
                            $works[$id]->id = $var;
                        }
                    }
                }
            }
        }
        return $works;
    }

    /**
     * Set experiense for resume
     * @param array $works
     * @return int
     */
    static function setExp($works) {
        $year = 0;
        $month = 0;
        foreach ($works as $work) {
            $month = $month + $work->endMonth + (12 - $work->startMonth);
            $year = $year + ($work->endYear - $work->startYear);
            if ($month>=12) {
                $year++;
                $month = $month - 12;
            } else {
                if ($work->endYear != $work->startYear) {
                    $year--;
                }
            }
        }
        $exp = 3;
        if ($year < 6) {
            if (($year > 1) && ($year < 3)) {
                $exp = 1;
            } else {
                if ($year < 1) {
                    $exp = 4;
                } else {
                    $exp = 2;
                }
            }
        }
        return $exp;
    }

    /**
     * Function converted first
     * letter to uppercase and
     * other to lowercase
     * @param string $str
     * @return string
     */
    static function mb_ucfirst($str) 
    {
        $str = mb_strtolower($str, 'UTF-8');
        $fc = mb_strtoupper(mb_substr($str, 0, 1));
        return $fc.mb_substr($str, 1);
    }

}