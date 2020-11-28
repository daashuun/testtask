<?php

use app\models\ResumeForm;
use yii\helpers\Html;

$this->title = 'Список резюме';
?>

<div class="header-search">
        <div class="container">
            <div class="header-search__wrap">
                <form class="header-search__form">
                    <a href="#"><img src="/images/dark-search.svg" alt="search"
                                     class="dark-search-icon header-search__icon"></a>
                    <?= Html::input('text', 'text', '', ['class' => "header-search__input",  'placeholder'=>"Поиск по резюме и навыкам", 'id'=>'searchText']) ?>
                    <button type="button" class="blue-btn header-search__btn search">Найти</button>
                </form>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container">
        <h1 class = 'mt24 mb16'></h1>
            <button class="vacancy-filter-btn">Фильтр</button>
            <div class="row">
                <div class="col-lg-9 desctop-992-pr-16">
                    <div id = 'resumes'>
                        <?=$resumes?>
                    </div>
                </div>
                <div class="col-lg-3 desctop-992-pl-16 d-flex flex-column vakancy-page-filter-block vakancy-page-filter-block-dnone" id = 'search'>
                    <div
                            class="vakancy-page-filter-block__row mobile-flex-992 mb24 d-flex justify-content-between align-items-center">
                        <div class="heading">Фильтр</div>
                        <img class="cursor-p" src="/images/big-cancel.svg" alt="cancel">
                    </div>
                    <div class="signin-modal__switch-btns-wrap resume-list__switch-btns-wrap mb16">
                        <?= Html::input('hidden', 'sex') ?>
                        <a href="#" class="signin-modal__switch-btn active" id = 'all'>Все</a>
                        <a href="#" class="signin-modal__switch-btn " id = 'men'>Мужчины</a>
                        <a href="#" class="signin-modal__switch-btn " id = 'women'>Женщины</a>
                    </div>
                    <div class="vakancy-page-filter-block__row mb24 search">
                        <div class="paragraph cadet-blue">Город</div>
                        <div class="citizenship-select">
                        <?= Html::dropDownList('Sity', [] , ['0' => 'Любой', ResumeForm::SityList()], ['class' => 'nselect-1', 'id' => 'sity']) ?>
                        </div>
                    </div>
                    <div class="vakancy-page-filter-block__row mb24">
                        <div class="paragraph cadet-blue">Зарплата</div>
                        <div class="p-rel">
                            <?= Html::input('number', 'salary', '', ['class' => "dor-input w100", 'step' => 0]) ?>
                            <img class="rub-icon" src="/images/rub-icon.svg" alt="rub-icon">
                        </div>
                    </div>
                    <div class="vakancy-page-filter-block__row mb24 search">
                        <div class="paragraph cadet-blue">Специализация</div>
                        <div class="citizenship-select">
                            <?= Html::dropDownList('specialization', [] , ['0' => 'Любая', ResumeForm::SpecList()], ['class' => 'nselect-1', 'id' => 'spec']) ?>
                        </div>
                    </div>
                    <div class="vakancy-page-filter-block__row mb24">
                        <div class="paragraph cadet-blue">Возраст</div>
                        <div class="d-flex">
                            <?= Html::input('number', 'start', '', ['class' => "dor-input w100", 'placeholder'=>"От"]) ?>
                            <?= Html::input('number', 'end', '', ['class' => "dor-input w100", 'placeholder'=>"До"]) ?>
                        </div>
                    </div>
                    <div class="vakancy-page-filter-block__row mb24">
                        <div class="paragraph cadet-blue">Опыт работы</div>
                        <div class="profile-info">
                            <p class = 'hidden' id = 'exp'></p>
                            <?= Html::checkBoxList('exp', [], ['Без опыта', 'От 1 года до 3 лет', 'От 3 лет до 6 лет', 'Более 6 лет'], [
                                'item' => function($index, $label, $name, $checked, $value) {
                                    if ($value==0)
                                        $value = 4;
                                    $return = '<div class="form-check d-flex">';
                                    $return .= '<input type="checkbox" class="form-check-input" id="expCheck' . $value . '" name="'. $name .'" value = "'.$value.'" '.$checked.'>';
                                    $return .= '<label class="form-check-label" for="expCheck'. $value .'"></label>';
                                    $return .= '<label for="expCheck'. $value .'" class="profile-info__check-text job-resolution-checkbox">' . ucwords($label) . '</label>';
                                    $return .= '</div>';
            
                                    return $return;
                                }
                            ]) ?>
                        </div>
                    </div>
                    <div class="vakancy-page-filter-block__row mb24">
                        <div class="paragraph cadet-blue">Тип занятости</div>
                        <div class="profile-info">
                            <p class = 'hidden' id = 'employment'></p>
                            <?= Html::checkBoxList('employment', [array_keys(ResumeForm::EmploymentList())], ResumeForm::EmploymentList(true), [
                                'item' => function($index, $label, $name, $checked, $value) {
                                    $value = $value +1;
                                    $return = '<div class="form-check d-flex">';
                                    $return .= '<input type="checkbox" class="form-check-input" id="exampleCheck' . $value . '" name="'. $name .'" value = "'.$value.'" '.$checked.'>';
                                    $return .= '<label class="form-check-label" for="exampleCheck'. $value .'"></label>';
                                    $return .= '<label for="exampleCheck'. $value .'" class="profile-info__check-text job-resolution-checkbox">' . ucwords($label) . '</label>';
                                    $return .= '</div>';
            
                                    return $return;
                                }
                            ]) ?>
                        </div>
                    </div>
                    <div class="vakancy-page-filter-block__row mb24">
                        <div class="paragraph cadet-blue">График работы</div>
                        <div class="profile-info">
                            <p class = 'hidden' id = 'schedule'></p>
                            <?= Html::checkBoxList('schedule', [array_keys(ResumeForm::ScheduleList())], ResumeForm::ScheduleList(true), [
                                'item' => function($index, $label, $name, $checked, $value) {
                                    $checked = $checked ? 'checked' : '';
                                    $return = '<div class="form-check d-flex">';
                                    $return .= '<input type="checkbox" class="form-check-input" id="scheduleCheck' . $value . '" name="'. $name .'" value = "'.$value.'" '.$checked.'>';
                                    $return .= '<label class="form-check-label" for="scheduleCheck'. $value .'"></label>';
                                    $return .= '<label for="scheduleCheck'. $value .'" class="profile-info__check-text job-resolution-checkbox">' . ucwords($label) . '</label>';
                                    $return .= '</div>';
            
                                    return $return;
                                }
                            ]) ?>
                        </div>
                    </div>
                    <div
                            class="vakancy-page-filter-block__row vakancy-page-filter-block__show-vakancy-btns mb24 d-flex flex-wrap align-items-center mobile-jus-cont-center">
                        <a class="link-orange-btn orange-btn mr24 mobile-mb12 search" href="#">Показать <span>1 230</span>
                            вакансии</a>
                        <a href="#" id = 'searchAll'>Сбросить все</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
