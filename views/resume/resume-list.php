<?php

use yii\widgets\LinkPager;

?>
<h1 class="main-title mt24 mb16">Результаты поиска</h1>
<div class="d-flex align-items-center flex-wrap mb8">
    <span class="paragraph mr16">Найдено <?= $count ?> резюме</span>
    <div class="vakancy-page-header-dropdowns">
        <div class="vakancy-page-wrap show">
            <a class="vakancy-page-btn vakancy-btn dropdown-toggle" href="#" role="button"
                id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <?php if (key($sort->getAttributeOrders()) == 'salary') {
                    echo 'По убыванию зарплаты';
                } elseif (key($sort->getAttributeOrders()) == 'salaryDown') {
                    echo 'По возрастанию зарплаты';
                } else {
                    echo 'По новизне';
                }
                ?>
                <i class="fas fa-angle-down arrowDown"></i>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="#"><?= $sort->link('changed', ['class'=>'sortLink'])?></a>
                <a class="dropdown-item" href="#"><?= $sort->link('salary', ['class'=>'sortLink'])?></a>
                <a class="dropdown-item" href="#"><?= $sort->link('salaryDown', ['class'=>'sortLink'])?></a>
            </div>
        </div>
    </div>
</div>
<?php if (count($resumes) > 0) { ?>
<?php foreach ($resumes as $id => $resume) : ?>
<div class="vakancy-page-block company-list-search__block resume-list__block p-rel mb16 resume" id = '<?= $resume->id ?>'>
    <div class="company-list-search__block-left">
        <div class="resume-list__block-img mb8">
            <img src="/images/photo/<?= $resume->photo ?>" alt="profile">
        </div>
    </div>
    <div class="company-list-search__block-right">
        <div class="mini-paragraph cadet-blue mobile-mb12">Обновлено <?= $view[$id]->getChanged() ?></div>
        <h3 class="mini-title mobile-off"><?= $view[$id]->getSpecialization() ?></h3>
        <div class="d-flex align-items-center flex-wrap mb8 ">
            <span class="mr16 paragraph"><?= $resume->salary ?> ₽</span>
            <span class="mr16 paragraph"><?= $view[$id]->getExperience() ?></span>
            <span class="mr16 paragraph"><?= $view[$id]->getFullYears() ?></span>
            <span class="mr16 paragraph"><?= $view[$id]->getSity() ?></span>
        </div>
    <?php if ($view[$id]->last) { ?>
        <p class="paragraph tbold mobile-off">Последнее место работы</p>
    <?php } else { ?>
        <p class="paragraph tbold mobile-off">Нет опыта работы</p>
    <?php } ?>
    </div>
    <div class="company-list-search__block-middle">
        <h3 class="mini-title desktop-off">
            <?= $view[$id]->getSpecialization() ?>
        </h3>
        <p class="paragraph mb16 mobile-mb32">
            <?= $view[$id]->getLastWork() ?>
        </p>
    </div>
</div>
<?php endforeach ?>
<?php } else { ?>
    <h3>Резюме не найдено...</h3>
<?php } ?>
<?php 
    echo LinkPager::widget([
        'linkOptions' => [
            'class' => 'page',
        ],
        'pagination' => $pages,
        'maxButtonCount' => 3,
        'hideOnSinglePage' => false,
        'lastPageLabel' => true,
        'firstPageLabel' => true,
        'prevPageLabel' => '<img class="mr8"
                                                src="/images/mini-left-arrow.svg" alt="arrow"> Назад',
        'nextPageLabel' => 'Далее <img class="ml8"
                                                        src="/images/mini-right-arrow.svg" alt="arrow">',
        
    ])
?>