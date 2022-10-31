<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\alert\AlertBlock;
use kartik\switchinput\SwitchInput;
use kartik\datecontrol\Module;

/* @var $this yii\web\View */
/* @var $model backend\models\Bonus */
/* @var $form yii\widgets\ActiveForm */
?>

<?php Pjax::begin(); ?>

    <div class="bonus-form">
    
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
                    ->field($model, 'user_id')
                    ->dropDownList(ArrayHelper::map($users, 'id', 'username'))
            ?>

            <?= $form
                    ->field($model, 'type')
                    ->dropDownList([
                        0 => 'Списание',
                        1 => 'Начисление'
                    ])
            ?>

            <?= $form
                    ->field($model, 'amount')
                    ->textInput([
                        'type' => 'number'
                    ]) 
            ?>

            <?= $form
                    ->field($model, 'reason')
                    ->dropDownList([
                        'Списание' => Yii::$app->params['bonus']['minus'],
                        'Начисление' => Yii::$app->params['bonus']['plus'],
                    ])
            ?>

            <?= $form
                    ->field($model, 'description')
                    ->textInput([
                        'maxlength' => true
                    ]) 
            ?>

            <?= $form
                    ->field($model, 'created_at')
                    ->hiddenInput()
                    ->label(false)
            ?>

            <?= $form
                    ->field($model, 'updated_at')
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
