<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use dvizh\order\assets\Asset;

$this->title = Yii::t('back', 'Заказ').' #'.$model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('back', 'Заказы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Asset::register($this);
?>
<div class="order-view">
    <?php if(Yii::$app->session->hasFlash('reSendDone')) { ?>
        <script>
        alert('<?= Yii::$app->session->getFlash('reSendDone') ?>');
        </script>
    <?php } ?>

    <!--
    <p>
        <?= Html::a(Yii::t('back', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('back', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('back', 'Realy?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-md-6">
            <?=dvizh\order\widgets\ChangeStatus::widget(['model' => $model]);?>
        </div>
        <div class="col-md-6">

        </div>

    </div>
    -->

    

    <?php
    $detailOrder = [
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'date',
                'value'        => date(Yii::$app->getModule('order')->dateFormat, $model->timestamp),
            ],
        ],
    ];
    
    if($model->client_name) {
        $detailOrder['attributes'][] = 'client_name';
    }
    
    if($model->phone) {
        $detailOrder['attributes'][] = 'phone';
    }

    if($model->email) {
        $detailOrder['attributes'][] = 'email:email';
    }
    
    if($model->promocode) {
        $detailOrder['attributes'][] = 'promocode';
    }

    if($model->comment) {
        $detailOrder['attributes'][] = 'comment';
    }
    
    if($model->address) {
        $detailOrder['attributes'][] = 'address';
    }
    
    if($model->payment_type_id && isset($paymentTypes[$model->payment_type_id])) {
        $detailOrder['attributes'][] = [
            'attribute' => 'payment_type_id',
            'value'        => @$paymentTypes[$model->payment_type_id],
        ];
    }
    
    if($model->shipping_type_id && isset($shippingTypes[$model->shipping_type_id])) {
		$detailOrder['attributes'][] = [
			'attribute' => 'shipping_type_id',
			'value'        => $shippingTypes[$model->shipping_type_id],
		];
    }

    if($model->delivery_type == 'totime') {
        $detailOrder['attributes'][] = 'delivery_time_date';
        $detailOrder['attributes'][] = 'delivery_time_hour';
        $detailOrder['attributes'][] = 'delivery_time_min';
    }

    if($fields = $fieldFind->all()) {
        foreach($fields as $fieldModel) {
            if (!in_array($fieldModel->name, [
                'city_id',
                'country_id',
                'delivery_id',
                'log_request',
                'log_response',
            ])){
                $detailOrder['attributes'][] = [
                    'label' => $fieldModel->description,
                    'value' => $fieldModel->getValue($model->id)
                ];
            }
        }
    }

    if($model->seller) {
        $detailOrder['attributes'][] = [
            'label' => Yii::t('back', 'Seller'),
            'value' => Html::encode($model->seller->name),
        ];
    }

    echo DetailView::widget($detailOrder);
    ?>

    <h2><?=Yii::t('back', 'Товары'); ?></h2>

    <?= \kartik\grid\GridView::widget([
        'export' => false,
        'summary' => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'item_id',
                'filter' => false,
                'label' => Yii::t('back', 'Товар'),
                'content' => function($model) {
                    if ($productModel = $model->product) {
                        $name = Html::a(Html::tag('p', json_decode($productModel->getCartName())->{Yii::$app->language}, [
                                    'class' => 'text-bold',
                                ]), [
                                    '/shop/product/update',
                                    'id' => $productModel->getCartId()
                                ], [
                                    'target' => '_blank',
                                ]);
                        
                        $description = '';
                        $elementOptions = json_decode($model->options);
                        $productOptions = $model->getModel()->getCartOptions();
                        if ($elementOptions) { 
                            foreach ($productOptions as $optionId => $optionData){
                                foreach ($elementOptions as $id => $value){
                                    if ($optionId == $id && $optionId == 1) {
                                        if(!$variantValue = $optionData['variants'][$value]) {
                                            $variantValue = 'deleted';
                                        }
                                        $description .= Html::tag('p', Html::tag('strong', Html::encode($optionData['name'])) . ': ' . Html::encode($variantValue));
                                    }
                                }
                                
                            }
                        }
                        
                        $description .= Html::tag('p', $model->description);
                        
                        $image = Html::tag('div', Html::a(Html::img($productModel->getImage()->getUrl('50x50')), [
                                    '/shop/product/update',
                                    'id' => $productModel->getCartId()
                                ], [
                                    'target' => '_blank',
                                ]), [
                                    'class' => 'media-left',
                                ]);
                        
                        return 
                            Html::tag('div', $image . Html::tag('div', $name . $description, [
                                'class' => 'media-body'
                            ]), [
                                'class' => 'media'
                            ]);
                    } else {
                        return Yii::t('back', 'Unknown product');
                    }
                }
            ],
            [
                'attribute' => 'count',
                'label' => Yii::t('back', 'Кол-во'),
                'headerOptions' => [
                    'class' => 'text-center',
                ],
                'contentOptions' => [
                    'class' => 'text-center',
                ],
            ],
            [
                'attribute' => 'base_price',
                'label' => Yii::t('back', 'Цена'),
                'value' => function($model){
                    $lang = null;
                    $elementOptions = json_decode($model->options);
                    $productOptions = $model->getModel()->getCartOptions();
                    if ($elementOptions) { 
                        foreach ($productOptions as $optionId => $optionData){
                            foreach ($elementOptions as $id => $value){
                                if ($optionId == $id && $optionId == 3){
                                    $lang = $optionData['variants'][$value];
                                    break;
                                }
                            }
                            
                        }
                    }
                    $currency = \common\models\Languages::findOne([
                        'code' => $lang
                    ])->currency;
                    return Yii::$app->formatter->asCurrency($model->base_price, $currency);
                },
                'headerOptions' => [
                    'class' => 'text-center',
                ],
                'contentOptions' => [
                    'class' => 'text-center text-nowrap',
                ],
            ],
            [
                'attribute' => 'price',
                'label' => Yii::t('back', 'Сумма'),
                'value' => function($model){
                    $sum = $model->count * $model->price;
                    $lang = null;
                    $elementOptions = json_decode($model->options);
                    $productOptions = $model->getModel()->getCartOptions();
                    if ($elementOptions) { 
                        foreach ($productOptions as $optionId => $optionData){
                            foreach ($elementOptions as $id => $value){
                                if ($optionId == $id && $optionId == 3){
                                    $lang = $optionData['variants'][$value];
                                    break;
                                }
                            }
                            
                        }
                    }
                    $currency = \common\models\Languages::findOne([
                        'code' => $lang
                    ])->currency;
                    return Yii::$app->formatter->asCurrency($sum, $currency);
                },
                'headerOptions' => [
                    'class' => 'text-center',
                ],
                'contentOptions' => [
                    'class' => 'text-center text-nowrap',
                ],
            ],
            // ['attribute' => 'price', 'label' => Yii::t('back', 'Price').' %'],
            // [
                // 'label' => Yii::t('back', 'Write-off'),
                // 'content' => function($elementModel) use ($model) {
                    // $return = '';
                    
                    // $product = $elementModel->getModel();
                    
                    // $style = '';
                    // $class = '';
                    
                    // if(Yii::$app->has('stock')) {
                        // $stockService = Yii::$app->stock;
                        
                        // if($stocks = $stockService->getAvailable()) {
                            // foreach($stocks as $stock) {
                                // $amount = $stock->getAmount($elementModel->item_id);
                                
                                // $return .= "<strong>{$stock->name}</strong> (<span class=\"amount\">{$amount}</span>) ";

                                // $count = $elementModel->count;
                                
                                // if($count > $amount) {
                                    // $count = $amount;
                                // }
                                
                                // if($count < 0) {
                                    // $count = 0;
                                // }
                                
                                // $input = Html::input('text', 'count', $count, ['class' => 'form-control', 'data-order-id' => $model->id, 'data-stock-id' => $stock->id, 'data-product-id' => $product->id]);
                                // $button = Html::tag('span', Html::button('<i class="glyphicon glyphicon-ok"></i>', ['class' => 'btn btn-success promo-code-enter-btn']), ['class' => 'input-group-btn']);

                                // $return .= Html::tag('div', $input.$button, ['class' => 'input-group', 'style' => 'width: 100px;']);
                            // }
                        // }
                        
                        // if($stockService->getOutcomingsByOrder($model->id, $product->id)) {
                            // $style = 'text-decoration: line-through;';
                            // $class = 'write-offed';
                        // }
                    // }
                    
                    // return Html::tag('div', $return, ['class' => 'outcomingWidget '.$class, 'style' => $style]);
                // }
            // ],
            // ['class' => 'yii\grid\ActionColumn', 'controller' => '/order/element', 'template' => '{delete}',  'buttonOptions' => ['class' => 'btn btn-default'], 'options' => ['style' => 'width: 75px;']],
        ],
    ]); ?>
    
    <?php
    if($elementToOrderUrl = Yii::$app->getModule('order')->elementToOrderUrl) {
        echo '<div style="text-align: right;"><a href="'.Url::toRoute([$elementToOrderUrl, 'order_id' => $model->id]).'" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i></a></div>';
    }
    ?>

    <h3 align="right">
        <?=Yii::t('back', 'Итого'); ?>: <?=$model->cost;?>

        <?php if ($model->promocode) { ?>
            (<?=Yii::t('back', 'Discount');?> <?php if(Yii::$app->has('promocode') && $code = Yii::$app->promocode->checkExists($model->promocode)) { echo " {$code->discount}%"; } ?>)
        <?php } else {
            
        } ?>
    </h3>

</div>
