<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\alert\AlertBlock;

/* @var $this yii\web\View */
/* @var $model common\models\Languages */
/* @var $form yii\widgets\ActiveForm */
?>

<?php Pjax::begin(); ?>

    <div class="langs-form">

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
                    ->field($model, 'name')
                    ->textInput([
                        'maxlength' => true
                    ])
            ?>

            <?= $form
                    ->field($model, 'code')
                    ->textInput([
                        'maxlength' => true
                    ])
            ?>
            
            <?= $form
                    ->field($model, 'currency')
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