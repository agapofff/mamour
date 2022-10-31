<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\alert\AlertBlock;
use jino5577\daterangepicker\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BonusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('back', 'Бонусы');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonus-index">

    <?= Html::a(Html::tag('span', '', [
            'class' => 'glyphicon glyphicon-plus'
        ]) . '&nbsp;' . Yii::t('back', 'Создать'), ['create'], [
            'class' => 'btn btn-success'
        ]);
    ?>

    <?php Pjax::begin(); ?>

        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'summary' => false,
                'columns' => [
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
                        'filter' => Html::activeDropDownList(
                            $searchModel, 
                            'active', 
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
                        'attribute' => 'user_id',
                        'format' => 'raw',
                        'filter' => Html::activeDropDownList(
                            $searchModel, 
                            'user_id', 
                            ArrayHelper::map($users, 'id', 'username'), [
                                'class' => 'form-control',
                                'prompt' => Yii::t('back', 'Все'),
                            ]
                        ),
                        'value' => function ($model) use ($users) {
                            return ArrayHelper::index($users, 'id')[$model->user_id]->username;
                        },
                        'headerOptions' => [
                            'class' => 'text-center'
                        ],
                        'contentOptions' => [
                            'class' => 'text-center'
                        ],
                    ],
                    
                    [
                        'attribute' => 'type',
                        'format' => 'raw',
                        'filter' => Html::activeDropDownList(
                            $searchModel, 
                            'type', 
                            [
                                0 => 'Списание',
                                1 => 'Начисление'
                            ], 
                            [
                                'class' => 'form-control',
                                'prompt' => Yii::t('back', 'Все'),
                            ]
                        ),
                        'value' => function ($model) {
                            return $model->type ? 'Начисление' : 'Списание';
                        },
                        'headerOptions' => [
                            'class' => 'text-center'
                        ],
                        'contentOptions' => [
                            'class' => 'text-center'
                        ],
                    ],
                    
                    [
                        'attribute' => 'amount',
                        'filterInputOptions' => [
                            'class' => 'form-control text-center',
                            'placeholder' => 'Поиск...'
                        ],
                        'contentOptions' => [
                            'class' => 'text-center'
                        ],
                        'headerOptions' => [
                            'class' => 'text-center'
                        ],
                    ],
                    
                    [
                        'attribute' => 'reason',
                        'format' => 'raw',
                        'filter' => Html::activeDropDownList(
                            $searchModel, 
                            'type', 
                            [
                                'Списание' => Yii::$app->params['bonus']['minus'],
                                'Начисление' => Yii::$app->params['bonus']['plus'],
                            ], 
                            [
                                'class' => 'form-control',
                                'prompt' => Yii::t('back', 'Все'),
                            ]
                        ),
                        'value' => function ($model) {
                            return Yii::$app->params['bonus'][$model->type ? 'plus' : 'minus'][$model->reason];
                        },
                        'headerOptions' => [
                            'class' => 'text-center'
                        ],
                        'contentOptions' => [
                            'class' => 'text-center'
                        ],
                    ],
                    
                    [
                        'attribute' => 'created_at',
                        'format' => ['datetime', 'php:d.m.Y H:i'],
                        'filter' => DateRangePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'created_at',
                            'locale' => 'ru-RU',
                            'pluginOptions' => [
                                'format' => 'd.m.Y',
                                'locale' => [
                                    'applyLabel' => 'Применить',
                                    'cancelLabel' => 'Отмена'
                                ],
                                'autoUpdateInput' => false
                            ],
                            'maskOptions' => [
                                'mask' => '99.99.9999 - 99.99.9999',
                            ],
                            'options' => [
                                'class' => 'form-control text-center',
                                'placeholder' => 'Поиск...'
                            ]
                        ]),
                        'headerOptions' => [
                            'class' => 'text-center'
                        ],
                        'contentOptions' => [
                            'class' => 'text-center'
                        ],
                    ],
                    
                    [
                        'attribute' => 'updated_at',
                        'format' => ['datetime', 'php:d.m.Y H:i'],
                        'filter' => DateRangePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'updated_at',
                            'locale' => 'ru-RU',
                            'pluginOptions' => [
                                'format' => 'd.m.Y',
                                'locale' => [
                                    'applyLabel' => 'Применить',
                                    'cancelLabel' => 'Отмена'
                                ],
                                'autoUpdateInput' => false
                            ],
                            'maskOptions' => [
                                'mask' => '99.99.9999 - 99.99.9999',
                            ],
                            'options' => [
                                'class' => 'form-control text-center',
                                'placeholder' => 'Поиск...'
                            ]
                        ]),
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

    <?php Pjax::end(); ?>

</div>
