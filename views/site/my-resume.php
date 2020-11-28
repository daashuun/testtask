<?php


$this->title = 'Мои резюме';
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="content">
    <div class="container">
        <div class="col-lg-9">
            <div class="main-title mb32 mt50 d-flex justify-content-between align-items-center">Мои резюме
                <?= Html::a('Добавить резюме', 'http://yii2/site/new-resume', $options = [
                    'class' => "link-orange-btn orange-btn my-vacancies-add-btn",
                ]);
                Html::a('+', 'http://yii2/site/new', $options = [
                    'class' => "my-vacancies-mobile-add-btn link-orange-btn orange-btn plus-btn",
                ]); ?>
            </div>
            <div class="tabs mb64">
                <div class="tabs__content active">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex">
                                <div class="paragraph mb8 mr16">У вас <span><?=count($resumes)?></span> резюме</div>
                            </div>
                            <?php foreach (array_reverse($resumes) as $id=>$resume) :?>
                            <div class="vakancy-page-block my-vacancies-block p-rel mb16" id='resume<?=$resume['id']?>'>
                                <div class="row">
                                    <div class="my-resume-dropdown dropdown show mb8">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <img src="/images/dots.svg" alt="dots">
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="dropdownMenuLink">
                                                
                                            <a class="dropdown-item" href="<?=Url::to('http://yii2/site/edit/?id='.$resume['id'])?>">Редактировать</a>
                                            <a class="dropdown-item delete-resume">Удалить</a>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 my-vacancies-block__left-col mb16">
                                        <h2 class="mini-title mb8"><?=$resume['specialization']?></h2>
                                        <div class="d-flex align-items-center flex-wrap mb8 ">
                                            <span class="mr16 paragraph"><?=$resume['salary']?> ₽</span>
                                            <span class="mr16 paragraph"><?=$resume['experience']?></span>
                                            <span class="mr16 paragraph"><?=$resume['sity']?></span>
                                        </div>
                                        <div class="d-flex flex-wrap">
                                            <div class="paragraph mr16">
                                                <strong>Просмотров</strong>
                                                <span class="grey"><?=$resume['view']?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                            class="col-xl-12 d-flex justify-content-between align-items-center flex-wrap">
                                        <div class="d-flex flex-wrap mobile-mb12">
                                            <a class="mr16" href="<?=Url::to('http://yii2/site/show-resume/?id='.$resume['id'])?>">Открыть</a>
                                        </div>
                                        <span class="mini-paragraph cadet-blue">Опубликовано <?=$resume['changed']?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>