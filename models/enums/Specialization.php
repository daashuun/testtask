<?php 

namespace app\models\enums;

use yii2mod\enum\helpers\BaseEnum;

/**
 * List of specialization's options
 */
class Specialization extends BaseEnum 
{
    /**
     * Specialization's options
     * @var array
     */
    public static $list = [
        '1' => 'Администратор баз данных',
        '2' => 'Аналитик',
        '3' => 'Арт-директор',
        '4' => 'Инженер',
        '5' => 'Компьютерная безопасность',
        '6' => 'Контент',
        '7' => 'Маркетинг',
        '8' => 'Мультимедиа',
        '9' => 'Оптимизация сайта (SEO)',
        '10' => 'Передача данных и доступ в интернет',
        '11' => 'Программирование, Разработка',
        '12' => 'Продажи',
        '13' => 'Продюсер',
        '14' => 'Развитие бизнеса',
        '15' => 'Системный администратор',
        '16' => 'Системы управления предприятием (ERP)',
        '17' => 'Сотовые, Беспроводные технологии',
        '18' => 'Стартапы',
        '19' => 'Телекоммуникации',
        '20' => 'Тестирование',
        '21' => 'Технический писатель',
        '22' => 'Управление проектами',
        '23' => 'Электронная коммерция',
        '24' => 'CRM системы',
        '25' => 'Web инженер',
        '26' => 'Web мастер',
    ];
}