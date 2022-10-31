<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\alert\AlertBlock;
use dvizh\order\models\OrderFieldType;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use kartik\switchinput\SwitchInput;
use backend\widgets\MultilangField;
use kartik\select2\Select2;

?>

<?php Pjax::begin(); ?>

    <div class="shippingtype-form">

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
        
            <?= MultilangField::widget([
                    'model' => $model,
                    'form' => $form,
                    'field' => 'name',
                    'languages' => $languages,
                ]);
            ?>
        
            <?= $form
                    ->field($model, 'sort')
                    ->hiddenInput()
                    ->label(false)
            ?>
            
            <?= $form
                    ->field($model, 'cost')
                    ->textInput([
                        'type' => 'number'
                    ])
            ?>
            
            <?= $form
                    ->field($model, 'free_cost_from')
                    ->textInput([
                        'type' => 'number'
                    ])
            ?>
            
            <?= $form
                    ->field($model, 'country_id')
                    ->widget(Select2::classname(), [ 
                        'data' => ArrayHelper::map($countries, 'id', function ($country) {
                            return json_decode($country->name)->{Yii::$app->language};
                        }),
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
                    ->hint(Html::tag('small', Yii::t('back', 'Индексы (ZIP-codes), в пределах которых действует способ доставки.<br>Если нужно ввести диапазон идентификаторов, введите первый и последний через дефис, без пробелов.<br>Если хотите исключить какой-то регион, поставьте "!" перед его номером (или диапазоном номеров).<br>Оставьте поле пустым, если доставка этим способом осуществляется по всей стране.')))
            ?>
            
            <?= $form
                    ->field($model, 'payment_types')
                    ->widget(Select2::classname(), [ 
                        'data' => ArrayHelper::map($paymentTypes, 'id', function ($paymentType) {
                            return json_decode($paymentType->name)->{Yii::$app->language};
                        }),
                        'options' => [
                            'multiple' => true,
                            'placeholder' => 'Выберите или введите'
                        ],
                    ])
            ?>
            
            <?= MultilangField::widget([
                    'model' => $model,
                    'form' => $form,
                    'field' => 'description',
                    'type' => 'html',
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