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
use kartik\select2\Select2;
use backend\widgets\MultilangField;

?>

<?php Pjax::begin(); ?>

    <div class="news-form">
    
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
                    ->field($model, 'date_published')
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

            <?= $form
                    ->field($model, 'category')
                    ->widget(Select2::classname(), [ 
                        'data' => $categories,
                        'options' => [
                            'placeholder' => 'Выберите или введите категорию'
                        ],
                        'pluginOptions' => [
                            'tags' => true,
                            'tokenSeparators' => [','],
                            'maximumInputLength' => 255
                        ],
                    ]);
            ?>
            
            <div class="form-group">
                <?= Gallery::widget([
                        'model' => $model,
                        'label' => Yii::t('back', 'Главное изображение'),
                        'previewSize' => '200x200',
                        'fileInputPluginOptions' => [
                            'showPreview' => false,
                        ],
                        'containerClass' => 'row',
                        'elementClass' => 'col-xs-12 col-sm-6 col-md-4',
                        // 'deleteButtonClass' => 'btn btn-sm btn-danger position-absolute top-0 right-0',
                        'deleteButtonText' => Html::tag('i', '', ['class' => 'fa fa-trash']),
                        // 'editButtonClass' => 'btn btn-sm btn-info position-absolute bottom-0 right-0',
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
    
            <?= $form
                    ->field($model, 'publisher')
                    ->hiddenInput()
                    ->label(false)
            ?>
            
            <?= $form
                    ->field($model, 'sort')
                    ->hiddenInput()
                    ->label(false)
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

<?php Pjax::end() ?>
