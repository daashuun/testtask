<?php 

namespace app\models\enums;

use yii2mod\enum\helpers\BaseEnum;

/**
 * List of employment's options
 */
class Employment extends BaseEnum 
{
    /**
     * Employment's options
     * @var array
     */
    public static $list = [
        '1' => 'Полная занятость', 
        '2' => 'Частичная занятость',
        '3' => 'Проектная/Временная работа',
        '4' => 'Волонтёрство', 
        '5' => 'Стажировка',
    ];
}