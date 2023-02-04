<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\web\View;

    /**
     * @var yii\web\View $this
     * @var dektrium\user\models\ResendForm $model
     */

    $this->title = Yii::t('front', 'Подтверждение учётной записи');
    $this->params['breadcrumbs'][] = $this->title;
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
                    'id' => 'resend-form',
                    // 'action' => '/resend',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                ]);
            ?>

                    <?= $form
                            ->field($model, 'email', [
                                'inputOptions' => [
                                    'autofocus' => 'autofocus',
                                    'class' => 'form-control py-1 px-0 bg-transparent border-top-0 border-left-0 border-right-0 border-dark outline-0 shadow-none montserrat font-weight-bold',
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
                            ->input('email')
                    ?>
                    
                    <?= Html::hiddenInput('lang', Yii::$app->language) ?>

                    <div class="row mt-2 mb-2">
                        <div class="col-sm-9 offset-sm-3">
                            <div class="row">
                                <div class="col-sm-6 mb-1">
                                    <?= Html::submitButton(Yii::t('front', 'Продолжить'), [
                                            'class' => 'btn btn-primary btn-block gotham px-2 py-1',
                                            'tabindex' => '4',
                                            'title' => Yii::t('front', 'Продолжить')
                                        ]) 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

            <?php
                ActiveForm::end();
            ?>
        </div>
    </div>
</div>