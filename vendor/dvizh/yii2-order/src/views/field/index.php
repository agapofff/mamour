<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use kartik\alert\AlertBlock;
use dvizh\order\assets\Asset;

Asset::register($this);

$this->title = Yii::t('back', 'Доп.поля заказа');
$this->params['breadcrumbs'][] = ['label' => Yii::t('back', 'Заказы'), 'url' => ['/order/order']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="field-index">

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
                            return Html::a($model->name, [
                                'update',
                                'id' => $model->id,
                            ], [
                                'data-pjax' => 0,
                            ]);
                        },
                        'filterInputOptions' => [
                            'class' => 'form-control text-center',
                            'placeholder' => 'Поиск...'
                        ],
                        'headerOptions' => [
                            'class' => 'text-center'
                        ],
                        'contentOptions' => [
                            'class' => 'text-center'
                        ],
                    ],
                    [
                        'attribute' => 'required',
                        'format' => 'html',
                        'filter' => Html::activeDropDownList(
                            $searchModel, 
                            'required', 
                            [
                                0 => Yii::t('back', 'Нет'),
                                1 => Yii::t('back', 'Да'),
                            ], 
                            [
                                'class' => 'form-control',
                                'prompt' => Yii::t('back', 'Все'),
                            ]
                        ),
                        'value' => function ($data) {
                            return Html::a(Html::tag('big', '', [
                                    'class' => 'glyphicon glyphicon-' . ($data->required ? 'ok text-success' : 'remove text-danger')
                                ]), [
                                    'required',
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
                    // [
                        // 'attribute' => 'type_id',
                        // 'filter' => Html::activeDropDownList($searchModel, 'type_id', $fieldTypes,[
                            // 'class' => 'form-control', 
                            // 'prompt' => Yii::t('back', 'Все')
                        // ]),
                        // 'value' => function ($model) use ($fieldTypes) {
                            // return  $fieldTypes[$model->type_id];
                        // },
                        // 'headerOptions' => [
                            // 'class' => 'text-center'
                        // ],
                        // 'contentOptions' => [
                            // 'class' => 'text-center'
                        // ],
                    // ],
                    [
                        'attribute' => 'description',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return json_decode($model->description)->{Yii::$app->language};
                        },
                        'filterInputOptions' => [
                            'class' => 'form-control text-center',
                            'placeholder' => 'Поиск...'
                        ],
                        'headerOptions' => [
                            'class' => 'text-center',
                        ],
                        'contentOptions' => [
                            'class' => 'text-center',
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
        
    <?php Pjax::end(); ?>

</div>
