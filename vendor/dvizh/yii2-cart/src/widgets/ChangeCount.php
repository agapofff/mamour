<?php
namespace dvizh\cart\widgets; 
use yii;
use yii\helpers\Url;
use yii\helpers\Html;

class ChangeCount extends \yii\base\Widget
{
    public $model = NULL;
    public $lineSelector = 'li'; //Селектор материнского элемента, где выводится элемент
    public $downArr = '<svg width="8" height="2" viewBox="0 0 8 2" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 1.192H0V0.488H8V1.192Z" fill="black"/></svg>';
    public $upArr = '<svg width="9" height="8" viewBox="0 0 9 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.8 0H4.76V3.52H8.28V4.48H4.76V8H3.8V4.48H0.28V3.52H3.8V0Z" fill="black"/></svg>';
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
        if($this->showArrows) {
            $downArr = Html::tag('div', Html::button($this->downArr, [
                'class' => 'btn btn-link p-0 rounded-pill d-flex align-items-center justify-content-center cart-change-count minus',
                'style' => 'pointer-events: ' . ($this->model->count == 1 ? 'none' : 'normal'),
                'disabled' => ($this->model->count == 1 ? true : false),
            ]), [
                'class' => 'input-group-prepend dvizh-arr dvizh-downArr',
                'style' => 'pointer-events: ' . ($this->model->count == 1 ? 'none' : 'normal')
            ]);
            $upArr = Html::tag('div', Html::button($this->upArr, [
                'class' => 'btn btn-link p-0 rounded-pill d-flex align-items-center justify-content-center cart-change-count plus',
            ]), [
                'class' => 'input-group-append dvizh-arr dvizh-upArr'
            ]);
        } else {
            $downArr = $upArr = '';
        }

        if(!$this->model instanceof \dvizh\cart\interfaces\CartElement) {
            $input = Html::activeTextInput($this->model, 'count', [
                'type' => ($this->showArrows ? 'text' : 'number'),
                'class' => 'dvizh-cart-element-count form-control text-center border-0 px-0 bg-transparent',
                'data-role' => 'cart-element-count',
                'data-line-selector' => $this->lineSelector,
                'data-id' => $this->model->getId(),
                'data-href' => $this->actionUpdateUrl,
                'min' => '1',
                'style' => ($this->showArrows ? 'pointer-events: none;' : ''),
            ]);
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
            return Html::tag('div', $downArr.$input.$upArr, [
                'class' => $this->cssClass . ($this->showArrows ? ' input-group justify-content-start align-items-center mr-auto' : ''),
                'style' => 'width: 120px;',
            ]);
        }
    }
}
