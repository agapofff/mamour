<?php
use yii\helpers\Html;

$this->title = Yii::t('back', 'Создать');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('back', 'Товары'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;
\dvizh\shop\assets\BackendAsset::register($this);
?>
<div class="row">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div class="product-create">
            <?= $this->render('_form', [
                    'model' => $model,
                    'priceTypes' => $priceTypes,
                    'priceModel' => $priceModel,
                    'languages' => $languages,
                ])
            ?>
        </div>
    </div>
</div>