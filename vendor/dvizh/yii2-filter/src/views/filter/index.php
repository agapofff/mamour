<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\alert\AlertBlock;

$this->title = 'Фильтры и опции';
$this->params['breadcrumbs'][] = $this->title;

\dvizh\filter\assets\Asset::register($this);

?>

<div class="filter-index">
    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="tabs row">
        <div class="col-md-6">
            <ul class="nav nav-tabs" role="tablist">
                <li <?php if($tab == 'filters') { ?>class="active"<?php } ?>><a href="<?=Url::toRoute(['/filter/filter/index', 'tab' => 'filters']);?>">Фильтры</a></li>
                <li <?php if($tab == 'options') { ?>class="active"<?php } ?>><a href="<?=Url::toRoute(['/filter/filter/index', 'tab' => 'options']);?>">Опции</a></li>
            </ul>
        </div>
    </div>

    <div class="info-block">
        <?php if($tab == 'filters') { ?>
            <p class="bg-info">По фильтрам покупатели выбирают подходящий по характеристикам товар.</p>
        <?php } else { ?>
            <p class="bg-info">Опции - это характеристики, которые участвуют в формировании модификации и могут влиять на цену товара при выборе определенной комбинации этих характеристик.</p>
        <?php } ?>
    </div>

    <?php Pjax::begin(); ?>
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => [
                    'class' => 'table table-striped table-bordered' . ($sort ? ' sortable ' . $sort : ''),
                    'data-sort' => Url::to(['/filter/filter/sort']),
                ],
                'columns' => [
                    // ['class' => 'yii\grid\SerialColumn'],
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
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a($model->name, [
                                'update',
                                'id' => $model->id,
                            ], [
                                'data-pjax' => 0
                            ]);
                        },
                        'headerOptions' => [
                            'class' => 'text-center'
                        ],
                        'contentOptions' => [
                            'class' => 'text-center'
                        ],
                        'filterInputOptions' => [
                            'class' => 'form-control text-center',
                            'placeholder' => 'Поиск...'
                        ],
                    ],
                    // 'slug',
                    // [
                        // 'attribute' => 'category',
                        // 'label' => 'Категория',
                        // 'content' => function($model) {
                            // $return = [];
                            // foreach($model->relation_field_value as $category) {
                                // $fieldValues = Yii::$app->getModule('filter')->relationFieldValues;
                                // if(isset($fieldValues[$category])) {
                                    // $return[] = $fieldValues[$category];
                                // }
                            // }
                            
                            // return implode(', ', $return);
                        // },
                        // 'filter' => false,
                    // ],
                    [
                        'attribute' => 'type',
                        'content' => function ($model) {
                            if ($model->type == 'checkbox') {
                                return 'Много вариантов';
                            } elseif ($model->type == 'radio') {
                                return 'Один вариант';
                            }
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'type',
                            Yii::$app->getModule('filter')->types,
                            [
                                'class' => 'form-control', 
                                'prompt' => 'Тип'
                            ]
                        ),
                    ],
                    // 'description',
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
