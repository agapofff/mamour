<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SearchHistory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="search-history-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dateandtime')->textInput() ?>

    <?= $form->field($model, 'request')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('back', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
