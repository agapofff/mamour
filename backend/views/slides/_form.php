<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\alert\AlertBlock;
use kartik\switchinput\SwitchInput;
use agapofff\gallery\widgets\Gallery;
use kartik\select2\Select2;
use backend\widgets\MultilangField;

/* @var $this yii\web\View */
/* @var $model backend\models\Banners */
/* @var $form yii\widgets\ActiveForm */
?>

<?php Pjax::begin(); ?>

    <div class="banners-form">
    
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
            
            <?= Gallery::widget([
                    'model' => $model,
                    'label' => Yii::t('back', 'Изображение'),
                    'previewSize' => '500x',
                    'fileInputPluginOptions' => [
                        'showPreview' => false,
                    ],
                    'containerClass' => 'row',
                    'elementClass' => 'col-xs-12',
                    'deleteButtonText' => Html::tag('i', '', ['class' => 'fa fa-trash']),
                    'editButtonText' => Html::tag('i', '', ['class' => 'fa fa-edit']),
                ]);
            ?>
            <br>

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
            
            <?= $form
                    ->field($model, 'link')
                    ->textInput([
                        'maxlength' => true
                    ])
                    ->hint('Относительная ссылка, после языковой метки, начинается со слеша')
            ?>

        <div class="hidden">
            <?= MultilangField::widget([
                    'model' => $model,
                    'form' => $form,
                    'field' => 'text',
                    'type' => 'html',
                    'languages' => $languages,
                ]);
            ?>
        </div>

            <?= $form
                    ->field($model, 'show_button')
                    ->widget(SwitchInput::classname(), [
                        'pluginOptions' => [
                            'onText' => Yii::t('back', 'Да'),
                            'offText' => Yii::t('back', 'Нет'),
                            'onColor' => 'success',
                            'offColor' => 'danger',
                        ],
                    ]);
            ?>

            <?= MultilangField::widget([
                    'model' => $model,
                    'form' => $form,
                    'field' => 'button_text',
                    'languages' => $languages,
                ]);
            ?>

        <div class="hidden">
            <?= $form
                    ->field($model, 'content_align')
                    ->radioList([
                        'Слева',
                        'По центру',
                        'Справа',
                    ], [
                        'class' => 'btn-group',
                        'data-toggle' => 'buttons',
                        'unselect' => null,
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return '<label class="btn btn-primary text-white '. ($checked ? ' active' : '') . '">' .
                                        Html::radio($name, $checked, [
                                            'value' => $value,
                                            'class' => 'btn-switch'
                                        ]) . $label . 
                                    '</label>';
                        },
                    ])
                    ->label(Yii::t('back', 'Расположение контента'), [
                        'style' => 'display: block'
                    ])
            ?>
        </div>
            
            <?= $form
                    ->field($model, 'color')
                    ->radioList([
                        'Темная',
                        'Светлая',
                    ], [
                        'class' => 'btn-group',
                        'data-toggle' => 'buttons',
                        'unselect' => null,
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return '<label class="btn btn-primary text-white '. ($checked ? ' active' : '') . '">' .
                                        Html::radio($name, $checked, [
                                            'value' => $value,
                                            'class' => 'btn-switch'
                                        ]) . $label . 
                                    '</label>';
                        },
                    ])
                    ->label(Yii::t('back', 'Цветовая схема'), [
                        'style' => 'display: block'
                    ])
            ?>
            
            <?= $form
                    ->field($model, 'sort')
                    ->hiddenInput()
                    ->label(false)
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