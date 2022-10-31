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

<div class="container-xxl mt-3">    
    <div class="row justify-content-center">
        <div class="col-sm-11 col-md-10 col-lg-9 col-xl-6 col-xxl-5">
            <h1 class="gotham font-weight-bold text-uppercase headline mb-5">
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

            <?php
                ActiveForm::end();
            ?>
        
        </div>
    </div>
</div>