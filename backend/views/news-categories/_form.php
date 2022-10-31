<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\alert\AlertBlock;
use kartik\switchinput\SwitchInput;
use dvizh\seo\widgets\SeoForm;
use backend\widgets\MultilangField;

?>

<?php Pjax::begin(); ?>

    <div class="news-categories-form">
    
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
                    ->field($model, 'slug')
                    ->textInput([
                        'maxlength' => true
                    ])
            ?>
            
            <br>
            <?= SeoForm::widget([
                    'model' => $model, 
                    'form' => $form, 
                    'languages' => $languages,
                ]); 
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
