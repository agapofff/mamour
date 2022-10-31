<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\alert\AlertBlock;

/* @var $this yii\web\View */
/* @var $model backend\models\Message */
/* @var $form yii\widgets\ActiveForm */

$sourceMessageArray = ArrayHelper::toArray($sourceMessage);
ArrayHelper::multisort($sourceMessageArray, 'id', SORT_DESC);
    

?>

<?php Pjax::begin(); ?>

    <div class="message-form">

        <?= AlertBlock::widget([
                'type' => 'growl',
                'useSessionFlash' => true,
                'delay' => 1,
            ]);
        ?>

        <?php $form = ActiveForm::begin(); ?>

            <?= $form
                    ->field($model, 'id')
                    ->dropDownList(ArrayHelper::map($sourceMessageArray, 'id', 'message'))
                    ->label('Константа');
            ?>

            <?= $form
                    ->field($model, 'language')
                    ->radioList(
                        ArrayHelper::map($languages, 'code', 'code'), 
                        [
                            'class' => 'btn-group',
                            'data-toggle' => 'buttons',
                            'unselect' => null,
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return '<label class="btn btn-primary text-white '. ($checked ? ' active' : '') . '">' .
                                            Html::radio($name, $checked, [
                                                'value' => $value,
                                                'class' => 'btn-switch'
                                            ]) . strtoupper($label) . 
                                        '</label>';
                            },
                        ]
                    )
                    ->label(Yii::t('back', 'Язык'), [
                        'style' => 'display: block'
                    ])
            ?>

            <?= $form
                    ->field($model, 'translation')
                    ->textArea([
                        // 'maxlength' => true,
                        'rows' => 5,
                    ])
            ?>
            
            <div class="form-group hidden">
                <?= Html::button(Yii::t('back', 'Автоперевод'), [
                    'class' => 'btn btn-info',
                    'onclick' => "
                        var lang = $('#message-language input:checked').val(),
                            text = $('#message-id option:selected').text();
                            
                        if (lang === 'kz') {
                            lang = 'kk';
                        } else if (lang === 'ua') {
                            lang = 'uk';
                        }
                        
                        $.ajax({
                            url: '" . Url::to(['/site/curl'], true) . "',
                            type: 'get',
                            data: {
                                url: 'https://fasttranslator.herokuapp.com/api/v1/text/to/text',
                                post: true,
                                params: JSON.stringify({
                                    source: text,
                                    lang: lang
                                })
                            },
                            success: function (data) {
                                var response = JSON.parse(data);
                                if (response.status === 200 && response.message === 'OK') {
                                    $('#message-translation').val(response.data);
                                } else {
                                    alert(response.message);
                                    console.log(data);
                                }
                            },
                            error: function (data) {
                                var response = JSON.parse(data);
                                alert(response.message);
                                console.log(data);
                            }
                        });
                        
                    ",
                ])
                ?>
            </div>

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