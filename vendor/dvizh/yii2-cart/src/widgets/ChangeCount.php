<?php
namespace dvizh\cart\widgets; 
use yii;
use yii\helpers\Url;
use yii\helpers\Html;

class ChangeCount extends \yii\base\Widget
{
    public $model = NULL;
    public $lineSelector = 'li'; //Селектор материнского элемента, где выводится элемент
    public $downArr = '<svg width="17" height="11" viewBox="0 0 17 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.00014 2L8.86313 9.57783L15.7197 2" stroke="#B8994F" stroke-width="1.42" stroke-miterlimit="10" stroke-linecap="square"/></svg>';
    public $upArr = '<svg width="17" height="11" viewBox="0 0 17 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.7196 9.57788L8.85659 2.00005L2 9.57788" stroke="#B8994F" stroke-width="1.42" stroke-miterlimit="10" stroke-linecap="square"/></svg>';
    public $cssClass = 'dvizh-change-count';
    public $defaultValue = 1;
    public $showArrows = true;
    public $actionUpdateUrl = null;
    public $customView = false; // for example '@frontend/views/custom/changeCountLayout'
	
	// public $name = null;
	// public $currency = null;

    public function init()
    {
        parent::init();

        \dvizh\cart\assets\WidgetAsset::register($this->getView());
        
        return true;
    }

    public function run()
    {
        if ($this->showArrows) {
            $downArr = Html::tag('div', Html::button($this->downArr, [
                'class' => 'btn btn-link p-0 pb-0_5 cart-change-count minus',
                'style' => 'line-height:1; pointer-events: ' . ($this->model->count == 1 ? 'none' : 'normal'),
                'data-id' => $this->model->getId(),
                'disabled' => ($this->model->count == 1 ? true : false),
            ]), [
                'class' => 'col-12 p-0 dvizh-arr dvizh-downArr text-center'
            ]);
            $upArr = Html::tag('div', Html::button($this->upArr, [
                'class' => 'btn btn-link p-0 pt-0_5 cart-change-count plus',
                'data-id' => $this->model->getId(),
                'style' => 'line-height:1',
            ]), [
                'class' => 'col-12 p-0 dvizh-arr dvizh-upArr text-center'
            ]);
        } else {
            $downArr = $upArr = '';
        }

        if (!$this->model instanceof \dvizh\cart\interfaces\CartElement) {
            $input = Html::activeTextInput($this->model, 'count', [
                'type' => ($this->showArrows ? 'text' : 'number'),
                'class' => 'dvizh-cart-element-count form-control text-center border-0 px-0 bg-transparent montserrat',
                'data-role' => 'cart-element-count',
                'data-line-selector' => $this->lineSelector,
                'data-id' => $this->model->getId(),
                'data-href' => $this->actionUpdateUrl,
                'min' => '1',
                'style' => 'width: 50px; font-size:2.25rem;' . ($this->showArrows ? 'pointer-events: none;' : ''),            ]);
        } else {
            $input = Html::input('number', 'count', $this->defaultValue, [
                'class' => 'dvizh-cart-element-before-count form-control',
                'data-line-selector' => $this->lineSelector,
                'data-id' => $this->model->getCartId(),
                'min' => '1',
            ]);
        }
        
        $count = Html::tag('div', $this->model->count, [
            // 'class' => 
        ]);
        
        if ($this->customView) {
            return $this->render($this->customView, [
                'model' => $this->model,
                'defaultValue' => $this->defaultValue,
            ]);
        } else {
            return Html::tag('div', Html::tag('div', Html::tag('div', $upArr . $downArr, [
                'class' => 'row align-items-center'
            ]), [
                'class' => 'col-auto p-0'
            ]) . Html::tag('div', $input, [
                'class' => 'col-auto p-0'
            ]), [
                'class' => $this->cssClass . ($this->showArrows ? ' row align-items-center flex-nowrap' : ''),
                // 'style' => 'width: 120px;',
            ]);
        }
    }
}
