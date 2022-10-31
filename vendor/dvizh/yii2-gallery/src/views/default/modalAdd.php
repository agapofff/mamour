<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="text-center">
    <?= Html::img(Yii::$app->urlManager->hostInfo . '/images/store/' . $model->filePath, [
        'class' => 'img-thumbnail'
    ]) ?>
</div>
<br>

<?php $form = ActiveForm::begin([
        'action' => [
            '/gallery/default/write',
            'id' => $model->id
        ],
        'options' => [
            'id' => 'noctua-gallery-form'
        ]
    ]);
?>

    <?= $form
            ->field($model, 'title')
            ->textInput([
                'maxlength' => 255
            ])
    ?>

    <?= $form
            ->field($model, 'alt')
            ->textInput([
                'maxlength' => 255
            ])
    ?>

    <?= $form
            ->field($model, 'description')
            ->hiddenInput()
            ->label(false)
    ?>

    <?= $form
            ->field($model, 'sort')
            ->hiddenInput()
            ->label(false)
    ?>

    <?= Html::hiddenInput('model', $post['model']) ?>

    <?= Html::hiddenInput('id', $post['id']) ?>

    <?= Html::hiddenInput('image', $post['image']) ?>

    <br>
    <div class="buttonSet text-center button-container">
        <?= Html::submitButton(Html::tag('span', '', [
                'class' => 'glyphicon glyphicon-floppy-saved'
            ]) . '&nbsp;' . Yii::t('back', 'Сохранить'), [
                'class' => 'btn btn-success btn-lg'
            ])
        ?>
    </div>

<?php ActiveForm::end(); ?>