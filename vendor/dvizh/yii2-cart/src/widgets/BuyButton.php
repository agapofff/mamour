<?php
namespace dvizh\cart\widgets;

use yii\helpers\Html;
use yii\helpers\Url;
use yii;

class BuyButton extends \yii\base\Widget
{
    public $text = NULL;
    public $model = NULL;
    public $count = 1;
    public $price = false;
    public $description = '';
    public $cssClass = NULL;
    public $htmlTag = 'a';
    public $options = null;
    public $addElementUrl = '/cart/element/create';

    public function init()
    {
        parent::init();

        \dvizh\cart\assets\WidgetAsset::register($this->getView());

        if ($this->options === NULL) {
            $this->options = (object)[];
        }
        
        if ($this->text === NULL) {
            $this->text = Yii::t('front', 'Добавить в корзину');
        }

        if ($this->cssClass === NULL) {
            $this->cssClass = 'btn btn-primary btn-lg rounded-pill px-4';
        }
        
        return true;
    }

    public function run()
    {
        if (!is_object($this->model) | !$this->model instanceof \dvizh\cart\interfaces\CartElement) {
            return false;
        }

        $model = $this->model;
        return Html::tag($this->htmlTag, Html::tag('span') . $this->text, [
            'href' => Url::to([$this->addElementUrl]),
            'class' => "dvizh-cart-buy-button dvizh-cart-buy-button{$this->model->getCartId()} {$this->cssClass}",
            'data-id' => $model->getCartId(),
            'data-url' => Url::to([$this->addElementUrl]),
            'data-role' => 'cart-buy-button',
            'data-count' => $this->count,
            'data-price' => (int)$this->price,
            'data-options' => json_encode($this->options),
            'data-description' => $this->description,
            'data-model' => $model::className(),
            'title' => $this->text,
            'style' => '',
			'disabled' => true,
			// 'type' => 'button',
        ]);
    }
}