<?php
namespace dvizh\cart\widgets;

use yii\helpers\Url;
use yii\helpers\Html;
use yii;

class ChangeOptions extends \yii\base\Widget
{
    const TYPE_SELECT = 'select';
    const TYPE_RADIO = 'radio';

    public $model = NULL;
    public $type = NULL;
    public $cssClass = '';
    public $defaultValues = [];
    public $disabledItems = [];

    public function init()
    {
        if ($this->type == NULL) {
            $this->type = self::TYPE_SELECT;
        }

        parent::init();

        \dvizh\cart\assets\WidgetAsset::register($this->getView());

        return true;
    }

    public function run()
    {
        if ($this->model instanceof \dvizh\cart\interfaces\CartElement) {
            $optionsList = $this->model->getCartOptions();
            $changerCssClass = 'dvizh-option-values-before';
            $id = $this->model->getCartId();
        } else {
            $optionsList = $this->model->getModel()->getCartOptions();
            $this->defaultValues = $this->model->getOptions();
            $id = $this->model->getId();
            $changerCssClass = 'dvizh-option-values';
        }

        if (!empty($optionsList)) {
            $i = 1;
            foreach ($optionsList as $optionId => $optionData) {
                if (!is_array($optionData)) {
                    $optionData = [];
                }
                
                $cssClass = "{$changerCssClass} dvizh-cart-option{$id} cart-option";

                $optionsArray = [];
                if ($optionId == 1 && $this->type == 'select'){
					$optionsArray = [
						'' => Yii::t('front', $optionData['name']) 
					];
				}
                // $optionsArray = ['' => $optionData['name']];
                if (isset($optionData['variants'])) {
                    foreach ($optionData['variants'] as $variantId => $value) {
                        $optionsArray[$variantId] = json_decode($value)->{Yii::$app->language};
                    }
                }
				
				$optionsClass = [];
				if (!empty($optionsArray)){
					foreach ($optionsArray as $optionKey => $optionVal){
						$optionsClass[$optionKey] = [
							'class' => 'px-1 py-1 border-bottom'
						];
					}
				}
				

                if ($this->type == 'select') {

                    if ($optionId == 1){
                        $list = Html::dropDownList('cart_options' . $id . '-' . $i,
                            ($optionId == 1 ? 0 : array_key_first($optionsArray)),
                            $optionsArray,
                            [
                                'data-href' => Url::toRoute([
                                    'cart/element/update',
                                    'lang' => Yii::$app->language,
                                    'store_type' => Yii::$app->params['store_type'],
                                ]),
                                'data-filter-id' => $optionId,
                                'data-name' => Html::encode($optionData['name']),
                                'data-id' => $id,
                                'class' => 'd-none ' . $cssClass,
                                'id' => 'option' . $optionId,
								'options' => $optionsClass,
                            ]
                        );
						
						$list .= '<div class="dropdown">
									<button class="btn btn-outline-primary btn-lg btn-block ttfirsneue text-uppercase py-1 dropdown-toggle bg-white text-primary" type="button" id="sizeSelect" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="0,0" data-flip="false">' 
										. Yii::t('front', $optionsArray[array_key_first($optionsArray)]) . 
									'</button>
									<div class="dropdown-menu rounded-0 pt-0 px-0 pb-1 w-100 border-primary border-top-0 shadow-none" aria-labelledby="sizeSelect">';
						
						foreach ($optionsArray as $optionKey => $optionVal){
							$list .= '<button class="dropdown-item ttfirsneue text-uppercase text-center py-1 w-100 dropdown-change-select ' . ($optionKey ? '' : 'd-none') . '" data-id="' . $id . '" data-value="' . $optionKey . '">' . $optionVal . '</button>
								<hr class="my-0 mx-2 ' . ($optionKey ? '' : 'd-none') . '">';		
						}
									
						$list .= '</div>
								</div>';
								
                    } else {
                        $list = Html::input('hidden', 'cart_options' . $id . '-' . $i, array_key_first($optionsArray), [
                            'data-href' => Url::toRoute([
                                'cart/element/update',
                                'lang' => Yii::$app->language,
                                'store_type' => Yii::$app->params['store_type'],
                            ]),
                            'data-filter-id' => $optionId,
                            'data-name' => Html::encode($optionData['name']),
                            'data-id' => $id,
                            'class' => 'custom-select custom-select-lg py-1 h-auto ttfirsneue text-center text-uppercase cursor-pointer ' . $cssClass,
                            'id' => 'option' . $optionId,
                        ]);
                    }
                } else {
                    $optionName = $optionId == 1 ? Yii::t('app', 'Выберите размер') : $optionData['name'];
                    $optionLabel = Html::tag('div', $optionName, [
                        'class' => 'dvizh-option-heading d-none',
                    ]);

                    $disabled = [];
                    if ($this->disabledItems) {
                        foreach ($this->disabledItems as $disabledItem) {
                            foreach ($optionsArray as $optionKey => $optionVal) {
                                if ($optionVal == $disabledItem) {
                                    $disabled[] = $optionKey;
                                }
                            }
                        }
                    }
                    $list = Html::radioList('cart_options' . $id . '-' . $i,
                        0,
                        $optionsArray,
                        [
                            'item' => function ($index, $label, $name, $checked, $value) use ($optionId, $cssClass, $id, $optionData) {
                                return Html::radio($name, $checked, [
                                    'value' => $value,
                                    'label' => Html::encode($label),
                                    'disabled' => in_array($value, $this->disabledItems[0]),
                                    'labelOptions' => [
                                        'class' => 'btn btn-lg rounded-0 btn-outline-warning courier text-uppercase mr-0_5 mb-0_5 p-0 d-flex justify-content-center align-items-center float-left ' . (in_array($value, $this->disabledItems[0]) ? 'disabled text-muted border-gray-500 pointer-events-none' : ''),
                                        'disabled' => in_array($value, $this->disabledItems[0]),
                                        'style' => '
                                            width: 40px;
                                            height: 40px;
                                        ',
                                    ],
                                    'data-href' => Url::toRoute([
                                        'cart/element/update',
                                        'store_id' => Yii::$app->params['store_id'],
                                    ]),
                                    'data-filter-id' => $optionId,
                                    'data-name' => Html::encode($optionData['name']),
                                    'data-id' => $id,
                                    'data-data' => $optionData,
                                    'class' => $cssClass,
                                ]);
                            },
                            // 'itemOptions' => [
                                // 'data-href' => Url::toRoute([
                                    // 'cart/element/update',
                                    // 'store_id' => Yii::$app->params['store_id'],
                                // ]),
                                // 'data-filter-id' => $optionId,
                                // 'data-name' => Html::encode($optionData['name']),
                                // 'data-id' => $id,
                                // 'class' => $cssClass,
                                // 'labelOptions' => [
                                    // 'class' => 'btn btn-lg rounded-0 btn-outline-primary mr-1 mb-1 p-0 d-flex justify-content-center align-items-center float-left',
                                    // 'style' => '
                                        // width: 58px;
                                        // height: 58px;
                                    // ',
                                // ],
                            // ],
                        ]
                    );
                }

                if ($this->type == 'select'){
                    $options[] = Html::tag('div', $list, [
                        'class' => 'dvizh-option w-100'
                    ]);
                } else {
                    $options[] = Html::tag('div', $optionLabel . Html::tag('div', $list, [
                        'class' => 'dvizh-option dvizh-option-heading btn-group btn-group-toggle w-100',
                        'data' => [
                            'toggle' => 'buttons',
                        ],
                    ]), [
                        'class' => ''
                     ]);
                }
                $i++;
            }
        } else {
            return null;
        }

        return Html::tag('div', implode('', $options), [
            'class' => 'dvizh-change-options ' . $this->cssClass
        ]);
    }

    private function _defaultValue($option)
    {
        if (isset($this->defaultValues[$option])) {
            return $this->defaultValues[$option];
        }

        return false;
    }
}
