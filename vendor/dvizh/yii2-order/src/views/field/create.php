<?php

use yii\helpers\Html;

$this->title = Yii::t('back', 'Создать поле');
$this->params['breadcrumbs'][] = ['label' => Yii::t('back', 'Заказы'), 'url' => ['/order/order']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('back', 'Доп.поля заказа'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div class="field-create">

            <?= $this->render('_form', [
                    'model' => $model,
                    'fieldTypes' => $fieldTypes,
                    'languages' => $languages,
                ]) 
            ?>

        </div>
    </div>
</div>
