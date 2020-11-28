<?php 

use yii\bootstrap\ActiveForm;
use app\models\ResumeForm;

?>
<div id = "exp<?=$id?>" class = 'exp'>
<?php ($form) ? $form : $form = new ActiveForm(); 
    $config = [
        'template' => 
        "<div class='row mb16'>
            <div class='col-lg-2 col-md-3'>
                <div class='paragraph'>
                    {label}\n
                </div>
            </div>
            <div class='col-lg-3 col-md-4 col-11'>
                {input}\n
                <span class='my'></span>
                {hint}\n
                {error}\n
            </div>
        </div>"
        ]; ?>
    <?php $this->registerJs(
        "if ($('#exp').children().first().attr('id') != ('exp'+$id)) {
            var b = `
            <div class='row mb24'>
                <div class='col-lg-2 col-md-3'>
                </div>
                <div class='col-lg-4 col-md-6 col-12'>
                    <div class='devide-border'></div>
                </div>
            </div>
            `;
            $(b).prependTo('#exp$id');
        }"); ?>
    <div class='row mb24'>
        <div class='col-lg-2 col-md-3'>
            <div class='paragraph'>Начало работы</div>
        </div>
        <div class='col-lg-3 col-md-4 col-11'>
            <?= $form->field($exp, '['. $id .']startMonth', $options = [
                    'template' => 
                    "
                    <div class='d-flex justify-content-between'>
                        <div class='citizenship-select w100 mr16'>
                            {input}\n
                        </div>
                        <div class='citizenship-select w100'>
                            ". $form->field($exp, '['. $id .']startYear', $options = [
                                'template' => "<div class='citizenship-select w100'>{input}\n<span class='my'></span>{hint}\n{error}\n</div>"
                                ])->input('number', [
                                'class' => 'require dor-input w100',
                                'placeholder' => '2006',
                                'aria-required' => 'true',
                            ]) ."
                        </div>
                    </div>
                    {hint}\n
                    {error}\n"
                    ])->listBox(ResumeForm::MonthList(),
                        [
                            'size' => '1',
                            'class' => 'nselect-1'.$id,
                            'data-title' => "",
                        ]); ?>
        </div>
    </div>
    <div class='row mb8 end<?=$id?>'>
        <div class='col-lg-2 col-md-3'>
            <div class='paragraph'>Окончание работы</div>
        </div>
        <div class='col-lg-3 col-md-4 col-11'>
        <?= $form->field($exp, '['. $id .']endMonth', $options = [
                        'template' => 
                        "
                        <div class='d-flex justify-content-between'>
                            <div class='citizenship-select w100 mr16'>
                                {input}\n
                            </div>
                            <div class='citizenship-select w100'>
                                ". $form->field($exp, '['. $id .']endYear', $options = [
                                    'template' => "<div class='citizenship-select w100'>{input}\n<span class='my'></span>{hint}\n{error}\n</div>"
                                    ])->input('number', [
                                    'class' => 'require dor-input w100',
                                    'placeholder' => '2006',
                                    'aria-required' => 'true',
                                ]) ."
                            </div>
                        </div>
                        {hint}\n
                        {error}\n"
                        ])->listBox(ResumeForm::MonthList(),
                            [
                                'size' => '1',
                                'class' => 'nselect-1'.$id,
                                'data-title' => "",
                            ]); ?>
        </div>
    </div>
    <?= $form->field($exp, '['. $id .']now', [
        'template' => "
            <div class='row mb32'>
                <div class='col-lg-2 col-md-3'>
                    <div class='paragraph'>
                    </div>
                </div>
                <div class='col-lg-3 col-md-4 col-11'>
                    <div class='profile-info'>
                        {input}\n
                        {label}\n
                    </div>
                </div>
            </div>
        "
    ])->label('По настоящее время', ['class'=>"profile-info__check-text job-resolution-checkbox"])->checkbox(['class'=>'now', 'id' => 'now'.$id], false); ?>
    <?= $form->field($exp, '['. $id .']company', $config)->label('Организация')->textInput(['class' => 'require dor-input w100','aria-required' => 'true']); ?>
    <?= $form->field($exp, '['. $id .']position', $config)->label('Должность')->textInput(['class' => 'require dor-input w100','aria-required' => 'true']); ?>
    <?= $form->field($exp, '['. $id .']duties', $options = [
    'template' => 
    "<div class='row mb16 mobile-mb32'>
        <div class='col-lg-2 col-md-3'>
            <div class='paragraph'>{label}</div>
        </div>\n
        <div class='col-lg-4 col-md-6 col-12'>
            {input}
            <div class='delete'><a>Удалить место работы</a></div>
        </div>\n
        {hint}\n
        {error}\n
    </div>"
    ])->label('Обязанности, функции, достижения')->textarea(['class' => 'dor-input w100 h96 mb8', 'placeholder' => "Расскажите о своих обязанностях, функциях и достижениях"]); ?>
    
    <?php if ($exp['now']) $this->registerJs("now($id)") ?>
    <?php $this->registerJs("$('.nselect-1'+ $id).nSelect();") ?>
    <?php $this->registerJs("$('#add').addClass('is_visible');"); ?>

</div>