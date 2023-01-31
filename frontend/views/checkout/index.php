<?php
use dvizh\cart\widgets\CartInformer;
use dvizh\cart\widgets\ElementsList;
use dvizh\order\widgets\OrderForm;

$this->title = Yii::t('front', 'Оформить заказ');
?>

<div class="container-xl">
    <div class="row">
        <div class="col-12">
            <?= ElementsList::widget([
                    'type' => 'checkout',
                    'currency' => $currency,
                    'lang' => Yii::$app->language,                
                ]);
            ?>
        </div>
        <div class="col-12">
            <?= CartInformer::widget([
                    'currency' => $currency
                ]);
            ?>
        </div>
        <div class="col-12">
            <?= OrderForm::widget() ?>
        </div>
    </div>
</div>
