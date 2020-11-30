<?php 

namespace app\models\enums;

use yii2mod\enum\helpers\BaseEnum;

class Schedule extends BaseEnum 
{
    public static $list = [
        '1' => 'Полный день',
        '2' => 'Сменный график',
        '3' => 'Гибкий график',
        '4' => 'Удалённая работа',
        '5' => 'Вахтовый метод',
    ];
}