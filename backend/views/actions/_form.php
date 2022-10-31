<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\alert\AlertBlock;
use kartik\switchinput\SwitchInput;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;
use agapofff\gallery\widgets\Gallery;
use dvizh\seo\widgets\SeoForm;
use backend\widgets\MultilangField;

/* @var $this yii\web\View */
/* @var $model backend\models\Actions */
/* @var $form yii\widgets\ActiveForm */
?>

<?php Pjax::begin(); ?>

    <div class="actions-form">
    
        <?= AlertBlock::widget([
                'type' => 'growl',
                'useSessionFlash' => true,
                'delay' => 1,
            ]);
        ?>

        <?php 
            $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data',
                ]
            ]); 
        ?>

            <?= $form
                ->field($model, 'active')
                ->widget(SwitchInput::classname(), [
                    'pluginOptions' => [
                        'onText' => Yii::t('back', 'Да'),
                        'offText' => Yii::t('back', 'Нет'),
                        'onColor' => 'success',
                        'offColor' => 'danger',
                    ],
                ]);
            ?>

            <?= $form
                    ->field($model, 'published')
                    ->widget(DateControl::classname(), [
                        'type' => 'date',
                        'displayFormat' => 'php:d F Y',
                        'saveFormat' => 'php:Y-m-d',
                        'saveTimezone' => 'Europe/Moscow',
                        'displayTimezone' => 'Europe/Moscow',
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'php:d F Y',
                            ],
                            'layout' => '{picker}{input}{remove}',
                            'options' => [
                                'placeholder' => Yii::t('back', 'Выберите дату')
                            ],
                        ],
                        'language' => 'ru',
                    ]);
            ?>

            <?= $form
                    ->field($model, 'type')
                    ->dropDownList(Yii::$app->params['actionsTypes'])
            ?>

            <?= MultilangField::widget([
                    'model' => $model,
                    'form' => $form,
                    'field' => 'name',
                    'languages' => $languages,
                ]);
            ?>
     
            <?= $form
                    ->field($model, 'slug')
                    ->textInput([
                        'maxlength' => true
                    ])
            ?>
            
            <div class="form-group">
                <?= Gallery::widget([
                        'model' => $model,
                        'label' => Yii::t('back', 'Изображение'),
                        'previewSize' => '200x200',
                        'fileInputPluginOptions' => [
                            'showPreview' => false,
                        ],
                        'containerClass' => 'row',
                        'elementClass' => 'col-xs-6',
                        'deleteButtonText' => Html::tag('i', '', ['class' => 'fa fa-trash']),
                        'editButtonText' => Html::tag('i', '', ['class' => 'fa fa-edit']),
                    ]);
                ?>
            </div>  

            <?= MultilangField::widget([
                    'model' => $model,
                    'form' => $form,
                    'field' => 'description',
                    'languages' => $languages,
                ]);
            ?>

            <?= MultilangField::widget([
                    'model' => $model,
                    'form' => $form,
                    'field' => 'text',
                    'type' => 'html',
                    'languages' => $languages,
                ]);
            ?>
            
            <br>
            <?= SeoForm::widget([
                    'model' => $model, 
                    'form' => $form,
                    'languages' => $languages,
                ]); 
            ?>

            <?= Html::input('hidden', 'saveAndExit', 0) ?>

            <br>
            <div class="form-group text-center">
                <?= Html::submitButton(Html::tag('span', '', [
                        'class' => 'glyphicon glyphicon-floppy-saved'
                    ]) . '&nbsp;' . Yii::t('back', 'Сохранить'), [
                        'class' => 'btn btn-success btn-lg'
                    ]) 
                ?>
                
                <?= Html::submitButton(Html::tag('span', '', [
                        'class' => 'glyphicon glyphicon-floppy-remove'
                    ]) . '&nbsp;' . Yii::t('back', 'Сохранить и закрыть'), [
                        'class' => 'btn btn-default btn-lg saveAndExit' . ($model->id ? '' : ' hidden')
                    ])
                ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php Pjax::end(); ?>
