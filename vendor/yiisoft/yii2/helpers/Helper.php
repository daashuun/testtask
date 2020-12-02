<?php 

namespace yii\helpers;

class Helper 
{

    static function dateToString($time) {
            $month = date('m', strtotime($time));
            $time = date('Y', strtotime($time))-2000;
            $between = ' и';
            if ($month==12) {
                $month = '';
                $between = '';
                $time++;
            } else {
                if ($month<10) 
                    $month = substr($month, (strlen($month)-1), strlen($month));
                if (($month!=1)&&($month<5)) {
                    $month .= ' месяца';
                } else {
                    if ($month!=1) {
                        $month .= ' месяцев';
                    } else {
                        $month .= ' месяц';
                    }
                };
            }
            if ($time==0) {
                $time = '';
                $between = '';
            } else {
                $last = substr($time, (strlen($time)-1), strlen($time));
                if ((($time<21)&&($time>4))||($last>4)||($last==0)) {
                    $time .= ' лет';
                } else {
                    if ($last==1) {
                        $time .= ' год';
                    } else {
                        $time .= ' года';
                    }
                }
            }
            
            $time .= $between.' '.$month;
            return $time;
    }

    static function saveImg($name, $oldName = '') {
        if (file_exists('images/changed/'.$name.'')) {
            rename('images/changed/'.$name.'', 'images/photo/'.$name.'');
        } else $oldName = '';
        if ((strlen($oldName)>0)&&(file_exists('images/photo/'.$oldName)))
            unlink ('images/photo/'.$oldName);
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

}