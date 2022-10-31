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
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\RecoveryForm $model
 */

$this->title = Yii::t('front', 'Новый пароль');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-xxl mt-3">    
    <div class="row justify-content-center">
        <div class="col-sm-11 col-md-9 col-lg-8 col-xl-6">
            <div class="row justify-content-center">
                <div class="col-xxl-10">
                    <h1 class="gotham font-weight-bold text-uppercase headline mb-5">
                        <?= $this->title ?>
                    </h1>
        
                    <?php 
                        $form = ActiveForm::begin([
                            'id' => 'password-recovery-form',
                            // 'action' => str_replace('/' . Yii::$app->language, '', Url::to()),
                            'enableAjaxValidation' => true,
                            'enableClientValidation' => false,
                        ]);
                    ?>

                        <?= $form
                                ->field($model, 'password', [
                                    'inputOptions' => [
                                        'class' => 'form-control py-1_75 px-1_5',
                                        'tabindex' => '2',
                                        'required' => true,
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
                                ->label(Yii::t('front', 'Новый пароль'));
                        ?>
                        
                        <?= Html::hiddenInput('lang', Yii::$app->language) ?>

                        <div class="row mt-2 mb-2">
                            <div class="col-sm-9 offset-sm-3">
                                <div class="row">
                                    <div class="col-sm-6 mb-1">
                                        <?= Html::submitButton(Yii::t('front', 'Сохранить'), [
                                                'class' => 'btn btn-primary btn-block gotham px-2 py-1',
                                                'tabindex' => '4',
                                                'title' => Yii::t('front', 'Сохранить')
                                            ]) 
                                        ?>
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
