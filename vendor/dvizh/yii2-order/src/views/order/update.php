<?php

use yii\helpers\Html;

$this->title = Yii::t('back', 'Update order');
$this->params['breadcrumbs'][] = ['label' => Yii::t('back', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('back', 'Update');
?>
<div class="order-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
