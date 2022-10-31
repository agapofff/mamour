<?php
namespace dvizh\shop\widgets;

use yii\helpers\Html;
use yii\helpers\Url;
use yii;

class ShowPrice extends \yii\base\Widget
{
    public $model = NULL;
    public $htmlTag = 'span';
    public $cssClass = '';
    public $price = NULL;
    public $priceOld = NULL;

    public function init()
    {
        \dvizh\shop\assets\WidgetAsset::register($this->getView());

        return parent::init();
    }

    public function run()
    {
        $js = 'dvizh.modificationconstruct.dvizhShopUpdatePriceUrl = "' .Url::toRoute([
            '/shop/tools/get-modification-by-options/',
            'lang' => Yii::$app->language,
            'store_type' => Yii::$app->params['store_type'],
        ]). '";';
        
        // $js = 'dvizh.modificationconstruct.dvizhShopUpdatePriceUrl = "' .Url::to('shop/tools/get-modification-by-options', true). '";';

        $this->getView()->registerJs($js);
        
        $price = $this->price ? $this->price : $this->model->getPrice(1);
        $priceOld = $this->priceOld && $this->priceOld > 0 ? $this->priceOld : $this->model->getOldprice(1);

        return Html::tag('div',
            Html::tag('del',
                Yii::$app->formatter->asCurrency($priceOld, Yii::$app->params['currency']),
                [
                    'class' => 'dvizh-shop-price-old dvizh-shop-price-old-{$this->model->id} {$this->cssClass} ttfirsneue mr-2 ' . ($priceOld > 0 ? 'd-inline': 'd-none'),
                ]
            )
            .
            Html::tag($this->htmlTag,
                $price ? Yii::$app->formatter->asCurrency($price, Yii::$app->params['currency']) : '',
                [
                    'class' => 'dvizh-shop-price dvizh-shop-price-' . $this->model->id . ' ' . $this->cssClass . ' ' . ($price > 0 ? 'd-inline' : 'd-none'),
                ]
            )
        );
    }
}
