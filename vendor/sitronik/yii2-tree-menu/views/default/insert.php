<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Advert */

if(is_numeric($model->id)) {
    $this->title = 'Изменить пункт меню: ' . $model->name;
} else {
    $this->title = 'Добавить пункт меню: ' . $model->name;
}

$this->params['breadcrumbs'][] = ['label' => 'Меню', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Создать';
?>
<div class="row">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div class="advert-update">

            <h1><?= Html::encode($this->title) ?></h1>

            <?= $this->render('_form', [
                    'model' => $model,
                    'languages' => $languages,
                    'categories' => $categories,
                    'products' => $products,
                    'filters' => $filters,
                ]) 
            ?>

        </div>
    </div>
</div>

