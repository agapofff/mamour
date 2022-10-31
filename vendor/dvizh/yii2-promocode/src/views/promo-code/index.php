<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\alert\AlertBlock;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PromoCodesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Промокоды';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promo-codes-index">
    <?= Html::a(Html::tag('span', '', [
            'class' => 'glyphicon glyphicon-plus'
        ]) . '&nbsp;' . Yii::t('back', 'Создать'), ['create'], [
            'class' => 'btn btn-success'
        ]);
    ?>

    <?php if (isset(Yii::$app->getModule('promocode')->orderModel)) { ?>
        <br>
        <div class="well pull-right">
            <p>
                <?= Html::a('Статистика по промокодам', ['statistic'], ['class' => 'btn btn-primary btn-block']) ?>
            </p>
            <p>
                <?= Html::a('Статистика за период', ['period-statistic'], ['class' => 'btn btn-primary btn-block']) ?>
            </p>
        </div>
        <br>
    <?php } ?>  

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
                        'attribute' => 'status',
                        'format' => 'html',
                        'filter' => Html::activeDropDownList(
                            $searchModel, 
                            'status', 
                            [
                                0 => Yii::t('back', 'Нет'),
                                1 => Yii::t('back', 'Да'),
                            ], 
                            [
                                'class' => 'form-control',
                                'prompt' => Yii::t('back', 'Все'),
                            ]
                        ),
                        'value' => function ($model) {
                            return Html::a(Html::tag('big', '', [
                                    'class' => 'glyphicon glyphicon-' . ($model->status ? 'ok text-success' : 'remove text-danger')
                                ]), [
                                    'active',
                                    'id' => $model->id
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
                        'attribute' => 'title',
                        'filterInputOptions' => [
                            'class' => 'form-control text-center',
                            'placeholder' => Yii::t('back', 'Поиск...'),
                        ],
                        'headerOptions' => [
                            'class' => 'text-center'
                        ],
                        'contentOptions' => [
                            'class' => 'text-center'
                        ],
                    ],
                    [
                        'attribute' => 'description',
                        'filterInputOptions' => [
                            'class' => 'form-control text-center',
                            'placeholder' => Yii::t('back', 'Поиск...'),
                        ],
                        'headerOptions' => [
                            'class' => 'text-center'
                        ],
                        'contentOptions' => [
                            'class' => 'text-center'
                        ],
                    ],
                    [
                        'attribute' => 'code',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a($model->code, [
                                'update',
                                'id' => $model->id
                            ], [
                                'title' => Yii::t('back', 'Изменить'),
                                'data-pjax' => 0,
                            ]);
                        },
                        'filterInputOptions' => [
                            'class' => 'form-control text-center',
                            'placeholder' => Yii::t('back', 'Поиск...'),
                        ],
                        'headerOptions' => [
                            'class' => 'text-center'
                        ],
                        'contentOptions' => [
                            'class' => 'text-center'
                        ],
                    ],
                    [
                        'attribute' => 'discount',
                        'value' => function ($model) {
                            return $model->discount . ($model->type == 'percent' ? '%' : '');
                        },
                        'filterInputOptions' => [
                            'class' => 'form-control text-center',
                            'placeholder' => Yii::t('back', 'Поиск...'),
                        ],
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
