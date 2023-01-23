<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\switchinput\SwitchInput;
use kartik\alert\AlertBlock;
use backend\widgets\MultilangField;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Stores */
/* @var $form yii\widgets\ActiveForm */
?>

<?php Pjax::begin(); ?>

    <div class="stores-form">

        <?= AlertBlock::widget([
                'type' => 'growl',
                'useSessionFlash' => true,
                'delay' => 1,
            ]);
        ?>

        <?php $form = ActiveForm::begin(); ?>

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
                    ->field($model, 'type')
                    ->radioList(Yii::$app->config->get('storeTypes'),
                        [
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
                        ]
                    )
                    ->label(Yii::t('back', 'Тип магазина'), [
                        'style' => 'display: block'
                    ])
            ?>

            <?= $form
                    ->field($model, 'store_id')
                    ->textInput([
                        'maxlength' => true
                    ])
            ?>
            
            <?= $form
                    ->field($model, 'name')
                    ->textInput([
                        'maxlength' => true
                    ])
            ?>
        
            <?= $form
                    ->field($model, 'country_id')
                    ->widget(Select2::classname(), [ 
                        'data' => $countries,
                    ])
            ?>
            
            <?= $form
                    ->field($model, 'currency')
                    ->textInput([
                        'maxlength' => true
                    ])
            ?>
            
            <?= $form
                    ->field($model, 'postcodes')
                    ->widget(Select2::classname(), [ 
                        'data' => $postcodes,
                        'options' => [
                            'multiple' => true,
                            'placeholder' => 'Выберите или введите'
                        ],
                        'pluginOptions' => [
                            'tags' => true,
                            'tokenSeparators' => [',', ' '],
                        ],
                    ])
                    ->hint(Html::tag('small', Yii::t('back', 'Индексы (ZIP-codes), в пределах которых работает склад.<br>Если нужно ввести диапазон идентификаторов, введите первый и последний через дефис, без пробелов.<br>Если хотите исключить какой-то регион, поставьте "!" перед его номером.<br>Оставьте поле пустым, если этот склад работает по всей стране.')))
            ?>   
            
            <?= $form
                    ->field($model, 'description')
                    ->textInput([
                        'maxlength' => true
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