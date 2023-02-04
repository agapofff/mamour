<?php

    use dektrium\user\widgets\Connect;
    use dektrium\user\models\LoginForm;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\web\View;

    /**
     * @var yii\web\View $this
     * @var dektrium\user\models\LoginForm $model
     * @var dektrium\user\Module $module
     */

    $this->title = Yii::t('front', 'Авторизация');
    $this->params['breadcrumbs'][] = $this->title;
    
    $this->registerCss('
        .login-by-phone {
            display: none;
        }
    ');

?>

<div class="container-fluid mt-3 mt-sm-4 mt-md-5 mt-lg-6 mt-xl-7">    
    <div class="row justify-content-center">
        <div class="col-sm-11 col-md-9 col-lg-8 col-xl-6">
            <h1 class="montserrat font-weight-bold text-uppercase headline mb-5">
                <?= $this->title ?>
            </h1>

            <?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

            <?php
                $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'action' => '/login',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'validateOnBlur' => false,
                    'validateOnType' => false,
                    'validateOnChange' => false,
                ]); 
            ?>
        
                <div class="login-by-email">
                    <?= $form
                            ->field($model, 'login', [
                                'inputOptions' => [
                                    'autofocus' => 'autofocus',
                                    'class' => 'form-control py-1 px-0 bg-transparent border-top-0 border-left-0 border-right-0 border-dark outline-0 shadow-none montserrat font-weight-bold',
                                    'autocomplete' => rand(),
                                    'tabindex' => '1',
                                    // 'required' => true,
                                    'placeholder' => ' ',
                                ],
                                'options' => [
                                    'class' => 'form-group row align-items-center mb-2',
                                ],
                                'template' => '{label}<div class="col-sm-9">{input}</div><div class="col-sm-9 offset-sm-3"><div class="row justify-content-between"><div class="col-auto"><small>{hint}</small></div><div class="col-auto text-right"><small>{error}</small></div></div></div>',
                                'labelOptions' => [
                                    'class' => 'col-sm-3 mb-0'
                                ]
                            ])
                            ->input('email')
                            ->label(Yii::t('front', 'E-mail'));
                    ?>

                    <?= $form
                            ->field($model, 'password', [
                                'inputOptions' => [
                                    'class' => 'form-control py-1 px-0 bg-transparent border-top-0 border-left-0 border-right-0 border-dark outline-0 shadow-none montserrat font-weight-bold',
                                    'tabindex' => '2',
                                    // 'required' => true,
                                    'autocomplete' => rand(),
                                    'placeholder' => ' ',
                                ],
                                'options' => [
                                    'class' => 'form-group row align-items-center mb-2',
                                ],
                                'template' => '{label}<div class="col-sm-9">{input}</div><div class="col-sm-9 offset-sm-3"><div class="row justify-content-between"><div class="col-auto"><small>{hint}</small></div><div class="col-auto text-right"><small>{error}</small></div></div></div>',
                                'labelOptions' => [
                                    'class' => 'col-sm-3 mb-0'
                                ]
                            ])
                            ->passwordInput()
                            ->label(Yii::t('front', 'Пароль'))
                            ->hint(Html::a(Yii::t('front', 'Забыли пароль?'), ['/request']))
                    ?>
                </div>
                
                <div class="login-by-phone">
                    <?= $form
                            ->field($model, 'phone', [
                                'inputOptions' => [
                                    'class' => 'form-control py-1 px-0 bg-transparent border-top-0 border-left-0 border-right-0 border-dark outline-0 shadow-none montserrat font-weight-bold phone-mask',
                                    'tabindex' => '4',
                                    // 'required' => true,
                                    'autocomplete' => rand(),
                                ],
                                'options' => [
                                    'class' => 'form-group row align-items-center mb-2',
                                ],
                                'template' => '{label}<div class="col-sm-9">{input}</div><div class="col-sm-9 offset-sm-3"><div class="row justify-content-between"><div class="col-auto"><small>{hint}</small></div><div class="col-auto text-right"><small>{error}</small></div></div></div>',
                                'labelOptions' => [
                                    'class' => 'col-sm-3 mb-0'
                                ]
                            ])
                    ?>
                </div>
                
                <?= $form
                        ->field($model, 'type')
                        ->hiddenInput()
                        ->label(false)
                ?>
                
                <?= Html::hiddenInput('lang', Yii::$app->language) ?>
                
                <div class="row mt-2 mb-2">
                    <div class="col-sm-9 offset-sm-3">
                        <div class="row">
                            <div class="col-sm-6 mb-1">
                                <?= Html::submitButton(Yii::t('front', 'Авторизация'), [
                                        'class' => 'btn btn-primary btn-block gotham px-2 py-1',
                                        'tabindex' => '4',
                                        'title' => Yii::t('front', 'Авторизация')
                                    ]) 
                                ?>
                            </div>
                            <div class="col-sm-6 mb-1">
                                <?= Html::a(Yii::t('front', 'Регистрация'), ['/register'], [
                                        'class' => 'btn btn-warning btn-block gotham text-white px-2 py-1',
                                        'tabindex' => '5',
                                        'title' => Yii::t('front', 'Регистрация')
                                    ]) 
                                ?>
                            </div>
                            <div class="col-12 mb-1 text-center">
                                <button type="button" class="btn btn-link login-by-email" onclick="switchLoginForm(1);">
                                    <?= Yii::t('front', 'Войти по СМС-коду') ?>
                                </button>
                                <button type="button" class="btn btn-link login-by-phone" onclick="switchLoginForm(0);">
                                    <?= Yii::t('front', 'Войти по email и паролю') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal fade" id="sms-code-modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-center w-100">
                                    <?= Yii::t('front', 'Подтвердите Ваш номер телефона') ?>
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <img src="/images/modal_close.svg">
                                </button>
                            </div>
                            <div class="modal-body">
                                <p class="text-center mb-1 small">
                                    <?= Yii::t('front', 'Введите смс-код из сообщения, отправленного на указанный Вами номер телефона') ?>
                                </p>
                                <?= $form
                                        ->field($model, 'sms_code', [
                                            'inputOptions' => [
                                                'autofocus' => 'autofocus',
                                                'class' => 'form-control form-control-lg text-center',
                                                'autocomplete' => rand(),
                                                'style' => '
                                                    font-size: 200%;
                                                    letter-spacing: 0.3rem;
                                                ',
                                                'oninput' => "this.value=this.value.replace(/[^\d]/,'')",
                                            ],
                                            'options' => [
                                                'class' => 'form-group row align-items-center justify-content-center mb-1_5',
                                            ],
                                            'labelOptions' => [
                                                'class' => 'col-md-3 mb-md-0 font-weight-bold'
                                            ],
                                            'template' => '<div class="col-10 col-sm-6 text-center">{input}{hint}{error}</div>',
                                        ])
                                        ->label(false)
                                ?>
                                <div class="row justify-content-center mb-0_5">
                                    <div class="col-10 col-sm-6 text-center">
                                        <button type="submit" class="btn btn-primary btn-block px-2 py-1 gotham">
                                            <?= Yii::t('front', 'Подтвердить') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button id="sms-code-button" type="button" class="btn btn-link gotham">
                                    <?= Yii::t('front', 'Отправить СМС-код ещё раз') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?= Connect::widget([
                        'baseAuthUrl' => ['/user/security/auth'],
                    ]) 
                ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<?php
    $this->registerJS("
        switchLoginForm = function (byPhone) {
            $('#login-form-login, #login-form-password, #login-form-phone, #login-form-sms_code').val('');
            if (byPhone) {
                $('.login-by-email').hide();
                $('.login-by-phone').show();
                $('#login-form-type').val('phone');
                $('#login-form-login, #login-form-password').attr('disabled', 'disabled');
                $('#login-form-phone').removeAttr('disabled');
                $('#login-form-phone').focus();
            } else {
                $('.login-by-phone').hide(); 
                $('.login-by-email').show();
                $('#login-form-type').val('email');
                $('#login-form-login, #login-form-password').removeAttr('disabled');
                $('#login-form-phone').attr('disabled', 'disabled');
                $('#login-form-login').focus();
            }
        }
        
        var time = 60;
        
        $('#login-form')
            .on('beforeSubmit', function (event) {
                event.preventDefault();                
                if ($('#login-form-type').val() == 'phone' && !$('#login-form-sms_code').val()) {

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
                    phone: $('#login-form-phone').val()
                });
                $('#login-form-sms_code').val('').focus();
                setTimer();
            }
            return false;
        }
        
        setTimer = function () {
            var timer = setInterval(function () {
                if (time === 0) {
                    $('#sms-code-button')
                        .removeAttr('disabled')
                        .text('" . Yii::t('front', 'Отправить СМС-код ещё раз') . "');
                    time = 60;
                    clearInterval(timer);
                    return false;
                } else if (time === 60) {
                    $('#sms-code-button')
                        .attr('disabled', true)
                        .text('" . Yii::t('front', 'Отправить СМС-код ещё раз') . ': ' . Yii::t('front', 'подождите') . " ' + time + ' " . Yii::t('front', 'сек.') . "');
                } else {
                    $('#sms-code-button').text('" . Yii::t('front', 'Отправить СМС-код ещё раз') . ': ' . Yii::t('front', 'подождите') . " ' + time + ' " . Yii::t('front', 'сек.') . "');
                }
                time = time - 1;
            }, 1000);
        }
        
        $('#sms-code-modal').on('shown.bs.modal', function (event) {
            $('#login-form-sms_code').focus();
        });
        
        $('#sms-code-modal').on('hidden.bs.modal', function (event) {
            $('#login-form-sms_code').val('');
        });
    ",
    View::POS_READY);
?>
