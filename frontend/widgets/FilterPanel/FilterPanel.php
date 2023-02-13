<?php
namespace frontend\widgets\FilterPanel;

use yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dvizh\filter\models\Filter;
use dvizh\filter\models\FieldRelationValue;
use yii2mod\slider\IonSlider;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\web\View;

class FilterPanel extends \yii\base\Widget
{
    public $itemId = null;
    public $filterId = null;
    public $itemCssClass = 'item';
    public $fieldName = 'filter';
    public $blockCssClass = 'block';
    public $findModel = false; //::find() модели, по которой будем искать соответствия
    public $ajaxLoad = false; //Ajax подгрузка результатов
    public $resultHtmlSelector = null; //CSS селектор, который хранит результаты
    public $submitButtonValue = 'Показать';
    public $actionRoute = false;
    public $filterGetParamName = 'filter';
    public $productsSizes = null;
    public $productsPrices = null;
    public $products = [];
    public $isSearch = false;
    
    public function init()
    {
        parent::init();

        // if ($this->ajaxLoad) {
            // \dvizh\filter\assets\FrontendAjaxAsset::register($this->getView());
        // } else {
            // \dvizh\filter\assets\FrontendAsset::register($this->getView());
        // }
    }

    public function run()
    {
        $params = [
            'is_filter' => 'yes'
        ];

        if ($this->filterId) {
            $params['id'] = $this->filterId;
        }

        $filters = Filter::find()
            ->orderBy('sort ASC')
            ->andWhere($params)
            ->all();

        $return = [];
        
        
        // поиск
        $return[] = Html::hiddenInput('search', Yii::$app->request->get('search'));
        // $title = Html::tag('p', Yii::t('front', 'Поиск'), [
            // 'class' => 'm-0'
        // ]);
        // $block = Html::input('text', 'search', Yii::$app->request->get('search'), [
            // 'class' => 'form-control mb-0 px-0 pt-1_5 pb-1',
            // 'autocomplete' => rand(),
            // 'placeholder' => Yii::t('front', 'Поиск...'),
        // ]);
        // $submitButton = Html::submitButton('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/></svg>', [
            // 'class' => 'btn btn-link position-absolute top-0 right-0 mt-0_5 pr-0'
        // ]);
        // $return[] = Html::tag('div', $title . Html::tag('div', $block . $submitButton, [
            // 'class' => 'form-group mb-2 position-relative floating-label',
        // ]), [
            // 'class' => 'col-12 col-md-6 col-lg-6 col-xl-3 mb-1', // $this->blockCssClass
        // ]);
        
        
        // сортировка
        $escape = new JsExpression("function(m) { return m; }");
        $view = $this->getView();
        $view->registerJs("
            function sortListFormat(state) {
                if (!state.id) return state.text; // optgroup
                src = '" . Yii::$app->urlManager->baseUrl . "/images/sort/' +  state.id.toLowerCase() + '.svg'
                return '<img src=\"' + src + '\"/> ' + state.text;
            }
        ");

        if (count($this->products) > 1) {
            $title = Html::tag('p', Yii::t('front', 'Сортировка'), [
                'class' => 'm-0'
            ]);
            $block = Select2::widget([
                'name' => 'sort',
                'value' => Yii::$app->request->get('sort'),
                'data' => [
                    '' => Yii::t('front', 'По умолчанию'),
                    'name' => Yii::t('front', 'По названию А-Я'),
                    '-name' => Yii::t('front', 'По названию Я-А'),
                    'price' => Yii::t('front', 'По возрастанию цены'),
                    '-price' => Yii::t('front', 'По убыванию цены'),
                ],
                'language' => Yii::$app->language,
                'theme' => Select2::THEME_MATERIAL,
                'hideSearch' => true,
                'pluginOptions' => [
                    // 'placeholder' => Yii::t('front', 'По умолчанию'),
                    // 'templateResult' => new JsExpression('sortListFormat'),
                    // 'templateSelection' => new JsExpression('sortListFormat'),
                    // 'escapeMarkup' => $escape,
                ],
                'pluginEvents' => [
                    'select2:select' => new JsExpression("
                        function () {
                            $('#products-filters').submit();
                        }
                    "),
                    'select2:unselect' => new JsExpression("
                        function () {
                            $('#products-filters').submit();
                        }
                    "),
                ],
            ]);
            $return[] = Html::tag('div', $title.$block, [
                'class' => $this->blockCssClass
            ]);
        }
        

        // цена
        if ($this->productsPrices) {
            $title = Html::tag('p', Yii::t('front', 'Цена'), [
                'class' => 'm-0'
            ]);
            $prices = Yii::$app->request->get('price') ? explode(';', Yii::$app->request->get('price')) : [min($this->productsPrices), max($this->productsPrices)];
            $block = IonSlider::widget([
                'name' => 'price',
                'type' => 'double',
                'pluginOptions' => [
                    'skin' => 'square',
                    'drag_interval' => true,
                    'grid' => true,
                    'min' => min($this->productsPrices),
                    'max' => max($this->productsPrices),
                    'from' => $prices[0],
                    'to' => $prices[1],
                    'step' => 100,
                    'onFinish' => new JsExpression("
                        function (data) {
                            $('#products-filters').submit();
                        }
                    "),
                ]
            ]);
            $return[] = Html::tag('div', $title . Html::tag('div', Html::tag('div', $block, [
                'class' => 'col-11',
                'style' => 'margin-top: 0.4rem !important',
            ]), [
                'class' => 'row justify-content-center'
            ]), [
                'class' => $this->blockCssClass
            ]);
        }
        
        
        // размеры
        if ($this->productsSizes) {
            $title = Html::tag('p', Yii::t('front', 'Размеры'), [
                'class' => 'm-0'
            ]);
            $block = Select2::widget([
                'name' => 'sizes',
                'value' => Yii::$app->request->get('sizes'),
                'data' => $this->productsSizes,
                'language' => Yii::$app->language,
                'theme' => Select2::THEME_MATERIAL,
                'showToggleAll' => false,
                'hideSearch' => true,
                'pluginOptions' => [
                    'multiple' => true,
                    'placeholder' => Yii::t('front', 'Все'),
                    'allowClear' => true,
                ],
                'pluginEvents' => [
                    'select2:select' => new JsExpression("
                        function () {
                            $('#products-filters').submit();
                        }
                    "),
                    'select2:unselect' => new JsExpression("
                        function () {
                            $('#products-filters').submit();
                        }
                    "),
                ],
            ]);
            $return[] = Html::tag('div', $title.$block, [
                'class' => $this->blockCssClass
            ]);
        }
        
        if ($filters && Yii::$app->request->get('search')) {
            foreach ($filters as $filter) {
                if (
                    empty($this->itemId) 
                    || in_array($this->itemId, $filter->selected)
                ) {
                    $block = '';
                    $title = Html::tag('p', Yii::t('front', $filter->name), [
                        'class' => 'm-0'
                    ]);
                    
                    if ($this->findModel) {
                        $variants = $filter->getVariantsByFindModel($this->findModel)->all();
                    } else {
                        $variants = $filter->variants;
                    }

                    if ($filter->type == 'range') {
                        $max = 0;
                        $min = 0;
                        foreach ($variants as $variant) {
                            if ($max < $variant->numeric_value) {
                                $max = $variant->numeric_value;
                            }
                            if ($min > $variant->numeric_value) {
                                $min = $variant->numeric_value;
                            }
                        }
                        
                        $fieldName = $this->fieldName.'['.$filter->id.']';
      
                        $from = $min;
                        $to = $max;
                        
                        $value = Yii::$app->request->get($this->fieldName)[$filter->id];
                        
                        if ($value) {
                            $values = explode(';', $value);
                            $from = $values[0];
                            $to = $values[1];
                        }
                        
                        if (!empty($variants)) {
                            $step = round($max/count($variants));
                        } else {
                            $step = 1;
                        }

                        $block = IonSlider::widget([
                            'name' => $fieldName,
                            'value' => $value,
                            'type' => "double",
                            'pluginOptions' => [
                                'drag_interval' => true,
                                'grid' => true,
                                'min' => $min,
                                'max' => $max,
                                'from' => $from,
                                'to' => $to,
                                'step' => $step,
                            ]
                        ]);
                        
                    } else if ($filter->type == 'select') {
                        
                        $fieldName = $this->fieldName.'['.$filter->id.']';
                        
                        $variantsList = ArrayHelper::map($variants, 'id', 'value');

                        foreach ($variantsList as $varKey => $varVal) {
                            $variantsList[$varKey] = json_decode($varVal)->{Yii::$app->language}; // Yii::t('front', $varVal);
                        }
                        
                        asort($variantsList);
                        
                        $view->registerJs("
                            function planeTextOptionFormat(state) {
                                return state.text;
                            }
                            function formatColorFilterResult(state) {
                                if (!state.id) return state.text;
                                return '<div class=\"row align-items-center\"><div class=\"col-auto p-0\"><img src=\"/images/colors/' + state.id.toLowerCase() + '.jpg\" style=\"width:30px; box-shadow: 0 0 3px #ccc; margin-top: 0;\"/></div><div class=\"col-auto pr-0\">' + state.text + '</div></div>';
                            }
                            function formatColorFilterSelection(state) {
                                if (!state.id) return state.text;
                                return '<div class=\"row align-items-center\"><div class=\"col-auto pr-0\"><img src=\"/images/colors/' + state.id.toLowerCase() + '.jpg\" style=\"width:14px; box-shadow: 0 0 3px #fff;\"/></div><div class=\"col-auto\">' + state.text + '</div></div>';
                            }
                        ");

                        $block = Select2::widget([
                            'name' => $fieldName,
                            'value' => Yii::$app->request->get($this->fieldName) ? Yii::$app->request->get($this->fieldName)[$filter->id] : null,
                            'data' => $variantsList,
                            'language' => Yii::$app->language,
                            'theme' => Select2::THEME_MATERIAL,
                            'showToggleAll' => false,
                            'hideSearch' => true,
                            'pluginOptions' => [
                                'multiple' => true,
                                'placeholder' => Yii::t('front', 'Все'),
                                'allowClear' => true,
                                'templateResult' => new JsExpression($filter->id == 5 ? "formatColorFilterResult" : "planeTextOptionFormat"),
                                'templateSelection' => new JsExpression($filter->id == 5 ? "formatColorFilterSelection" : "planeTextOptionFormat"),
                                'escapeMarkup' => new JsExpression("function(m) {
                                    return m;
                                }")
                            ],
                            'pluginEvents' => [
                                'select2:select' => new JsExpression("
                                    function (data) {
                                        $('#products-filters').submit();
                                    }
                                "),
                                'select2:unselect' => new JsExpression("
                                    function (data) {
                                        $('#products-filters').submit();
                                    }
                                "),
                            ],
                        ]);
                        
                    } else {
                        
                        foreach ($variants as $variant) {
                            $checked = false;
                            
                            if ($filterData = Yii::$app->request->get($this->filterGetParamName)) {
                                if ($this->findModel) {
                                    $filterParams = $this->findModel->convertFilterUrl($filterData);
                                } else {
                                    $filterParams = $filterData;
                                }
                                if (
                                    isset($filterParams[$filter->id]) 
                                    && (
                                        isset($filterParams[$filter->id][$variant->id]) 
                                        ||  $filterParams[$filter->id] == $variant->id
                                    )
                                ) {
                                    $checked = true;
                                }
                            }

                            if (!in_array($filter->type, array('radio', 'checkbox', 'range'))) {
                                $filter->type = 'checkbox';
                            }

                            if ($filter->type == 'radio') {
                                $fieldName = $this->fieldName.'['.$filter->id.']';
                            } else {
                                $fieldName = $this->fieldName.'['.$filter->id.']['.$variant->id.']';
                            }

                            $field = Html::input($filter->type, $fieldName, $variant->id, ['checked' => $checked, 'data-item-css-class' => $this->itemCssClass, 'id' => "variant{$variant->id}"]);

                            if ($this->actionRoute) {
                                $field .= Html::label(Html::a($variant->value, $this->buildUrl($filter->slug, $variant->latin_value, $filter->type, $checked)), "variant{$variant->id}"); 
                            } else {
                                $field .= Html::label($variant->value, "variant{$variant->id}"); 
                            }
                            
                            $block .= Html::tag('div', $field);
                        }
                    }
                    
                    if (!empty($variants)) {
                        $return[] = Html::tag('div', $title . $block, [
                            'class' => $this->blockCssClass
                        ]);
                    }
                }
            }
        }
        
        if (!$this->isSearch || Yii::$app->request->get('search')) {
            $return[] = Html::tag('div', Html::a(Yii::t('front', 'Сбросить'), explode('?', Url::to())[0], [
                'class' => 'btn btn-outline-secondary',
            ]), [
                'class' => 'col-12 mt-1 text-center'
            ]);
        }

        if ($return) {
            // $return[] = Html::input('submit', '', $this->submitButtonValue, ['class' => 'btn btn-submit']);

            // foreach(Yii::$app->request->get() as $key => $value) {
                // if(!is_array($value)) {
                    // $return[] = Html::input('hidden', Html::encode($key), Html::encode($value));
                // }
            // }

            return Html::tag('form', implode('', $return), [
                'id' => 'products-filters',
                'data-resulthtmlselector' => $this->resultHtmlSelector, 
                'name' => 'dvizh-filter', 
                'action' => $this->actionRoute, 
                'class' => 'dvizh-filter row mt-1 mt-md-0',
                'data-pjax' => true,
            ]);
        }
        
        return null;
    }
    
    public function buildUrl($filterSlug, $variantValue, $filterType = 'radio', $checked = false)
    {
        if(!is_array($this->actionRoute) | is_array(Yii::$app->request->get($this->filterGetParamName))) {
            return '#';
        }
        
        if($params = Yii::$app->request->get($this->filterGetParamName)) {
            $filterString = explode('_and_', $params);
        } else {
            $filterString = [];
        }
        
        $params = [];
        
        //decompose
        foreach($filterString as $filterData) {
            $filterData = explode('_is_', $filterData);
            $params[$filterData[0]] = explode('_or_', $filterData[1]);
        }
        
        if(!isset($params[$filterSlug])) {
            $params[$filterSlug] = [];
        }
        
        if($filterType == 'checkbox') {
            if(!in_array($variantValue, $params[$filterSlug])) {
                $params[$filterSlug][$variantValue] = $variantValue;
            }
        } else {
            $params[$filterSlug] = [$variantValue => $variantValue];
        }
        
        //compose
        $filterString = [];
        foreach($params as $filterSlug => $filterVariants) {
            if($checked) {
                foreach($filterVariants as $key => $val) {
                    if($val == $variantValue) {
                        unset($filterVariants[$key]);
                        
                    }
                }
            }
            if($filterVariants) {
                $filterString[] = $filterSlug . '_is_' . implode('_or_', $filterVariants);
            }
        }
        
        $this->actionRoute[$this->filterGetParamName] = implode('_and_', $filterString);
        
        return Url::toRoute($this->actionRoute);
    }
}
