<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\alert\AlertBlock;
use yii\widgets\Pjax;
use dvizh\order\models\OrderFieldType;
use yii\helpers\ArrayHelper;
use kartik\switchinput\SwitchInput;
use backend\widgets\MultilangField;

?>

<?php Pjax::begin(); ?>

    <div class="paymenttype-form">

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
                
            <?= MultilangField::widget([
                    'model' => $model,
                    'form' => $form,
                    'field' => 'description',
                    'type' => 'html',
                    'languages' => $languages,
                ]);
            ?>
            
            <?= $form
                    ->field($model, 'widget')
                    ->textInput([
                        'placeholder' => 'dvizh\paymaster\widgets\PaymentForm'
                    ])
            ?>
        
            <p><?=Yii::t('back', 'Вызов виджета оплаты'); ?>:</p>
            
            <pre>widgetPath::widget([
    'autoSend' => true,
    'orderModel' => $model,
    'description' => 1,
])</pre>
            <p><?=Yii::t('back', 'Example')?>: <a href="https://github.com/dvizh/yii2-paymaster">dvizh/yii2-paymaster</a></p>
            
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