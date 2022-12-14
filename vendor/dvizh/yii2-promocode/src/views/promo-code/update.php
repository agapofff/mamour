<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PromoCodes */

$this->title = 'Изменить';
$this->params['breadcrumbs'][] = ['label' => 'Промокоды', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменить промокод '.$model->code;
?>
<div class="row">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div class="promo-codes-update">

            <?= $this->render('_form', [
                    'model' => $model,
                    'targetModelList' => $targetModelList,
                    'items' => $items,
                    'conditions' => $conditions,
                    'clientsModelMap' => $clientsModelMap,
                ]) 
            ?>

        </div>
    </div>
</div>
