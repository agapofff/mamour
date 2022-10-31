<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Url;
use yii\helpers\Html;
use dektrium\user\helpers\Timezone;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $profile
 */

$this->title = Yii::t('front', 'Профиль');
$this->params['breadcrumbs'][] = $this->title;

$inviteLink = Url::to(['register', 'referal' => base64_encode(Yii::$app->user->id)]);

?>

<div class="container-lg container-xl container-xxl">
    <h1 class="text-uppercase mb-2 wow fadeIn" data-wow-duration="0.5s">
        <?= Yii::t('front', 'Личный кабинет') ?>
    </h1>
    
    <div id="account" class="accordion">
    
        <a href="#profile" class="h4 position-relative d-block text-uppercase text-decoration-none font-weight-normal text-dark" data-toggle="collapse" aria-expanded="true" aria-controls="profile">
            <?= Yii::t('front', 'Профиль') ?>
            <?= Html::img('/images/arrow_lk_active.svg', [
                    'class' => 'position-absolute top-0 right-0 d-none d-md-block transition',
                ])
            ?>
        </a>
        <div id="profile" class="collapse show" data-parent="#account">
            <p>
                <a href="<?= '#' // Url::to(['/account/edit']) ?>" class="text-gray-400">
                    <?= Yii::t('front', 'Редактировать') ?>
                </a>
            </p>
            <div class="row">
                <div class="col-md-3 pt-4">
                    <a href="<?= '#' // Url::to(['/account/edit']) ?>" class="rounded-pill border border-teal d-block" style="border-width: 3px !important">
                        <img src="<?= $user->getImage()->getUrl('400') ?>" class="img-fluid" class="rounded-pill">
                    </a>
                </div>
                <div class="col-md-8 offset-md-1">
                    <h2 class="mb-2">
                        <?= $profile->name ?: Yii::t('front', 'Мой питомец') ?>
                    </h2>
                    <div class="row mb-0_5">
                        <div class="col-4 font-weight-bold">
                            <?= Yii::t('front', 'Пол') ?>
                        </div>
                        <div class="col-8">
                            <?= Yii::t('front', $profile->sex ? Yii::t('front', Yii::$app->params['sex'][$profile->sex]) : '') ?>
                        </div>
                    </div>
                    <div class="row mb-0_5">
                        <div class="col-4 font-weight-bold">
                            <?= Yii::t('front', 'Порода') ?>
                        </div>
                        <div class="col-8">
                            <?= json_decode($breed)->{Yii::$app->language} ?>
                        </div>
                    </div>
                    <div class="row mb-0_5">
                        <div class="col-4 font-weight-bold">
                            <?= Yii::t('front', 'Дата рождения') ?>
                        </div>
                        <div class="col-8">
                            <?= $profile->birthday ? Yii::$app->formatter->asDate($profile->birthday, 'dd.MM.yyyy') : '' ?>
                        </div>
                    </div>
                    <div class="row mb-0_5">
                        <div class="col-4 font-weight-bold">
                            <?= Yii::t('front', 'Вес') ?>
                        </div>
                        <div class="col-8">
                            <?= $profile->weight ? $profile->weight . ' ' . Yii::t('front', 'кг') : '' ?>
                        </div>
                    </div>
                    <div class="row mb-0_5">
                        <div class="col-4 font-weight-bold">
                            <?= Yii::t('front', 'Активность') ?>
                        </div>
                        <div class="col-8">
                            <?= $profile->activity ? Yii::t('front', Yii::$app->params['activity'][$profile->activity]) : '' ?>
                        </div>
                    </div>
                    <hr>
                    <h4 class="mt-2 mb-1_5">
                        <?= Yii::t('front', 'Хозяин') ?>
                    </h4>
                    <div class="row mb-0_5">
                        <div class="col-4 font-weight-bold">
                            <?= Yii::t('front', 'ФИО') ?>
                        </div>
                        <div class="col-8">
                            <?= implode(' ', [$profile->first_name, $profile->last_name]) ?>
                        </div>
                    </div>
                    <div class="row mb-0_5">
                        <div class="col-4 font-weight-bold">
                            <?= Yii::t('front', 'Телефон') ?>
                        </div>
                        <div class="col-8">
                            <?= $profile->phone ?>
                        </div>
                    </div>
                    <div class="row mb-0_5">
                        <div class="col-4 font-weight-bold">
                            <?= Yii::t('front', 'E-mail') ?>
                        </div>
                        <div class="col-8">
                            <?= $user->email ?>
                        </div>
                    </div>
                    <hr>
                    <h4 class="mt-2 mb-1_5">
                        <?= Yii::t('front', 'Подписка') ?>
                    </h4>
                </div>
            </div>
        </div>
        
        <hr>
        
        <a href="#orders" class="h4 position-relative d-block text-uppercase text-decoration-none font-weight-normal text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="orders">
            <?= Yii::t('front', 'Покупки') ?>
            <?= Html::img('/images/arrow_lk_active.svg', [
                    'class' => 'position-absolute top-0 right-0 d-none d-md-block transition',
                ])
            ?>
        </a>
        <div id="orders" class="collapse" data-parent="#account">
            <p>&nbsp;</p>
        </div>
        
        <hr>
        
        <a href="#bonus" class="h4 position-relative d-block text-uppercase text-decoration-none font-weight-normal text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="bonus">
            <?= Yii::t('front', 'Кошелек') ?>
            <?= Html::img('/images/arrow_lk_active.svg', [
                    'class' => 'position-absolute top-0 right-0 d-none d-md-block transition',
                ])
            ?>
        </a>
        <div id="bonus" class="collapse" data-parent="#account">
            <p>&nbsp;</p>
        </div>
        
        <hr>
        
        <a href="#friends" class="h4 position-relative d-block text-uppercase text-decoration-none font-weight-normal text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="friends">
            <?= Yii::t('front', 'Друзья') ?>
            <?= Html::img('/images/arrow_lk_active.svg', [
                    'class' => 'position-absolute top-0 right-0 d-none d-md-block transition',
                ])
            ?>
        </a>
        <div id="friends" class="collapse" data-parent="#account">
        
            <div class="text-center">
                <button type="button" class="btn btn-secondary btn-lg rounded-pill" data-toggle="modal" data-target="@invite">
                    <?= Yii::t('front', 'Пригласить') ?>
                </button>
            </div>
            
            <div class="modal fade" id="sms-code-modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <img src="/images/modal_close.svg">
                            </button>
                            <h5 class="modal-title text-center mb-2">
                                <?= Yii::t('front', 'Пригласить') ?>
                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-control-label">
                                    <?= Yii::t('front', 'Отправьте эту ссылку Вашим друзьям и знакомым:') ?>
                                </label>
                                <input type="text" value="<?= $inviteLink ?>" class="form-control copy" id="invite-input" data-copy="<?= $inviteLink ?>">
                                <div class="valid-feedback">
                                    <a href="#" class="copy" data-copy="<?= $inviteLink ?>">
                                        <?= Yii::t('front', 'Скопировать ссылку') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <hr>
        
        <a href="#actions" class="h4 position-relative d-block text-uppercase text-decoration-none font-weight-normal text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="actions">
            <?= Yii::t('front', 'Акции') ?>
            <?= Html::img('/images/arrow_lk_active.svg', [
                    'class' => 'position-absolute top-0 right-0 d-none d-md-block transition',
                ])
            ?>
        </a>
        <div id="actions" class="collapse" data-parent="#account">
            <div class="row mb-4 mt-3 px-xl-5">
        <?php
            foreach ($actions as $action) {
        ?>
                <div class="col-md-6">
                    <?= $this->render('/actions/_post', [
                            'action' => $action
                        ])
                    ?>
                </div>
        <?php
            }
        ?>
                <div class="col-12">
                    <p class="lead my-1">
                        <a href="<?= Url::to(['/actions']) ?>" class="text-dark">
                            <?= Yii::t('front', 'Архив акций') ?>
                        </a>
                    </p>
                </div>
            </div>
        </div>
        
        <hr>
    </div>
</div>