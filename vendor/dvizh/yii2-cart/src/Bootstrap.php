<?php
namespace dvizh\cart;

use yii\base\BootstrapInterface;
use yii;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Yii::$container->set('dvizh\cart\interfaces\Cart', 'dvizh\cart\models\Cart');
        Yii::$container->set('dvizh\cart\interfaces\Element', 'dvizh\cart\models\CartElement');
        Yii::$container->set('cartElement', 'dvizh\cart\models\CartElement');

        if (!isset($app->i18n->translations['cart']) && !isset($app->i18n->translations['cart*'])) {
            $app->i18n->translations['cart'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__.'/messages',
                'forceTranslation' => true
            ];
        }
    }
}