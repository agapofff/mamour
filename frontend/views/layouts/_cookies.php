<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
?>
<!--
    <div id="cookiesNotification" class="fixed-bottom w-100 fade" data-type="1">
        <div class="vw-100 pb-1 px-1_5">
            <div class="row align-items-center justify-content-end">
                <div class="col-12 col-sm-10 col-md-6 col-lg-5 col-xl-4 col-xxl-3 px-1 py-1 border bg-white text-primary font-weight-light">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <strong><?= Yii::t('front', 'Мы используем файлы cookie') ?></strong>, <span class="text-lowercase"><?= Yii::t('front', 'Чтобы улучшить работу сайта и предоставить вам лучший сервис.' ) ?></span><br>
                            <?= Yii::t('front', 'Продолжая использовать сайт, вы соглашаетесь с {0} файлов cookie.', Html::a(Yii::t('front', 'условиями использования'), [
                                '/cookie-policy'
                            ], [
                                'target' => '_blank',
                                'class' => 'text-primary',
                                'style' => 'text-decoration: underline',
                            ])) ?>
                        </div>
                        <div class="col-auto">
                            <?= Html::button(Yii::t('front', 'OK'), [
                                    'id' => 'cookiesNotificationShown',
                                    'type' => 'button',
                                    'class' => 'btn btn-outline-primary rounded-0 ajax',
                                    'data-remote' => Url::to(['/cookies-notification-shown']),
                                    'onclick' => '$("#cookiesNotification").remove();',
                                ])
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
-->
    <div id="cookiesNotification" class="fixed-bottom w-100 fade" data-type="2">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-end">
                <div class="col-12 py-1 px-1 bg-primary text-secondary text-white font-weight-light">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <?= Yii::t('front', 'Мы используем файлы cookie') ?>, <span class="text-lowercase"><?= Yii::t('front', 'Чтобы улучшить работу сайта и предоставить вам лучший сервис.' ) ?></span> <?= Yii::t('front', 'Продолжая использовать сайт, вы соглашаетесь с {0} файлов cookie.', Html::a(Yii::t('front', 'условиями использования'), [
                                '/cookie-policy'
                            ], [
                                'target' => '_blank',
                                'class' => 'text-white',
                                'style' => 'text-decoration: underline',
                            ])) ?>
                        </div>
                        <div class="col-auto">
                            <?= Html::button(Yii::t('front', 'OK'), [
                                    'id' => 'cookiesNotificationShown',
                                    'type' => 'button',
                                    'class' => 'btn btn-outline-secondary text-white border-white rounded-0 ajax',
                                    'data-remote' => Url::to(['/cookies-notification-shown']),
                                    'onclick' => '$("#cookiesNotification").remove();',
                                ])
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
<!--
    <div id="cookiesNotification" class="fixed-top w-100 fade" data-type="3">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-end">
                <div class="col-12 py-1 px-1 bg-primary text-secondary text-white font-weight-light">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <?= Yii::t('front', 'Мы используем файлы cookie') ?>, <span class="text-lowercase"><?= Yii::t('front', 'Чтобы улучшить работу сайта и предоставить вам лучший сервис.' ) ?></span> <?= Yii::t('front', 'Продолжая использовать сайт, вы соглашаетесь с {0} файлов cookie.', Html::a(Yii::t('front', 'условиями использования'), [
                                '/cookie-policy'
                            ], [
                                'target' => '_blank',
                                'class' => 'text-white',
                                'style' => 'text-decoration: underline',
                            ])) ?>
                        </div>
                        <div class="col-auto">
                            <?= Html::button(Yii::t('front', 'OK'), [
                                    'id' => 'cookiesNotificationShown',
                                    'type' => 'button',
                                    'class' => 'btn btn-outline-secondary text-white border-white rounded-0 ajax',
                                    'data-remote' => Url::to(['/cookies-notification-shown']),
                                    'onclick' => "
                                        $('#cookiesNotification').remove();
                                        $('#nav').removeAttr('style');
                                    ",
                                ])
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
-->