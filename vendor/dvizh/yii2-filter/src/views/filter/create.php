<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Создание фильтра';
$this->params['breadcrumbs'][] = ['label' => 'Фильтры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="filter-create">
    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            <?= $this->render('_form', [
                    'model' => $model,
                ]) 
            ?>
        </div>
    </div>
</div>
