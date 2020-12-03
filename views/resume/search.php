<?php

use yii\widgets\ActiveForm;
use app\models\enums\Sity;
use app\models\enums\Specialization;
use app\models\enums\Schedule;
use app\models\enums\Employment;

?>
<div class="resume-form-search">
    <?php
    $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "
            <div class='vakancy-page-filter-block__row mb24 search'>
                <div class='paragraph cadet-blue '>{label}</div>
                <div class='citizenship-select'>
                    {input}\n
                    {hint}\n
                    {error}\n
                </div>
            </div>"
        ],
    ])
    ?>
        <div class="signin-modal__switch-btns-wrap resume-list__switch-btns-wrap mb16">
            <?= $form->field($search, 'sex')->hiddenInput()->label(false) ?>
            <a href="#" class="signin-modal__switch-btn active" id = 'all'>Все</a>
            <a href="#" class="signin-modal__switch-btn " id = 'men'>Мужчины</a>
            <a href="#" class="signin-modal__switch-btn " id = 'women'>Женщины</a>
        </div>
        <?= $form->field($search, 'sity')->dropDownList(['0' => 'Любой', Sity::listData()], ['class' => 'nselect-1', 'id' => 'sity']) ?>
        <?= $form->field($search, 'salary', $options = [
            'template' => 
            "<div class='vakancy-page-filter-block__row mb24 search'>
                <div class='paragraph cadet-blue '>{label}</div>
                <div class='p-rel'>
                    {input}
                <img class='rub-icon' src='/images/rub-icon.svg' alt='rub-icon'>
                </div>\n
                {hint}\n
                {error}\n
            </div>"
            ])->input('number', ['class' => 'dor-input w100'])?>
        <?= $form->field($search, 'specialization')->dropDownList(['0' => 'Любая', Specialization::listData()], ['class' => 'nselect-1', 'id' => 'spec']) ?>
        <div class="vakancy-page-filter-block__row mb24">
            <div class="paragraph cadet-blue">Возраст</div>
            <div class="d-flex">
                <?= $form->field($search, 'start', $options = [
                    'template' => 
                    "{input}\n{hint}\n{error}\n"
                ])->input('number', ['class' => "dor-input w100", 'placeholder'=>"От"]) ?>
                <?= $form->field($search, 'end', $options = ['template' => "{input}\n{hint}\n{error}\n"])
                    ->input('number', ['class' => "dor-input w100", 'placeholder'=>"До"]) ?>
            </div>
        </div>
        <?php
            $temp = 
            "<div class='vakancy-page-filter-block__row mb24'>
                <div class='paragraph cadet-blue '>{label}</div>
                <div class='profile-info'>
                    {input}
                </div>\n
                {hint}\n
                {error}\n
            </div>";
        ?>
        <?= $form->field($search, 'exp', $options = ['template' => $temp])->checkboxList([
                '4'=>'Без опыта', 
                '1'=>'От 1 года до 3 лет', 
                '2'=>'От 3 лет до 6 лет', 
                '3'=>'Более 6 лет'
            ], [ 'item' => function($index, $label, $name, $checked, $value) {
                    $return = '<div class="form-check d-flex">';
                    $return .= '<input type="checkbox" class="profile-info__check-text" id="exampleCheck' . $value . '" name="'. $name .'" value = "'. $value .'">';
                    $return .= '<label class="form-check-label" for="exampleCheck'. $value .'"></label>';
                    $return .= '<label for="exampleCheck'. $value .'" class="profile-info__check-text job-resolution-checkbox">'. ucwords($label) .'</label>';
                    $return .= '</div>';
                    return $return;
                }
            ]) ?>
        <?= $form->field($search, 'employment', $options = ['template' => $temp])->checkboxList(Employment::listData(), 
                [ 'item' => function($index, $label, $name, $checked, $value) {
                    $return = '<div class="form-check d-flex">';
                    $return .= '<input type="checkbox" class="profile-info__check-text" id="employmentCheck'. $value .'" name="'. $name .'" value = "'. $value .'">';
                    $return .= '<label class="form-check-label" for="employmentCheck'. $value .'"></label>';
                    $return .= '<label for="employmentCheck'. $value .'" class="profile-info__check-text job-resolution-checkbox">'. ucwords($label) .'</label>';
                    $return .= '</div>';
                    return $return;
                }
            ]) ?>
        <?= $form->field($search, 'schedule', $options = ['template' => $temp])->checkboxList(Schedule::listData(), 
            [ 'item' => function($index, $label, $name, $checked, $value) {
                $return = '<div class="form-check d-flex">';
                $return .= '<input type="checkbox" class="profile-info__check-text" id="scheduleCheck'. $value .'" name="'. $name .'" value = "'. $value .'">';
                $return .= '<label class="form-check-label" for="scheduleCheck'. $value .'"></label>';
                $return .= '<label for="scheduleCheck'. $value .'" class="profile-info__check-text job-resolution-checkbox">'. ucwords($label) .'</label>';
                $return .= '</div>';
                return $return;
            }
        ]) ?>
        <div
                class="vakancy-page-filter-block__row vakancy-page-filter-block__show-vakancy-btns mb24 d-flex flex-wrap align-items-center mobile-jus-cont-center">
            <a class="link-orange-btn orange-btn mr24 mobile-mb12 search" href="#">Показать <span>1 230</span>
                вакансии</a>
            <a href="#" id = 'searchAll'>Сбросить все</a>
        </div>
    <?php ActiveForm::end() ?>
</div>