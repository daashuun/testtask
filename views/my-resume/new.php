<?php 

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;
use app\models\enums\Sity;
use app\models\enums\Employment;
use app\models\enums\Schedule;
use app\models\enums\Specialization;
$this->title = 'Создать резюме';

?>
<div class="content p-rel">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="mt8 mb40">
                    <img src="/images/blue-left-arrow.svg" alt="arrow">
                    <?= Html::a('Вернуться без сохранения', 'http://yii2/my-resume') ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-title mb24">Новое резюме</div>
            </div>
        </div>
        <div class="col-12">
            <?php $form = ActiveForm::begin([
                'layout' => 'horizontal',
                'action' => 'http://yii2/my-resume/save',
                'options' => [
                        'class' => 'signup-form form-register1',
                        'id' => 'new',
                        'enctype' => 'multipart/form-data',
                    ],
                'fieldConfig' => [
                    'template' => 
                    "<div class='row mb16'>
                        <div class='col-lg-2 col-md-3 dflex-acenter'>
                            <div class='paragraph'>
                                {label}
                            </div>
                        </div>\n
                        <div class='col-lg-3 col-md-4 col-11'>
                            {input}\n
                            {hint}\n
                            {error}\n
                        </div>
                    </div>",
                    'horizontalCssClasses' => [
                        'label' => '',
                        'error' => '',
                        'hint' => '',
                    ],
                ],
            ]);?>
            <?=$form->field($resume, 'photo')->hiddenInput(['value' => $resume['photo'],])->label(false);?>
            <?=$form->field($resume, 'id')->hiddenInput(['value'=>$resume['id']])->label(false);?>
            <?=$form->field($resume, 'view')->hiddenInput(['value'=>$resume['view']])->label(false);?>
            <?= $form->field($resume, 'foto', $options = [
                'template' => 
                "<div class='row mb32'>
                    <div class=col-lg-2 col-md-3 dflex-acenter>
                        <div class=paragraph>Фото</div>
                    </div>
                    <div class=col-lg-3 col-md-4 col-11>
                        <div class='profile-foto-upload mb8' id='profile'>
                        <img id='profilePhoto' src='".(($resume['photo']) ? '/images/photo/'.$resume['photo'] : '/images/profile-foto.jpg') . "' alt=foto>
                        </div>
                        <div class='row mb16'>    
                            {input}\n
                            {label}\n
                            {hint}\n
                            {error}\n
                        </div>
                    </div>
                </div>",
                ])->label('Изменить фото', ['class' => 'custom-file-upload'])->input('file', [
                    'accept' => 'image/*',
                ]); ?>
            <?= $form->field($resume, 'surname')->textInput(['class' => 'dor-input w100']); ?>
            <?= $form->field($resume, 'name')->textInput(['class' => 'dor-input w100']); ?>
            <?= $form->field($resume, 'middlename')->textInput(['class' => 'dor-input w100']); ?>
            <?= $form->field($resume, 'birtday', $options = [
                    'template' => 
                    "<div class='row mb16'>
                        <div class='col-lg-2 col-md-3 dflex-acenter'>
                            <div class='paragraph'>
                                {label}
                            </div>
                        </div>\n
                        <div class='col-lg-3 col-md-4 col-11'>
                            <div class='datepicker-wrap input-group date'>
                                {input}
                                <img src='/images/mdi_calendar_today.svg'>
                            </div>\n
                            {hint}\n
                            {error}\n
                        </div>
                    </div>"
                    ])->widget(DatePicker::className(), [
                    'options' => [
                        'class'=> 'dor-input dpicker datepicker-input',
                    ],
                ]); ?>
            <?= $form->field($resume, 'sex', $options = [
                'template' => 
                "<div class='row mb16'>
                    <div class='col-lg-2 col-md-3'>
                        <div class='paragraph'>{label}</div>
                    </div>\n
                    <ul class='card-ul-radio profile-radio-list'>
                        {input}
                    </ul>\n
                    {hint}\n
                    {error}\n
                </div>"
                ])->radioList(
                    [
                        '1' => 'Мужской',
                        '0' => 'Женский', 
                    ], [
                        'item' => function($index, $label, $name, $checked, $value) {
                            $return = '<li>';
                            $return .= '<input type="radio" id="test' . $value . '" name="'. $name .'" value="'. $value .'"';
                            $return .= $checked ? 'checked>' :  '>';
                            $return .= '<label for = "test'. $value .'">' . ucwords($label) . '</label>';
                            $return .= '</li>';

                            return $return;
                        }
                    ]
                );?>
            <?= $form->field($resume, 'sity', $options = [
                'template' => 
                "<div class='row mb16'>
                    <div class='col-lg-2 col-md-3 dflex-acenter'>
                        <div class='paragraph'>{label}</div>
                    </div>\n
                    <div class='col-lg-3 col-md-4 col-11'>
                        <div class='citizenship-select'>
                        {input}
                        </div>\n
                    {hint}\n
                    {error}\n
                    </div>
                </div>"
                ])->listBox(Sity::listData(),
                    [
                        'size' => '1',
                        'class' => 'dor-input w100 nselect-1',
                        'data-title' => "",
            ]); ?>
            <div class="row mb16">
                <div class="col-lg-2 col-md-3 dflex-acenter">
                    <div class="heading">Способы связи</div>
                </div>
                <div class="col-lg-7 col-10"></div>
            </div>
            <?= $form->field($resume, 'email')->textInput(['class' => 'dor-input w100']); ?>
            <?= $form->field($resume, 'phone')->textInput([
                'class' => 'dor-input w100',
                'pattern' => '\+7?{0,1}9[0-9]{10}',
                'placeholder' => "+7 ___ ___ __ __",]); ?>
            <div class="row mb24">
                <div class="col-12">
                    <div class="heading">Желаемая должность</div>
                </div>
            </div>
            <?= $form->field($resume, 'specialization', $options = [
                'template' => 
                "<div class='row mb16'>
                    <div class='col-lg-2 col-md-3 dflex-acenter'>
                        <div class='paragraph'>{label}</div>
                    </div>\n
                    <div class='col-lg-3 col-md-4 col-11'>
                        <div class='citizenship-select'>
                        {input}
                        </div>\n
                    {hint}\n
                    {error}\n
                    </div>
                </div>"
                ])->listBox(Specialization::listData(),
                    [
                        'size' => '1',
                        'class' => 'dor-input w100 nselect-1',
                        'data-title' => "",
            ]); ?>
            <?= $form->field($resume, 'salary', $options = [
                    'template' => 
                    "<div class='row mb16'>
                        <div class='col-lg-2 col-md-3 dflex-acenter'>
                            <div class='paragraph'>
                                {label}
                            </div>
                        </div>\n
                        <div class='col-lg-3 col-md-4 col-11'>
                            <div class='p-rel'>
                                {input}
                                <img class='rub-icon' src='/images/rub-icon.svg' alt='rub-icon'>
                            </div>\n
                            {hint}\n
                            {error}\n
                        </div>
                    </div>"
                ])->input('number', [
                    'class' => 'dor-input w100',
                    'placeholder' => "От",
                ]); ?>
            <?= $form->field($resume, 'employment', $options = [
                    'template' => 
                    "<div class='row mb32'>
                        <div class='col-lg-2 col-md-3'>
                            <div class='paragraph'>
                                {label}
                            </div>
                        </div>\n
                        <div class='col-lg-3 col-md-4 col-11'>
                            <div class='profile-info'>
                                {input}
                            </div>\n
                            {hint}\n
                            {error}\n
                        </div>
                    </div>"
                ])->checkboxList(Employment::listData(),
                [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $checked = $checked ? 'checked' : '';
                        $return = '<div class="form-check d-flex">';
                        $return .= '<input type="checkbox" class="form-check-input" id="exampleCheck' . $value . '" name="'. $name .'" value = "'.$value.'" '.$checked.'>';
                        $return .= '<label class="form-check-label" for="exampleCheck'. $value .'"></label>';
                        $return .= '<label for="exampleCheck'. $value .'" class="profile-info__check-text job-resolution-checkbox">' . ucwords($label) . '</label>';
                        $return .= '</div>';

                        return $return;
                    }
                ]); ?>
            <?= $form->field($resume, 'schedule', $options = [
                    'template' => 
                    "<div class='row mb32'>
                        <div class='col-lg-2 col-md-3'>
                            <div class='paragraph'>
                                {label}
                            </div>
                        </div>\n
                        <div class='col-lg-3 col-md-4 col-11'>
                            <div class='profile-info'>
                                {input}
                            </div>\n
                            {hint}\n
                            {error}\n
                        </div>
                    </div>"
                ])->checkboxList(Schedule::listData(),
                [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $checked = $checked ? 'checked' : '';
                        $return = '<div class="form-check d-flex">';
                        $return .= '<input type="checkbox" class="form-check-input" id="scheduleCheck' . $value . '" name="'. $name .'" value = "'.$value.'" '.$checked.'>';
                        $return .= '<label class="form-check-label" for="scheduleCheck'. $value .'"></label>';
                        $return .= '<label for="scheduleCheck'. $value .'" class="profile-info__check-text job-resolution-checkbox">' . ucwords($label) . '</label>';
                        $return .= '</div>';

                        return $return;
                    }
                ]); ?>
            <div class="row mb32">
                <div class="col-12">
                    <div class="heading">Опыт работы</div>
                </div>
            </div>
            <?= $form->field($resume, 'experience', $options = [
                'template' => 
                "<div class='row mb16'>
                    <div class='col-lg-2 col-md-3 dflex-acenter'>
                        <div class='paragraph'>{label}</div>
                    </div>\n
                    <ul class='card-ul-radio profile-radio-list'>
                        {input}
                    </ul>\n
                    {hint}\n
                    {error}\n
                </div>"
                ])->radioList(
                    [
                        '0' => 'Нет опыта работы', 
                        '1' => 'Есть опыт работы'
                    ], [
                        'item' => function($index, $label, $name, $checked, $value) {
                            $return = '<li>';
                            $return .= '<input type="radio"  id="exp' . $value . '" name="'. $name .'" value = "'.$value.'" ';
                            $return .= $checked ? 'checked>' :  '>';
                            $return .= '<label for = "exp'. $value .'">' . ucwords($label) . '</label>';
                            $return .= '</li>';

                            return $return;
                        }
                ]
            );?>
            <div id="exp">

            <?php
                if (count($resume->work)!=0) {
                    $this->context->actionWork($form, $resume->work);
                }
            ?>

            </div>
            <div class='row mb64' id="add">
                <div class='col-lg-2 col-md-3 dflex-acenter'>
                    <div class='paragraph'>
                        <label ></label>
                    </div>
                </div>
                <div class='col-lg-3 col-md-4 col-11'>
                    <div>
                        <a class = 'add'>+ Добавить место работы</a>
                    </div>
                </div>
            </div> 
            <div class="row mb32">
                <div class="col-12">
                    <div class="heading">Расскажите о себе</div>
                </div>
            </div>
            <?= $form->field($resume, 'about', $options = [
                'template' => 
                "<div class='row mb64 mobile-mb32'>
                    <div class='col-lg-2 col-md-3'>
                        <div class='paragraph'>{label}</div>
                    </div>\n
                    <div class='col-lg-5 col-md-7 col-12'>
                        {input}
                    </div>\n
                    {hint}\n
                    {error}\n
                </div>"
                ])->textarea(['class' => 'dor-input w100 h176 mb8']); ?>
            <div class="row mb128 mobile-mb64">
                <div class="col-lg-2 col-md-3">
                </div>
                <div class="col-lg-10 col-md-9">
                    <?= Html::submitButton('Сохранить', ['class' => 'orange-btn link-orange-btn', 'id' => 'sub']); ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
