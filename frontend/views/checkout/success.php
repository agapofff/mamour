<?php
    use yii\web\View;
    use yii\helpers\Url;
    use yii\helpers\Html;
    
    $this->title = Yii::t('front', 'Ваш заказ успешно оформлен');
?>


<div class="container-fluid mb-5 mt-1_5 px-lg-2 px-xl-3 px-xxl-5">
    <div class="row">
        <div class="col-12">
            <h1 class="ttfirsneue text-uppercase display-2 position-relative d-inline-block mb-0 red_dot">
                <?= Yii::t('front', 'Спасибо') ?>
            </h1>
        </div>
    </div>
</div>

<div class="container-fluid mb-15 mt-5 px-lg-2 px-xl-3 px-xxl-5">    
    <div class="row mb-6">
        <div class="col-12 col-xl-10">
            <h2 class="h1 mb-0 font-weight-light">
                <?= Yii::t('front', 'Мы отправим письмо с подтверждением заказа на Ваш e-mail') ?>
            </h2>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-12 col-sm-6 col-md-4 col-xl-3 col-xxl-2">
            <?= Html::a(Yii::t('front', 'Назад к покупкам'), Url::to(['/catalog']), [
                    'class' => 'btn btn-primary d-block ttfirsneue text-uppercase py-1',
                ])
            ?>
        </div>
    </div>
</div>

<?php
    $this->registerJs("
        ymPurchase('" . date('YmdHis') . "', '" . json_encode($products) . "', '" . Yii::$app->params['currency'] . "');
        fbqPurchase('" . json_encode($products) . "', '" . $sum . "', '" . Yii::$app->params['currency'] . "');
    ", View::POS_READY);
?>