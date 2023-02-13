<?php
use yii;
use yii\leheprs\Url;

$this->registerJs("
const payment = new PaymentPageSdk('" . Yii::$app->params['RaiffeisenID'] . "');

payment.openPopup({
    amount: " . $order->cost . ",
    comment: '" . Yii::t('front', 'Оплата заказа на сайте') . " " . Yii::$app->id . "',
    locale: '" . Yii::$app->language . "',
    style: {
        header: {
            logo: 'https://mamour-enfants.com/images/logo_nav_dark_small.svg',
        },
        button: {
            backgroundColor: '#1E1E1E',
            textColor: '#ffffff',
            hoverTextColor: '#ffffff',
            hoverBackgroundColor: '#1E1E1E',
            borderRadius: '0px',
        },
    }
    orderId: '" . $order->id . "',
    successUrl: '" . Url::to(['/checkout/success']) . "',
    failUrl: '" . Url::to(['/checkout/error']) . "',
    successSbpUrl: '" . Url::to(['/checkout/success']) . "',
});
");