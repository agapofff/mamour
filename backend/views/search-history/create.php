<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SearchHistory */

$this->title = Yii::t('back', 'Create Search History');
$this->params['breadcrumbs'][] = ['label' => Yii::t('back', 'Search Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="search-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
