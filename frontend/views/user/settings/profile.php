<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use dektrium\user\helpers\Timezone;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ToggleButtonGroup;
use agapofff\gallery\widgets\Gallery;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $profile
 */

$this->title = Yii::t('front', 'Профиль');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid mt-3 mt-sm-4 mt-md-5 mt-lg-6 mt-xl-7">    
    <div class="row justify-content-center justify-content-lg-start">
        <div class="col-sm-3 col-md-3 col-lg-2 col-xl-2 offset-xl-1 d-none d-md-block">
            <?= $this->render('@frontend/views/user/settings/_menu') ?>
        </div>
        <div class="col-sm-11 col-md-9 col-lg-8 col-xl-6">
            <div class="row justify-content-center">
                <div class="col-xxl-10">
                    <h1 class="gotham font-weight-bold text-uppercase headline mb-3 mb-md-5">
                        <?= $this->title ?>
                    </h1>
                    
                    <?php 
                        $form = ActiveForm::begin([
                            'id' => 'account-form',
                            'action' => '/user/settings/account',
                            'enableAjaxValidation' => true,
                            'enableClientValidation' => true,
                            'validateOnBlur' => true,
                            'validateOnType' => true,
                            'validateOnChange' => true,
                            'fieldConfig' => [
                                'options' => [
                                    'class' => 'form-group row align-items-center mb-1 mb-sm-2',
                                ],
                                'labelOptions' => [
                                    'class' => 'col-sm-3 mb-0'
                                ],
                                'template' => '{label}<div class="col-sm-9">{input}</div><div class="col-sm-9 offset-sm-3"><div class="row justify-content-between"><div class="col-auto"><small>{hint}</small></div><div class="col-auto text-right"><small>{error}</small></div></div></div>',
                                'inputOptions' => [
                                    'class' => 'form-control py-1_75 px-1_5',
                                    'autocomplete' => rand(),
                                ],
                            ],
                        ]);
                    ?>

                        <?= $form
                                ->field($settings, 'first_name', [
                                    'inputOptions' => [
                                        'placeholder' => Yii::t('front', 'Ваше имя'),
                                        'required' => true,
                                    ]
                                ])
                        ?>
                        
                        <?= $form
                                ->field($settings, 'phone', [
                                    'inputOptions' => [
                                        'class' => 'form-control py-1_75 px-1_5 phone-mask',
                                        'required' => true,
                                    ]
                                ])
                        ?>
                        
                        <?= $form
                                ->field($settings, 'email', [
                                    'inputOptions' => [
                                        'placeholder' => Yii::t('front', 'Ваш e-mail'),
                                        'required' => true,
                                    ]
                                ])
                                ->input('email')
                        ?>
                        
                        <?= $form
                                ->field($settings, 'current_password', [
                                    'inputOptions' => [
                                        'required' => false,
                                    ]
                                ])
                                ->passwordInput()
                        ?>
                        
                        <?= $form
                                ->field($settings, 'new_password')
                                ->passwordInput()
                        ?>
                                
                        <div class="row mt-2 mb-2">
                            <div class="col-sm-9 offset-sm-3">
                                <div class="row">
                                    <div class="col-sm-6 mb-1">
                                        <?= Html::submitButton(Yii::t('front', 'Сохранить'), [
                                                'class' => 'btn btn-primary btn-block gotham px-2 py-1',
                                            ]) 
                                        ?>
                                    </div>
                                    <div class="col-sm-6 mb-1">
                                        <?= Html::a(Yii::t('front', 'Отмена'), ['/account'], [
                                                'class' => 'btn btn-warning btn-block gotham text-white px-2 py-1',
                                            ])
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal fade" id="sms-code-modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-0 pb-0">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <img src="/images/modal_close.svg">
                                        </button>
                                    </div>
                                    <div class="modal-body pt-0">
                                        <h5 class="modal-title text-center mb-2">
                                            <?= Yii::t('front', 'Подтвердите Ваш номер телефона') ?>
                                        </h5>
                                        <p class="text-center mb-2">
                                            <?= Yii::t('front', 'Введите смс-код из сообщения, отправленного на указанный Вами номер телефона') ?>
                                        </p>
                                        <?= $form
                                                ->field($settings, 'sms_code', [
                                                    'inputOptions' => [
                                                        'autofocus' => 'autofocus',
                                                        'class' => 'form-control form-control-lg text-center',
                                                        'tabindex' => '7',
                                                        'autocomplete' => rand(),
                                                        'style' => '
                                                            font-family: monospace;
                                                            font-size: 250%;
                                                            padding: 0.1em;
                                                            height: auto;
                                                        ',
                                                        'oninput' => "this.value=this.value.replace(/[^\d]/,'')",
                                                    ],
                                                    'options' => [
                                                        'class' => 'form-group row align-items-center justify-content-center mb-2',
                                                    ],
                                                    'labelOptions' => [
                                                        'class' => 'col-md-3 mb-md-0 font-weight-bold'
                                                    ],
                                                    'template' => '<div class="col-10 col-md-8 text-center">{input}{hint}{error}</div>',
                                                ])
                                                ->label(false)
                                        ?>
                                        <div class="text-center mb-0_5">
                                            <button type="submit" class="btn btn-secondary btn-lg rounded-pill">
                                                <?= Yii::t('front', 'Подтвердить') ?>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button id="sms-code-button" type="button" class="btn btn-outline-secondary btn-lg rounded-pill">
                                            <?= Yii::t('front', 'Отправить СМС-код') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    $this->registerJs("
        // дата рождения
        $(document).on('input', '.birthday', function () {
            if ($('#year').val() && $('#month').val() && $('#day').val()) {
                $('#profile-birthday').val($('#year').val() + '-' + $('#month').val() + '-' + $('#day').val());
            }
        });
        
        // загрузка файлов
        $(document).on('change', 'input[type=\"file\"]', function () {
            $('input[name=\"saveAndExit\"]').val(0);
            $(this).parents('form').submit();
        });
        
        
        // SMS
        
        var time = 60;
        
        $('#account-form')
            .on('beforeSubmit', function (event) {
                event.preventDefault();
                if ($('#settings-form-phone').val() != '" . $settings->phone . "' && !$('#settings-form-sms_code').val()) {
                    $('#sms-code-modal').modal('show');
                    sendSmsCode();
                    return false;
                }
            });
            
        $(document).on('click', '#sms-code-button', function () {
            sendSmsCode();
        });
        
        sendSmsCode = function () {
            if (time === 60) {
                var sendCode = $.get('/" . Yii::$app->language . "/sms/get-code', {
                    phone: $('#settings-form-phone').val()
                });
                $('#settings-form-sms_code').val('').focus();
                setTimer();
            }
            return false;
        }
        
        setTimer = function () {
            var timer = setInterval(function () {
                if (time === 0) {
                    $('#sms-code-button')
                        .removeAttr('disabled')
                        .text('" . Yii::t('front', 'Отправить СМС-код') . "');
                    time = 60;
                    clearInterval(timer);
                    return false;
                } else if (time === 60) {
                    $('#sms-code-button')
                        .attr('disabled', true)
                        .text('" . Yii::t('front', 'Подождите') . " ' + time + ' " . Yii::t('front', 'сек.') . "');
                } else {
                    $('#sms-code-button').text('" . Yii::t('front', 'Подождите') . " ' + time + ' " . Yii::t('front', 'сек.') . "');
                }
                time = time - 1;
            }, 1000);
        }
        
        $('#sms-code-modal').on('shown.bs.modal', function (event) {
            $('#settings-form-sms_code').focus();
        });
        
        $('#sms-code-modal').on('hidden.bs.modal', function (event) {
            $('#settings-form-sms_code').val('');
        });
    ", View::POS_READY);
?>