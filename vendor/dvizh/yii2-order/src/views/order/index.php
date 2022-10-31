<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use dvizh\order\widgets\Informer;
use kartik\export\ExportMenu;
use nex\datepicker\DatePicker;
use dvizh\order\assets\Asset;
use dvizh\order\assets\OrdersListAsset;
use dvizh\order\widgets\AssigmentToOrder;

$this->title = Yii::t('back', 'Заказы');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('back', 'Заказы'),
    'url' => ['/order/default/index']
];
$this->params['breadcrumbs'][] = $this->title;


Asset::register($this);
OrdersListAsset::register($this);

if($dateStart = Yii::$app->request->get('date_start')) {
    $dateStart = date('Y-m-d', strtotime($dateStart));
}

if($dateStop = Yii::$app->request->get('date_stop')) {
    $dateStop = date('Y-m-d', strtotime($dateStop));
}

$timeStart = Yii::$app->request->get('time_start');
$timeStop = Yii::$app->request->get('time_stop');

$columns = [];

// $columns[] = [
    // 'class' => \yii\grid\SerialColumn::className(),
// ];

$columns[] = [
    'attribute' => 'id',
    'options' => ['style' => 'width: 50px;'],
    'contentOptions' => [
     'class' => 'show-details'
    ],
    'headerOptions' => [
        'class' => 'text-center'
    ],
    'filterInputOptions' => [
        'class' => 'form-control text-center',
        'placeholder' => Yii::t('back', 'Поиск...'),
    ],
];
if(Yii::$app->getModule('order')->showCountColumn){
    $columns[] = [
        'attribute' => 'count',
        'label' => Yii::t('back', 'Шт.'),
        'contentOptions' => [
            'class' => 'show-details'
        ],
        'content' => function($model) {
            return $model->count;
        }
    ];

 }

$columns[] = [
    'attribute' => 'base_cost',
    'label' => Yii::t('back', 'Цена'),
    'contentOptions' => [
        'class' => 'show-details text-right',
    ],
    'headerOptions' => [
        'class' => 'text-center',
    ],
];

$columns[] = [
    'attribute' => 'cost',
    'label' => Yii::t('back', 'Сумма'),
    'contentOptions' => [
        'class' => 'show-details text-right',
    ],
    'headerOptions' => [
        'class' => 'text-center',
    ],
    'content' => function($model) {
        $total = $model->cost;
        if($model->promocode) {
            $total .= Html::tag('div', $model->promocode, [
                'style' => 'color: orange; font-size: 80%;',
                Yii::t('back', 'Промокод')
            ]);
        }
        if  (is_object(Yii::$app->getModule('order')->discountDescriptionCallback)) {

            $callback = Yii::$app->getModule('order')->discountDescriptionCallback;
            $certificate = $callback($model->id);
            if  ($certificate) {
                $total .= Html::tag('div',$certificate->code, [
                    'style' => 'color: green; font-size: 80%;',
                    Yii::t('back', 'Сертификат')
                ]);
            }
        } else {
            $total .= '';
        }

        return $total;
    },
];

if(Yii::$app->getModule('order')->showPaymentColumn){
    $columns[] = [
        'attribute' => 'payment',
        'filter' => Html::activeDropDownList(
            $searchModel,
            'payment',
            [
                'yes' => Yii::t('back', 'Да'),
                'no' => Yii::t('back', 'Нет')
            ],
            [
                'class' => 'form-control',
                'prompt' => Yii::t('back', 'Оплачено')
            ]
        ),
        'value' => function($model){
            return Yii::t('back', $model->payment);
        },
        'headerOptions' => [
            'class' => 'text-center',
        ],
    ];
 }

foreach(Yii::$app->getModule('order')->orderColumns as $column) {
    if ($column == 'payment_type_id') {
        $column = [
            'attribute' => 'payment_type_id',
            'filter' => Html::activeDropDownList(
                $searchModel,
                'payment_type_id',
                $paymentTypes,
                [
                    'class' => 'form-control',
                    'prompt' => Yii::t('back', 'Способ оплаты')
                ]
            ),
            'contentOptions' => [
                'class' => 'show-details text-center hidden'
            ],
            'value' => function($model) use ($paymentTypes) {
                if (isset($paymentTypes[$model->payment_type_id])) {
                    return $paymentTypes[$model->payment_type_id];
                }
            },
            'headerOptions' => [
                'class' => 'text-center hidden',
            ],
            'filterOptions' => [
                'class' => 'hidden',
            ],
        ];
    } else if ($column == 'shipping_type_id') {
        $column = [
            'attribute' => 'shipping_type_id',
            'filter' => Html::activeDropDownList(
                $searchModel,
                'shipping_type_id',
                $shippingTypes,
                [
                    'class' => 'form-control',
                    'prompt' => Yii::t('back', 'Способ доставки')
                ]
            ),
            'contentOptions' => [
                'class' => 'show-details text-center'
            ],
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'value' => function($model) use ($shippingTypes) {
                if (isset($shippingTypes[$model->shipping_type_id])) {
                    return $shippingTypes[$model->shipping_type_id];
                }
            }
        ];
    } else if (is_array($column) && isset($column['field'])) {
        $column = [
            'attribute' => 'field',
            'label' => $column['label'],
            'contentOptions' => [
                'class' => 'show-details text-center'
            ],
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'value' => function($model) use ($column) {
                return $model->getField($column['field']);
            }
        ];
    } else if (is_array($column) && isset($column['content']) && is_callable($column['content'])) {
        $column = [
            'label' => $column['label'],
            'content' => function($model) use ($column) {
                $func = $column['content'];
                return $func($model);
            },
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ];
    }

    if (gettype($column) === 'string') {
        $column = [
            'attribute' => $column,
            'contentOptions' => [
                'class' => 'show-details text-center'
            ],
            'headerOptions' => [
                'class' => 'text-center',
            ],
        ];
    }

    $columns[] = $column;
}

$columns[] = [
    'attribute' => 'date',
    'filter' => false,
    'contentOptions' => [
        'class' => 'show-details text-center'
    ],
    'headerOptions' => [
        'class' => 'text-center',
    ],
    'value' => function($model) {
        return date(Yii::$app->getModule('order')->dateFormat, $model->timestamp);
    }
];

$columns[] = [
    'attribute' => 'status',
    'filter' => Html::activeDropDownList(
        $searchModel,
        'status',
        Yii::$app->getModule('order')->orderStatuses,
        [
            'class' => 'form-control',
            'prompt' => Yii::t('back', 'Статус')
        ]
    ),
    'format' => 'raw',
    'value' => function($model) use ($module) {
        if(!$model->status) {
            return null;
        }
        
        return Yii::$app->getModule('order')->orderStatuses[$model->status];

        // return \dvizh\order\widgets\ChangeStatus::widget(['model' => $model]);
    },
    'headerOptions' => [
        'class' => 'text-center',
    ],
    'contentOptions' => [
        'class' => 'text-center',
    ],
];

if ($module->elementToOrderUrl) {
    $columns[] = [
        'label' => Yii::t('back', 'Добавить в заказ'),
        'content' => function($model) use ($module) {
            return '<a href="'.Url::toRoute([$module->elementToOrderUrl, 'order_id' => $model->id]).'" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i></a>';
        }
    ];
}

$columns[] = ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {delete}'];


$order = Yii::$app->order;
?>

<!--
<div class="informer-widget">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><a href="#" style="border-bottom: 1px solid #ccc;" onclick="$('.order-statistics-body').toggle(); return false;"><?=Yii::t('back', 'Statistics');?>...</a></h3>
        </div>
        <div class="order-statistics-body" style="display: none;">
            <div class="panel-body">
                <?=Informer::widget();?>
            </div>
        </div>
    </div>
</div>
-->


    <form action="<?=Url::toRoute(['/order/order/index']);?>" class="row search">
        <?php
        foreach(Yii::$app->request->get() as $key => $value) {
            if(!is_array($value)) {
                echo Html::input('hidden', Html::encode($key), Html::encode($value));
            }
        }
        ?>
        <div class="col-md-4">
            <label><?=Yii::t('back', 'Дата');?></label>
            <div class="row">
                <div class="col-md-6">
                    <?= DatePicker::widget([
                        'name' => 'date_start',
                        'addon' => false,
                        'value' => $dateStart,
                        'size' => 'sm',
                        'language' => 'ru',
                        'placeholder' => Yii::t('back', 'Дата начала'),
                        'clientOptions' => [
                            'format' => 'L',
                            'minDate' => '2015-01-01',
                        ],
                        'dropdownItems' => [
                            [
                                'label' => Yii::t('back', 'Вчера'),
                                'url' => '#',
                                'value' => \Yii::$app->formatter->asDate('-1 day')
                            ],
                            [
                                'label' => Yii::t('back', 'Завтра'),
                                'url' => '#',
                                'value' => \Yii::$app->formatter->asDate('+1 day')
                            ],
                            [
                                'label' => Yii::t('back', 'Задать значение'),
                                'url' => '#',
                                'value' => Yii::t('back', 'Значение'),
                            ],
                        ],
                    ]);?>
                    <?php if($timeStart && !Yii::$app->request->get('OrderSearch')) { ?>
                        <input type="hidden" name="time_start" value="<?=Html::encode($timeStart);?>" />
                        <p><small><?=Yii::t('back', 'Date from');?>: <?=Html::encode($timeStart);?></small></p>
                    <?php } ?>
                </div>
                <div class="col-md-6">
                    <?= DatePicker::widget([
                        'name' => 'date_stop',
                        'addon' => false,
                        'value' => $dateStop,
                        'size' => 'sm',
                        'placeholder' => Yii::t('back', 'Дата окончания'),
                        'language' => 'ru',
                        'clientOptions' => [
                            'format' => 'L',
                            'minDate' => '2015-01-01',
                        ],
                        'dropdownItems' => [
                            [
                                'label' => Yii::t('back', 'Вчера'),
                                'url' => '#',
                                'value' => \Yii::$app->formatter->asDate('-1 day')
                            ],
                            [
                                'label' => Yii::t('back', 'Завтра'),
                                'url' => '#',
                                'value' => \Yii::$app->formatter->asDate('+1 day')
                            ],
                            [
                                'label' => Yii::t('back', 'Задать значение'),
                                'url' => '#',
                                'value' => Yii::t('back', 'Значение'),
                            ],
                        ],
                    ]);?>
                    <?php if($timeStop && !Yii::$app->request->get('OrderSearch')) { ?>
                        <input type="hidden" name="time_stop" value="<?=Html::encode($timeStop);?>" />
                        <p><small><?=Yii::t('back', 'Дата окончания');?>: <br /><?=Html::encode($timeStop);?></small></p>
                    <?php } ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 hidden">
            <label><?=Yii::t('back', 'Товары');?></label>
            <?php if ($elementPossibleNames = \Yii::$app->getModule('order')->searchByElementNameArray) { ?>
                <?= Html::dropDownList(
                        'elementName',
                        \Yii::$app->request->get('elementName'),
                        $elementPossibleNames,
                        [
                            'class' => 'form-control',
                            'prompt' => Yii::t('back', 'Все'),
                        ]);
                    ?>
            <?php } ?>
        </div>

        <div class="col-md-2">
            <label>&nbsp;</label>
            <div>
                <input class="btn btn-success" type="submit" value="<?=Yii::t('back', 'Поиск');?>"  />
                <a href="<?=Url::toRoute(['/order/order/index', 'tab' => $tab]);?>" class="btn btn-default"><i class="glyphicon glyphicon-remove-sign"></i></a>
            </div>
         </div>
    </form>


<?php if ($hasAssignments > 0) { ?>
    <div class="tabs row">
        <div class="col-md-6">
            <ul class="nav nav-tabs" role="tablist">
                <li <?php if($tab == 'orders') { ?>class="active"<?php } ?>>
                    <a href="<?=Url::toRoute(['/order/order/index', 'tab' => 'orders']);?>"><?=Yii::t('back', 'Orders');?></a>
                </li>
                <li <?php if($tab == 'assigments') { ?>class="active"<?php } ?>>
                    <a href="<?=Url::toRoute(['/order/order/index', 'tab' => 'assigments']);?>"><?=Yii::t('back', 'Assigments');?></a>
                </li>
            </ul>
        </div>
    </div>
<?php } ?>

    <div class="summary row hidden">
        <div class="col-md-4">
            <h3>
                <?=number_format($dataProvider->query->sum('cost'), 2, ',', '.');?>
                <?=$module->currency;?>
            </h3>
        </div>
        <div class="col-md-4">
            <?php
            if(Yii::$app->getModule('order')->showPaymentColumn){
                ?>
                <h3>
                    <?php
                    echo Yii::t('back', 'Оплачено') . ": ";
                    $query = clone $dataProvider->query;
                    echo number_format($query->where('payment <> \'no\'')->sum('cost'), 2, ',', '.') . $module->currency;
                    ?>
                </h3>
            <?php
            }
            ?>
        </div>
        <div class="col-md-4 export">
            <?php
            $gridColumns = $columns;
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
                
            ]);
            ?>
        </div>
    </div>

    <div class="order-list">
        <?=  \kartik\grid\GridView::widget([
            'export' => false,
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $columns,
            'summary' => false,
        ]); ?>
    </div>

