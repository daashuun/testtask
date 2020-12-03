<?php 

namespace app\models\enums;

use yii2mod\enum\helpers\BaseEnum;

/**
 * List of schedule's options
 */
class Schedule extends BaseEnum 
{
    /**
     * Schedule's options
     * @var array
     */
    public static $list = [
        '1' => 'Полный день',
        '2' => 'Сменный график',
        '3' => 'Гибкий график',
        '4' => 'Удалённая работа',
        '5' => 'Вахтовый метод',
    ];
}