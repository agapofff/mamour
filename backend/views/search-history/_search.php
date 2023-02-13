<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SearchHistorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="search-history-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user') ?>

    <?= $form->field($model, 'dateandtime') ?>

    <?= $form->field($model, 'request') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('back', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('back', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
