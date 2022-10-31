<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Stores */

$this->title = Yii::t('back', 'Создать');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('back', 'Склады'), 
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;

$model->active = 1;
?>

<div class="row">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div class="stores-create">
            <?= $this->render('_form', [
                    'model' => $model,
                    'languages' => $languages,
                    'countries' => $countries,
                    'postcodes' => $postcodes,
                ])
            ?>
        </div>
    </div>
</div>
