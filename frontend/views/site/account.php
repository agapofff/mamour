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
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $profile
 */

$this->title = Yii::t('front', 'Профиль');
$this->params['breadcrumbs'][] = $this->title;

$inviteLink = Url::to(['/join/' . base64_encode(Yii::$app->user->id)], true);

?>

<div class="container-xxl mt-3">    
    <div class="row justify-content-center">
        <div class="col-sm-11 col-md-10 col-lg-9 col-xl-6 col-xxl-5">
            <h1 class="gotham font-weight-bold text-uppercase headline mb-5">
                <?= $this->title ?>
            </h1>
    
        <a href="#profile" class="h4 position-relative d-block text-uppercase text-decoration-none font-weight-normal text-dark" data-toggle="collapse" aria-expanded="true" aria-controls="profile">
            <?= Yii::t('front', 'Профиль') ?>
            <?= Html::img('/images/arrow_lk_active.svg', [
                    'class' => 'position-absolute top-0 right-0 d-none d-md-block transition',
                ])
            ?>
        </a>
        <div id="profile" class="collapse show" data-parent="#account">
            <p>
                <a href="<?= Url::to(['/account/edit']) ?>" class="text-gray-400">
                    <?= Yii::t('front', 'Редактировать') ?>
                </a>
            </p>
            <div class="row">
                <div class="col-md-3 pt-4">
                    <a href="<?= Url::to(['/account/edit']) ?>">
                        <img src="<?= $user->getImage()->getUrl('400x400') ?>" class="img-fluid rounded-pill border border-teal cursor-pointer file-upload-trigger" style="border-width: 3px !important">
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
                            <?= Yii::t('front', !is_null($profile->sex) ? Yii::t('front', Yii::$app->params['sex'][$profile->sex]) : '') ?>
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
            <p class="lead">
                <?= Yii::t('front', 'Здесь пока пусто') ?>
            </p>
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
            <div class="mb-4 mt-3 px-xl-5">
            <?php
                Pjax::begin([
                    'id' => 'pjax-bonuses',
                    'enablePushState' => false,
                ]);
            ?>
                    <h2 class="mb-3 text-uppercase">
                        <?= Yii::t('front', 'Накопленные бонусы') ?>
                    </h2>
                    <div class="row alig-items-center">
                        <div class="col-md-6">
                            <img src="/images/lk_bonus.png" alt="<?= Yii::$app->id ?>" class="img-fluid">
                        </div>
                        <div class="col-md-6">
                            <div class="row justify-content-center">
                                <div class="col-auto">
                                    <div class="row justify-content-between">
                                        <div class="col-auto">
                                            <p class="text-gray-700 mb-0">
                                                <?= Yii::$app->formatter->asDate('now') ?>
                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#bonuses" class="text-gray-700 mb-0" data-toggle="modal">
                                                <?= Yii::t('front', 'Подробнее') ?>
                                            </a>
                                        </div>
                                    </div>
                                    <p class="display-1 text-secondary font-weight-bold mb-0">
                                        <?= $userBonus['total'] ?> <small class="font-weight-bold">UME</small>
                                    </p>
                                </div>
                                <div class="col-12 mb-1">
                                    <hr>
                                </div>
                                <div class="col-auto">
                                    <h5 class="text-center">
                                        <?= Yii::t('front', 'Сумма бонусов состоит из') ?>:
                                    </h5>
                                    <div class="row justify-content-between">
                                        <div class="col-sm-6 text-center mt-1">
                                            <p class="h2 font-weight-bold text-secondary">
                                                <?= $userBonus['reasons'][1] ?> <small class="font-weight-bold">UME</small>
                                            </p>
                                            <p class="h6">
                                                <?= Yii::t('front', 'Подписки') ?>
                                            </p>
                                        </div>
                                        <div class="col-sm-6 text-center mt-1">
                                            <p class="h2 font-weight-bold text-secondary">
                                                <?= $userBonus['reasons'][0] ?> <small class="font-weight-bold">UME</small>
                                            </p>
                                            <p class="h6">
                                                <?= Yii::t('front', 'Рекомендации') ?>
                                            </p>
                                        </div>
                                        <div class="col-sm-6 text-center mt-1">
                                            <p class="h2 font-weight-bold text-secondary">
                                                <?= $userBonus['reasons'][2] ?> <small class="font-weight-bold">UME</small>
                                            </p>
                                            <p class="h6">
                                                <?= Yii::t('front', 'Подарки') ?>
                                            </p>
                                        </div>
                                        <div class="col-sm-6 text-center mt-1">
                                            <p class="h2 font-weight-bold text-secondary">
                                                <?= $userBonus['reasons'][3] ?> <small class="font-weight-bold">UME</small>
                                            </p>
                                            <p class="h6">
                                                <?= Yii::t('front', 'Другое') ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-3">
                    <h2 class="text-uppercase text-center mb-2">
                        <?= Yii::t('front', 'Куда потратить') ?>
                    </h2>
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <ul class="check-circle">
                                <li class="h6">
                                    <?= Yii::t('front', 'Покупка бустеров') ?>
                                </li>
                                <li class="h6">
                                    <?= Yii::t('front', 'Покупка акуссеуаров') ?>
                                </li>
                            </ul>
                        </div>
                        <div class="col-auto">
                            <ul class="check-circle">
                                <li class="h6">
                                    <?= Yii::t('front', 'Участие в розыгрыше') ?>
                                </li>
                                <li class="h6">
                                    <?= Yii::t('front', 'Поделиться с другом') ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                            
                    <div class="modal fade" id="bonuses" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header pb-0">
                                    <h5 class="modal-title text-center mb-1">
                                        <?= Yii::t('front', 'Бонусный счет') ?>
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <img src="/images/modal_close.svg">
                                    </button>
                                </div>
                                <div class="modal-body pt-0_5">
                                    <table id="bonus-history" class="table mb-0">
                                        <tbody>
                                <?php
                                    if ($userBonus['bonuses']) {
                                        foreach (array_reverse($userBonus['bonuses']) as $bonus) {
                                ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?= Yii::$app->formatter->asDate($bonus->created_at) ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= $bonus->type ? '+' : '-' ?><?= $bonus->amount ?> <small>UME</small>
                                                </td>
                                                <td class="text-center">
                                                    <?= Yii::t('front', Yii::$app->params['bonus'][$bonus->type][$bonus->reason]) ?>
                                                </td>
                                            </tr>
                                <?php
                                        }
                                    }
                                ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <p class="h5 font-weight-bold mb-0">
                                        <?= $userBonus['total'] ?> <small class="font-weight-bold">UME</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                Pjax::end();
            ?>
            </div>
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
            <div class="mb-4 mt-3 px-xl-5">
        <?php
            if ($friends) {
                Pjax::begin([
                    'id' => 'pjax-friends',
                    'enablePushState' => false,
                ]);
        ?>
                    <h2 class="mb-3 text-uppercase">
                        <?= Yii::t('front', 'Оформите подарок другу') ?>
                    </h2>
                    <p class="lead font-weight-bold mb-3">
                        <?= Yii::t('front', 'Выберите сумму для перевода') ?>
                    </p>
                    <div class="mb-1">
                <?php
                    foreach ($friends as $f => $friend) {
                ?>
                        <div class="media my-2">
                            <img src="<?= $friend->getImage()->getUrl('100x100') ?>" class="rounded-pill">
                            <div class="media-body ml-2">
                                <div class="row align-items-baseline">
                                    <div class="col-auto">
                                        <h4 class="d-inline mb-0 mr-0_5">
                                            <?= $friend->profile->name ?: ($friend->profile->first_name ?: $friend->username) ?>
                                        </h4>
                                <?php
                                    if ($friend->profile->breed) {
                                ?>
                                        <h6 class="d-inline">
                                            <?= $friend->profile->breed ? json_decode(ArrayHelper::getValue($breeds, $friend->profile->breed.'.name'))->{Yii::$app->language} : '' ?>
                                            <?= $friend->profile->breed && $friend->profile->birthday ? ', ' : '' ?>
                                            <?= $friend->profile->birthday ? explode(',', Yii::$app->formatter->asDuration((new DateTime())->setTimestamp(time())->diff(new DateTime($friend->profile->birthday)), ',', ''))[0] : '' ?>
                                        </h6>
                                <?php
                                    }
                                ?>
                                    </div>
                                </div>
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto">
                                        <div class="row align-items-center">
                                    <?php
                                        for ($i = 1; $i < 6; $i++) {
                                    ?>
                                            <div class="col-12 col-sm-auto pr-0_5">
                                                <div role="radiogroup">
                                                    <div class="custom-control custom-radio custom-radio-small custom-radio-secondary my-1">
                                                        <input type="radio" id="bonus-gift-<?= $friend->id ?>-<?= $i ?>" name="bonus-gift-<?= $friend->id ?>" class="custom-control-input" value="<?= $i ?>" <?= $i == 1 ? 'checked' : '' ?>>
                                                        <label class="custom-control-label h5 font-weight-bold text-secondary" for="bonus-gift-<?= $friend->id ?>-<?= $i ?>">
                                                            <?= $i ?> <small class="text-uppercase font-weight-bold">ume</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                        </div>
                                    </div>
                                    <div class="col-auto py-0_5">
                                        <button type="button" class="btn btn-secondary btn-lg rounded-pill bonus-gift" data-user="<?= $friend->id ?>" data-url="<?= Url::to(['/bonus/gift'], true) ?>">
                                            <?= Yii::t('front', 'Подарить') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        if ($f < count($friends)-1){
                    ?>
                        <hr>
                <?php
                        }
                    }
                ?>
                    </div>
        <?php
                Pjax::end();
            }
        ?>
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-secondary btn-lg rounded-pill" data-toggle="modal" data-target="#invite">
                        <?= Yii::t('front', 'Пригласить друзей') ?>
                    </button>
                </div>
            </div>
            
            <div class="modal fade" id="invite" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title text-center mb-2">
                                <?= Yii::t('front', 'Пригласить друзей') ?>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <img src="/images/modal_close.svg">
                            </button>
                        </div>
                        <div class="modal-body pt-0">
                            <div class="form-group">
                                <label class="form-control-label">
                                    <?= Yii::t('front', 'Отправьте эту ссылку Вашим друзьям и знакомым:') ?>
                                </label>
                                <input type="text" value="<?= $inviteLink ?>" class="form-control form-control-lg copy" id="invite-input" data-text="<?= $inviteLink ?>" data-success="<?= Yii::t('front', 'Ссылка скопирована') ?>" data-error="<?= Yii::t('front', 'Произошла ошибка! Пожалуйста, попробуйте еще раз чуть позже') ?>')" readonly>
                                <div class="help text-right">
                                    <button type="button" class="btn btn-link p-0 copy" data-text="<?= $inviteLink ?>" data-success="<?= Yii::t('front', 'Ссылка скопирована') ?>" data-error="<?= Yii::t('front', 'Произошла ошибка! Пожалуйста, попробуйте еще раз чуть позже') ?>')">
                                        <?= Yii::t('front', 'Скопировать ссылку') ?>
                                    </button>
                                </div>
                            </div>
                            <div class="text-center mt-1 mb-0_5">
                                <p class="text-center lead m-0">
                                    <?= Yii::t('front', 'Отправить') ?>
                                </p>
                                <script src="https://yastatic.net/share2/share.js"></script>
                                <div class="ya-share2" 
                                    data-size="l" 
                                    data-shape="round" 
                                    data-color-scheme="whiteblack" 
                                    data-services="vkontakte,odnoklassniki,telegram,twitter,viber,whatsapp" 
                                    data-copy="hidden" 
                                    data-description="<?= Yii::t('front', 'Здравствуйте') ?>! <?= Yii::t('front', 'Приглашаю вас в {0}', [Yii::$app->name]) ?>: <?= $inviteLink ?>" 
                                    data-image="<?= Url::to(['/images/share1.jpg'], true) ?>" 
                                    data-lang="<?= Yii::$app->language ?>" 
                                    data-title="<?= Yii::t('front', 'Приглашение в {0}', [Yii::$app->name]) ?>" 
                                    data-url="<?= $inviteLink ?>"
                                ></div>
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
            if ($actions){
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