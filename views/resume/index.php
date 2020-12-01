<?php

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
                    <?= $this->render('search', compact('search')); ?>
                </div>
            </div>
        </div>
    </div>
