<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\alert\AlertBlock;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Params */
/* @var $form yii\widgets\ActiveForm */
?>

<?php Pjax::begin(); ?>

    <div class="params-form">
    
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
                ->field($model, 'category')
                ->widget(Select2::classname(), [
                    'data' => $categories,
                    'options' => [
                        'placeholder' => 'Выберите или введите категорию', 
                    ],
                    'pluginOptions' => [
                        'tags' => true,
                        'tokenSeparators' => [',', ''],
                        'maximumInputLength' => 50,
                        'allowClear' => true,
                    ],
                ])
                ->hint('На английском языке')
            ?>

            <?= $form
                    ->field($model, 'name')
                    ->textInput([
                        'maxlength' => true
                    ]) 
            ?>

            <?= $form
                    ->field($model, 'value')
                    ->textArea([
                        'maxlength' => true
                    ]) 
            ?>
            
            <?= $form
                    ->field($model, 'type')->dropdownList(Yii::$app->params['settingsTypes'], [
                        'prompt' => 'Выберите тип'
                    ])
            ?>
            
            <?= $form
                    ->field($model, 'description')
                    ->textArea([
                        'maxlength' => true
                    ]) 
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