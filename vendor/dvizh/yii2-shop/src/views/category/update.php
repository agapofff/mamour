<?php
use yii\helpers\Html;

$this->title = Yii::t('back', 'Изменить');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('back', 'Категории'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = Yii::t('back', 'Обновить');
\dvizh\shop\assets\BackendAsset::register($this);
?>
<div class="row">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div class="category-update">

            <?= $this->render('_form', [
                'model' => $model,
                'languages' => $languages,
            ]) ?>

            <?php if ($fieldPanel = \dvizh\field\widgets\Choice::widget(['model' => $model])) { ?>
                <div class="block">
                    <h2><?= Yii::t('back', 'Прочее') ?></h2>
                    <?=$fieldPanel;?>
                </div>
            <?php } ?>
            
        </div>
    </div>
</div>