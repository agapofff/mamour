<?php
namespace dvizh\cart\widgets;

use yii\helpers\Html;
use yii\helpers\Url;
use yii;

class CartInformer extends \yii\base\Widget
{

    public $text = NULL;
    public $offerUrl = NULL;
    public $cssClass = NULL;
    public $htmlTag = 'span';
    public $showOldPrice = true;
    public $currency = null;

    public function init()
    {
        parent::init();

        \dvizh\cart\assets\WidgetAsset::register($this->getView());

        if ($this->offerUrl == NULL) {
            $this->offerUrl = Url::to(['/cart/default/index']);
        }
        
        if ($this->text === NULL) {
            $this->text = '{c} '. Yii::t('front', 'шт.').' - {p}';
        }
        
        return true;
    }

    public function run()
    {
        $cart = Yii::$app->cart;

        if ($this->showOldPrice == false | $cart->cost == $cart->getCost(false)) {
            $this->text = str_replace(['{c}', '{p}'],
                ['<span class="dvizh-cart-count">'.$cart->getCount().'</span>', '<strong class="dvizh-cart-price">'.Yii::$app->formatter->asCurrency($cart->getCost(), $this->currency).'</strong>'],
                $this->text
            );
        } else {
            $this->text = str_replace(['{c}', '{p}'],
                ['<span class="dvizh-cart-count">'.$cart->getCount().'</span>', '<strong class="dvizh-cart-price"><s>'.Yii::$app->formatter->asCurrency(round($cart->getCost(false)), $this->currency).'</s>'.$cart->getCostFormatted().'</strong>'],
                $this->text
            );
        }
        
        return Html::tag($this->htmlTag, $this->text, [
				'href' => $this->offerUrl,
				'class' => "dvizh-cart-informer {$this->cssClass}",
				'data-count' => $cart->getCount(),
				'data-cost' => $cart->getCost(),
			]);
    }
}
