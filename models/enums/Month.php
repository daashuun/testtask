<?php 

namespace app\models\enums;

use yii2mod\enum\helpers\BaseEnum;

/**
 * List of months
 */
class Month extends BaseEnum 
{
    /**
     * Month's options
     * @var array
     */
    public static $list = [
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