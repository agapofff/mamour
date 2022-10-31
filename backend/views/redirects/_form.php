<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\switchinput\SwitchInput;
use kartik\alert\AlertBlock;

/* @var $this yii\web\View */
/* @var $model backend\models\Redirects */
/* @var $form yii\widgets\ActiveForm */
?>

<?php Pjax::begin(); ?>

    <div class="redirects-form">

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
                    ->radioList(
                        [
                            0 => 'Обычный редирект',
                            1 => 'Короткая ссылка',
                        ],
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
                    ->label(Yii::t('back', 'Тип ссылки'), [
                        'style' => 'display: block'
                    ])
            ?>

            <?= $form
                    ->field($model, 'link_from')
                    ->textInput(['maxlength' => true])
            ?>

            <?= $form
                    ->field($model, 'link_to')
                    ->textInput(['maxlength' => true])
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


<?php
    $this->registerJs("
        var shortLink = '". str_replace(Yii::$app->request->baseUrl, '', Url::home(true)) . hash('crc32', date('Y-m-d H:i:s')) . "';
        $('.btn-switch').change(function () {
            $('#redirects-link_from').val(this.value === '1' ? shortLink : '');
        });
        ",
        yii\web\View::POS_READY,
        'js-shortlink'
    )
?>