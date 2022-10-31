<?php
namespace dvizh\cart\widgets;
use yii;
use yii\helpers\Html;

class ElementPrice extends \yii\base\Widget
{
    public $model = NULL;
    public $currency = NULL;
    public $cssClass = NULL;
    public $htmlTag = 'div';
    
    public function init()
    {
        parent::init();
        return true;
    }
    
    public function run()
    {
        return Html::tag($this->htmlTag, Yii::$app->formatter->asCurrency((float)$this->model->price, $this->currency ? $this->currency : Yii::$app->params['currency']), [
            'class' => "dvizh-cart-element-price{$this->model->getId()} {$this->cssClass} text-center",
        ]);
    }
}