<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use kartik\alert\AlertBlock;
use dvizh\order\assets\Asset;

Asset::register($this);

$this->title = Yii::t('back', 'Способы доставки');
// $this->params['breadcrumbs'][] = [
    // 'label' => Yii::t('back', 'Заказы'),
    // 'url' => ['/order/default/index']
// ];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="shippingtype-index">

    <?= Html::a(Html::tag('span', '', [
            'class' => 'glyphicon glyphicon-plus'
        ]) . '&nbsp;' . Yii::t('back', 'Создать'), [
            'create',
        ], [
            'class' => 'btn btn-success',
            'data-pjax' => 0,
        ])
    ?>

    <?php Pjax::begin(); ?>

        <?= AlertBlock::widget([
                'type' => 'growl',
                'useSessionFlash' => true,
                'delay' => 1,
            ]);
        ?>

        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'summary' => false,
                'tableOptions' => [
                    'class' => 'table table-striped table-bordered' . ($sort ? ' sortable ' . $sort : ''),
                    'data-sort' => Url::to(['sort']),
                ],
                'columns' => [
                    [
                        'attribute' => 'sort',
                        'format' => 'html',
                        'filter' => false,
                        'value' => function ($model) use ($sort) {
                            return Html::tag('i', '', [
                                'class' => 'fa fa-sort ' . ($sort ? 'text-info sort-handler' : 'text-muted')
                            ]);
                        },
                        'headerOptions' => [
                            'class' => 'text-center',
                            'style' => 'width: 50px;',
                        ],
                        'contentOptions' => [
                            'class' => 'text-center'
                        ],
                    ],
                    [
                        'attribute' => 'id',
                        'filterInputOptions' => [
                            'class' => 'form-control text-center',
                            'placeholder' => 'Поиск...'
                        ],
                        'headerOptions' => [
                            'class' => 'text-center',
                            'style' => 'width: 100px',
                        ],
                        'contentOptions' => [
                            'class' => 'text-center',
                        ],
                    ],
                    [
                        'attribute' => 'active',
                        'format' => 'html',
                        'filter' => Html::activeDropDownList($searchModel, 'active', [
                                0 => Yii::t('back', 'Нет'),
                                1 => Yii::t('back', 'Да'),
                            ], [
                                'class' => 'form-control',
                                'prompt' => Yii::t('back', 'Все'),
                            ]
                        ),
                        'value' => function ($data) {
                            return Html::a(Html::tag('big', '', [
                                    'class' => 'glyphicon glyphicon-' . ($data->active ? 'ok text-success' : 'remove text-danger')
                                ]), [
                                    'active',
                                    'id' => $data->id
                                ], [
                                    'class' => 'pjax'
                                ]);
                        },
                        'headerOptions' => [
                            'class' => 'text-center'
                        ],
                        'contentOptions' => [
                            'class' => 'text-center'
                        ],
                    ],

                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a(json_decode($model->name)->{Yii::$app->language}, [
                                'update',
                                'id' => $model->id
                            ], [
                                'data-pjax' => 0,
                            ]);
                        },
                        'headerOptions' => [
                            'class' => 'text-center',
                            // 'style' => 'width: 20%'
                        ],
                        'filterInputOptions' => [
                            'class' => 'form-control text-center',
                            'placeholder' => Yii::t('back', 'Поиск...'),
                        ],
                    ],
                    
                    [
                        'attribute' => 'country_id',
                        'format' => 'raw',
                        'value' => function ($model) use ($countries) {
                            return Html::a(json_decode(ArrayHelper::getValue(ArrayHelper::map($countries, 'id', 'name'), $model->country_id))->{Yii::$app->language}, [
                                '/countries/update',
                                'id' => $model->country_id,
                            ], [
                                'data-pjax' => 0,
                            ]);
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'country_id',
                            ArrayHelper::map($countries, 'id', function ($country) {
                                return json_decode($country->name)->{Yii::$app->language};
                            }),
                            [
                                'class' => 'form-control',
                                'prompt' => Yii::t('back', 'Все'),
                            ]
                        ),
                        'headerOptions' => [
                            'class' => 'text-center'
                        ],
                        'contentOptions' => [
                            'class' => 'text-center'
                        ],
                    ],
                    
                    // [
                        // 'attribute' => 'postcodes',
                        // 'format' => 'html',
                        // 'value' => function ($model) {
                            // return $model->postcodes ? join('<br>', json_decode($model->postcodes)) : '';
                        // },
                        // 'filterInputOptions' => [
                            // 'class' => 'form-control text-center',
                            // 'placeholder' => Yii::t('back', 'Поиск...'),
                        // ],
                        // 'headerOptions' => [
                            // 'class' => 'text-center'
                        // ],
                        // 'contentOptions' => [
                            // 'class' => 'text-center'
                        // ],
                    // ],
                    
                    [
                        'attribute' => 'cost',
                        'format' => 'raw',
                        'contentOptions' => [
                            'class' => 'text-right'
                        ],
                        'headerOptions' => [
                            'class' => 'text-center',
                        ],
                        'filterInputOptions' => [
                            'class' => 'form-control text-center',
                            'placeholder' => Yii::t('back', 'Поиск...'),
                        ],
                        'headerOptions' => [
                            'class' => 'text-center'
                        ],
                        'contentOptions' => [
                            'class' => 'text-center',
                        ],
                    ],

                    [
                        'attribute' => 'payment_types',
                        'format' => 'raw',
                        'value' => function ($model) use ($paymentTypes) {
                            $paymentTypes = ArrayHelper::map($paymentTypes, 'id', 'name');
                            $paymentTypesNames = [];
                            if ($modelPaymentTypes = explode(',', $model->payment_types)) {
                                foreach ($modelPaymentTypes as $modelPaymentType) {
                                    $paymentTypesNames[] = Html::a(json_decode(ArrayHelper::getValue($paymentTypes, $modelPaymentType))->{Yii::$app->language}, [
                                        '/order/payment-type/update',
                                        'id' => $modelPaymentType,
                                    ], [
                                        'data-pjax' => 0
                                    ]);
                                }
                            }
                            return join('<br>', $paymentTypesNames);
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'country_id',
                            ArrayHelper::map($paymentTypes, 'id', function ($paymentType) {
                                return json_decode($paymentType->name)->{Yii::$app->language};
                            }),
                            [
                                'class' => 'form-control',
                                'prompt' => Yii::t('back', 'Все'),
                            ]
                        ),
                        'headerOptions' => [
                            'class' => 'text-center'
                        ],
                        'contentOptions' => [
                            'class' => 'text-center'
                        ],
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'contentOptions' => [
                            'class' => 'text-center'
                        ],
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return Html::a('', $url, [
                                    'class' => 'glyphicon glyphicon-pencil btn btn-primary btn-xs',
                                    'title' => Yii::t('back', 'Изменить'),
                                    'data-pjax' => 0,
                                ]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('', $url, [
                                    'class' => 'glyphicon glyphicon-trash btn btn-danger btn-xs',
                                    'title' => Yii::t('back', 'Удалить'),
                                    'data' => [
                                        'pjax' => 0,
                                        'confirm' => Yii::t('back', 'Вы уверены, что хотите удалить этот элемент?'),
                                        'method' => 'post'
                                    ]
                                ]);
                            },
                        ]
                    ],

                ],
            ]); 
        ?>
    
    <?php Pjax::end() ?>
</div>