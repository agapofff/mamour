<?php
use dvizh\cart\widgets\CartInformer;
use dvizh\cart\widgets\ElementsList;
use dvizh\order\widgets\OrderForm;

$this->title = Yii::t('front', 'Оформить заказ');

// echo \yii\helpers\VarDumper::dump(Yii::$app->cart, 99, true);
?>

<div class="container-xl mt-3 mt-sm-4 mt-md-5 mt-lg-6 mt-xl-7">    
    <h1 class="montserrat font-weight-bold text-uppercase headline mb-5">
        <?= $this->title ?>
    </h1>
    <div class="row">
        <div class="col-12">
            <?= ElementsList::widget([
                    'type' => 'checkout',
                    'currency' => $currency,
                    'lang' => Yii::$app->language,
                ]);
            ?>
        </div>
    </div>
    <div class="row align-items-center">
        <div class="col-sm-6">
            <?=\dvizh\promocode\widgets\Enter::widget();?>
        </div>        
        <div class="col-sm-6 text-right">
            <?= CartInformer::widget([
                    'currency' => $currency,
                    'showOldPrice' => false,
                ]);
            ?>
        </div>
    </div>
    <div class="row">
        <?= OrderForm::widget() ?>
    </div>
</div>
