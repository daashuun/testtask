<?php

use yii\helpers\Html;
use yii\helpers\Helper;

$this->title = 'Резюме';

?>
<div class="content p-rel">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="mt8 mb32"><img src="/images/blue-left-arrow.svg" alt="arrow">
                    <?= Html::a('Список резюме', $back, ['id' => 'back']) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-5 mobile-mb32">
                <div class="profile-foto resume-profile-foto"><img src="/images/photo/<?=$resume->photo?>" alt="profile-foto">
                </div>
            </div>
            <div class="col-lg-8 col-md-7">
                <div class="main-title d-md-flex justify-content-between align-items-center mobile-mb16">
                    <?=$resume->getSpecialization()?>
                </div>
                <div class="paragraph-lead mb16">
                    <span class="mr24"><?=$resume->salary?> ₽</span>
                    <span><?=$resume->getExperience()?></span>
                </div>
                <div class="profile-info company-profile-info resume-view__info-blick">
                    <div class="profile-info__block company-profile-info__block mb8">
                        <div class="profile-info__block-left company-profile-info__block-left">Имя
                        </div>
                        <div class="profile-info__block-right company-profile-info__block-right">
                        <?=$resume->surname?> <?=$resume->name?> <?=$resume->middlename?>
                        </div>
                    </div>
                    <div class="profile-info__block company-profile-info__block mb8">
                        <div class="profile-info__block-left company-profile-info__block-left">Возраст
                        </div>
                        <div class="profile-info__block-right company-profile-info__block-right"><?=$resume->getFullYears()?></div>
                    </div>
                    <div class="profile-info__block company-profile-info__block mb8">
                        <div class="profile-info__block-left company-profile-info__block-left">Занятость</div>
                        <div class="profile-info__block-right company-profile-info__block-right">
                        <?=$resume->getEmployment()?>
                        </div>
                    </div>
                    <div class="profile-info__block company-profile-info__block mb8">
                        <div class="profile-info__block-left company-profile-info__block-left">График работы
                        </div>
                        <div class="profile-info__block-right company-profile-info__block-right">
                        <?=$resume->getSchedule()?>
                        </div>
                    </div>
                    <div class="profile-info__block company-profile-info__block mb8">
                        <div class="profile-info__block-left company-profile-info__block-left">Город проживания
                        </div>
                        <div class="profile-info__block-right company-profile-info__block-right"><?=$resume->getSity()?></div>
                    </div>
                    <div class="profile-info__block company-profile-info__block mb8">
                        <div class="profile-info__block-left company-profile-info__block-left">
                            Электронная почта
                        </div>
                        <div class="profile-info__block-right company-profile-info__block-right">
                            <?= Html::a($resume->email, 'mailto:'.$resume->email) ?></div>
                    </div>
                    <div class="profile-info__block company-profile-info__block mb8">
                        <div class="profile-info__block-left company-profile-info__block-left">
                            Телефон
                        </div>
                        
                        <div class="profile-info__block-right company-profile-info__block-right">
                            <?= Html::a($resume->phone, 'tel:'.$resume->phone) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="devide-border mb32 mt50"></div>
                <div class="tabs mb50">
                    <div class="tabs__content active">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="row mb16">
                                    <div class="col-lg-12"><h3 class="heading mb16">
                                    <?= $resume->getExperience() ?></h3></div>
                                        <?php if ($resume->experience ) :?>
                                        <?php foreach ($resume->works as $id=>$work) : ?>
                                    <div class="col-md-4 mb16">
                                        <div class="paragraph tbold mb8"><?=$resume->getWorkStart($id)?> — <?=$resume->getWorkEnd($id)?></div>
                                        <div class="mini-paragraph"><?=Helper::dateToString($work['time'])?></div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="paragraph tbold mb8"><?=$work['company']?></div>
                                        <div class="paragraph tbold mb8"><?=$work['position']?>
                                        </div>
                                        <div class="paragraph">
                                            <?=$work['duties']?>
                                        </div>
                                    </div>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="company-profile-text mb64">
                                    <h3 class="heading mb16">Обо мне</h3>
                                    <p><?=$resume->about?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>