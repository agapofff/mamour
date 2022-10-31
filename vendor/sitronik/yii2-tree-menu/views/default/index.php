<?php
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = Yii::t('back', 'Меню');
$this->params['breadcrumbs'][] = Yii::t('back', 'Меню');
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="row">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <?php Pjax::begin(); ?>
            <?= sitronik\treemenu\Tree::widget(['isAdmin' => true]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
