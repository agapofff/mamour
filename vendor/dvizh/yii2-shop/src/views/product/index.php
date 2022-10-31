<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dvizh\shop\models\Category;
use dvizh\shop\models\Producer;
use kartik\export\ExportMenu;

use yii\widgets\Pjax;
use kartik\alert\AlertBlock;

$this->title = Yii::t('back', 'Товары');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('back', 'Магазин'),
    'url' => ['/shop/default/index']
];
$this->params['breadcrumbs'][] = $this->title;

\dvizh\shop\assets\BackendAsset::register($this);
?>

<?php Pjax::begin(); ?>

<?= AlertBlock::widget([
        'type' => 'growl',
        'useSessionFlash' => true,
        'delay' => 1,
    ]);
?>

<div class="product-index">
    
    <!--
    <div class="row">
        <div class="col-md-6">
            <?php
                /*
                $gridColumns = [
                    'id',
                    'code',
                    'category.name',
                    'producer.name',
                    'name',
                    'price',
                    'amount',
                ];
                echo ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumns
                ]);
                */
            ?>
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle dvizh-mass-controls disabled" data-toggle="dropdown">
                    <span class="glyphicon glyphicon-cog "></span>
                    <span class="caret "></span>
                </button>
                <ul class="dropdown-menu dvizh-model-control">
                    <li data-action="edit">
                        <a data-toggle="modal" data-target="#modal-control-model" data-model="<?= $dataProvider->query->modelClass ?>" class="dvizh-mass-edit" href="#">Редактиовать выбранные</a>
                    </li>
                    <li data-action="delete" >
                        <a  data-model="<?= $dataProvider->query->modelClass ?>" data-action="<?= Url::to(['/shop/product/mass-deletion']) ?>" class="dvizh-mass-delete" href="#">Удалить выбранные</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    -->

    
    <?= Html::a(Html::tag('span', '', [
        'class' => 'glyphicon glyphicon-plus'
    ]) . '&nbsp;' . Yii::t('back', 'Создать'), [
        'create',
        'category_id' => Yii::$app->request->get('ProductSearch')['category_id']
    ], [
        'class' => 'btn btn-success',
        'data-pjax' => 0,
    ]) ?>
    
    <br style="clear: both;"></div>
    
    <?php
        echo \yii\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'summary' => false,
            'tableOptions' => [
                'class' => 'table table-striped table-bordered' . ($ordering ? ' sortable ' . $ordering : ''),
                'data-ordering' => Url::to(['ordering']),
            ],
            'columns' => [
                // ['class' => '\kartik\grid\CheckboxColumn'],
                
                // ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'sort',
                    'format' => 'html',
                    'filter' => false,
                    'value' => function ($model) use ($ordering) {
                        return Html::tag('i', '', [
                            'class' => 'fa fa-sort ' . ($ordering ? 'text-info sort-handler' : 'text-muted')
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
                    'attribute' => 'id',
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                    'headerOptions' => [
                        'class' => 'text-center',
                        'style' => 'width: 100px'
                    ],
                    'filterInputOptions' => [
                        'class' => 'form-control text-center',
                        'placeholder' => Yii::t('back', 'Поиск...'),
                    ],
                ],
                
                [
                    'attribute' => 'name',
                    'label' => Yii::t('back', 'Товар'),
                    'format' => 'raw',
                    'contentOptions' => [
                        'class' => 'text-center',
                        'style' => 'vertical-align: center',
                    ],
                    'headerOptions' => [
                        'class' => 'text-center'
                    ],
                    'filterInputOptions' => [
                        'class' => 'form-control text-center',
                        'placeholder' => Yii::t('back', 'Поиск...'),
                    ],
                    'value' => function ($model) {
                        $name = json_decode($model->name)->{Yii::$app->language};
						$image = $model->getImage();
                        if ($image){
                            return Html::a(Html::tag('div', Html::tag('div', Html::img($image->getUrl('50x50')), [
                                'class' => 'media-left'
                            ]) . Html::tag('div', $name, [
                                'class' => 'media-body media-middle text-left'
                            ]), [
                                'class' => 'media'
                            ]), [
                                'update',
                                'id' => $model->id,
                                'ref' => Url::current([], true),
                            ], [
                                'data-pjax' => 0,
                            ]);
                        } else {
                            return Html::a($name, [
                                'update',
                                'id' => $model->id,
                                'ref' => Url::current([], true),
                            ], [
                                'data-pjax' => 0,
                            ]);
                        }
                    }
                ],
                
                /*
                [
                    'attribute' => 'images',
                    'format' => 'images',
                    'filter' => false,
                    'content' => function($model){
                        if ($image = $model->getImage()->getUrl('50x50')){
                            return Html::a(Html::img($image), ['update', 'id' => $model->id]);
                        }
                    }
                ],
                */
                /*
                [
                    'attribute' => 'code',
                    'format' => 'raw',
                    'contentOptions' => [
                        // 'style' => 'min-width: 200px'
                    ],
                    'headerOptions' => [
                        'class' => 'text-center',
                        'style' => 'width: 20%'
                    ],
                    'filterInputOptions' => [
                        'class' => 'form-control text-center',
                        'placeholder' => Yii::t('back', 'Поиск...'),
                    ],
                ],
                */
                /*
                [
                    'attribute' => 'amount',
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                    'format' => 'raw',
                    'filter' => false,
                    'value' => function($data){
                        return $data->amount > 0 ? Html::tag('span', '', [
                            'class' => 'glyphicon glyphicon-ok text-success'
                        ]) : Html::tag('span', '', [
                            'class' => 'glyphicon glyphicon-remove text-danger'
                        ]);
                    },
                ],
                */
                
                /*
                [
                    'attribute' => 'price',
                    'label' => Yii::t('back', 'Цена'),
                    'headerOptions' => [
                        'class' => 'text-center',
                    ],
                    'filterInputOptions' => [
                        'class' => 'form-control text-center',
                        'placeholder' => Yii::t('back', 'Поиск...'),
                    ],
                    'contentOptions' => [
                        'class' => 'text-right',
                    ],
                    'content' => function ($model) {
                        $prices = '';
                        foreach($model->prices as $price){
                            $prices .= Html::tag('p', Html::tag('span', $price->price, [
                                'title' => $price->name
                            ]));
                            // $return .= "<p class=\"productsMenuPrice\"><span title=\"{$price->name}\">{$price->price}</span></p>";
                        }
                        return $prices;
                    }
                ],
                */
                
                // [
                    // 'attribute' => 'available',
                    // 'format' => 'raw',
                    // 'contentOptions' => [
                        // 'class' => 'text-center'
                    // ],
                    // 'headerOptions' => [
                        // 'class' => 'text-center',
                        // 'style' => 'min-width: 90px'
                    // ],
                    // 'filter' => Html::activeDropDownList(
                        // $searchModel,
                        // 'available',
                        // [
                            // 0 => Yii::t('back', 'Нет'),
                            // 1 => Yii::t('back', 'Да'),
                        // ], [
                            // 'class' => 'form-control',
                            // 'prompt' => Yii::t('back', 'Все'),
                        // ]
                    // ),
                    // 'value' => function ($data) {
                        // return Html::a(Html::tag('big', '', [
                            // 'class' => 'glyphicon glyphicon-' . ($data->available ? 'ok text-success' : 'remove text-danger')
                        // ]), [
                            // 'active',
                            // 'id' => $data->id
                        // ], [
                            // 'class' => 'pjax'
                        // ]);
                    // },
                // ],
				
                // [
                    // 'attribute' => 'is_new',
                    // 'format' => 'raw',
                    // 'contentOptions' => [
                        // 'class' => 'text-center'
                    // ],
                    // 'headerOptions' => [
                        // 'class' => 'text-center',
                        // 'style' => 'min-width: 90px'
                    // ],
                    // 'filter' => Html::activeDropDownList(
                        // $searchModel,
                        // 'is_new',
                        // [
                            // 0 => Yii::t('back', 'Нет'),
                            // 1 => Yii::t('back', 'Да'),
                        // ], [
                            // 'class' => 'form-control',
                            // 'prompt' => Yii::t('back', 'Все'),
                        // ]
                    // ),
                    // 'value' => function ($data) {
                        // return Html::a(Html::tag('big', '', [
                            // 'class' => 'glyphicon glyphicon-' . ($data->is_new ? 'ok text-success' : 'remove text-danger')
                        // ]), [
                            // 'active',
                            // 'id' => $data->id
                        // ], [
                            // 'class' => 'pjax'
                        // ]);
                    // },
                // ],
				
                // [
                    // 'attribute' => 'is_popular',
                    // 'format' => 'raw',
                    // 'contentOptions' => [
                        // 'class' => 'text-center'
                    // ],
                    // 'headerOptions' => [
                        // 'class' => 'text-center',
                        // 'style' => 'min-width: 90px'
                    // ],
                    // 'filter' => Html::activeDropDownList(
                        // $searchModel,
                        // 'is_popular',
                        // [
                            // 0 => Yii::t('back', 'Нет'),
                            // 1 => Yii::t('back', 'Да'),
                        // ], [
                            // 'class' => 'form-control',
                            // 'prompt' => Yii::t('back', 'Все'),
                        // ]
                    // ),
                    // 'value' => function ($data) {
                        // return Html::a(Html::tag('big', '', [
                            // 'class' => 'glyphicon glyphicon-' . ($data->is_popular ? 'ok text-success' : 'remove text-danger')
                        // ]), [
                            // 'active',
                            // 'id' => $data->id
                        // ], [
                            // 'class' => 'pjax'
                        // ]);
                    // },
                // ],
				
                // [
                    // 'attribute' => 'is_promo',
                    // 'format' => 'raw',
                    // 'contentOptions' => [
                        // 'class' => 'text-center'
                    // ],
                    // 'headerOptions' => [
                        // 'class' => 'text-center',
                        // 'style' => 'min-width: 90px'
                    // ],
                    // 'filter' => Html::activeDropDownList(
                        // $searchModel,
                        // 'is_promo',
                        // [
                            // 0 => Yii::t('back', 'Нет'),
                            // 1 => Yii::t('back', 'Да'),
                        // ], [
                            // 'class' => 'form-control',
                            // 'prompt' => Yii::t('back', 'Все'),
                        // ]
                    // ),
                    // 'value' => function ($data) {
                        // return Html::a(Html::tag('big', '', [
                            // 'class' => 'glyphicon glyphicon-' . ($data->is_popular ? 'ok text-success' : 'remove text-danger')
                        // ]), [
                            // 'active',
                            // 'id' => $data->id
                        // ], [
                            // 'class' => 'pjax'
                        // ]);
                    // },
                // ],
                
                [
                    'attribute' => 'category_id',
                    'format' => 'raw',
                    'headerOptions' => [
                        'class' => 'text-center',
                        'style' => 'min-width: 150px;'
                    ],
                    'filter' => Html::activeDropDownList(
                        $searchModel,
                        'category_id',
                        Category::buildTextTree(),
                        [
                            'class' => 'form-control',
                            'prompt' => Yii::t('back', 'Все'),
                        ]
                    ),
                    // 'value' => 'category.name'
                    'value' => function ($model) use ($categories) {
                        $html = '';
                        $cats = [];
                        foreach ($model->categories as $key => $category) {
                            $productCategories = array_reverse(Category::getAllParents($categories, $category->id, false, true));
                            foreach ($productCategories as $productCategory) {
                                $cats[$key][] = Html::a(json_decode($productCategory['name'])->{Yii::$app->language}, [
                                    '/shop/category/update',
                                    'id' => $productCategory['id']
                                ], [
                                    'data-pjax' => 0
                                ]);
                            }
                        }
                        if (!empty($cats)) {
                            foreach ($cats as $cat) {
                                $html .= Html::tag('div', join(' / ', $cat));
                            }
                        }
                        return $html;
                    }
                ],
                
                /*
                [
                    'attribute' => 'producer_id',
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                    'headerOptions' => [
                        'class' => 'text-center'
                    ],
                    'filter' => Html::activeDropDownList(
                        $searchModel,
                        'producer_id',
                        ArrayHelper::map(Producer::find()->orderBy('name')->all(), 'id', 'name'),
                        ['class' => 'form-control', 'prompt' => 'Производитель']
                    ),
                    'value' => 'producer.name'
                ],
                */
                
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {copy} {delete}',
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
                        'copy' => function ($url, $model) {
                            return Html::a('', [
								'copy',
								'id' => $model->id,
							], [
                                'class' => 'glyphicon glyphicon-duplicate btn btn-info btn-xs',
                                'title' => Yii::t('back', 'Копировать'),
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

</div>

<?php Pjax::end(); ?>

<!--

<div class="modal fade" id="modal-control-model">
    <div class="modal-dialog modal-mass-update">
        <div class="modal-content">
            <div class="notification-error text-center" style="display: none;">
                <div class="col-xs-12 alert alert-danger">
                    <button class="close">×</button>
                    <div class="glyphicon glyphicon-exclamation-sign"></div>
                    Вы ничего не выбрали
                    <div>
                    </div>

                </div>
            </div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title">Выберите поля для редактирования</h3>
                <p>Вы можете редактировать одновременно несколько записей.
                    Выберете записи из списка выше, отметьте галочкой поля,
                    которые нужно отредактировать, и нажмите на кнопку
                    "Редактировать выбранные".</p>
            </div>
            <div class="modal-body mass-update-body">
                <ul class="nav nav-tabs nav-mass-update">
                    <li class="active"><a href="#product-fields" data-toggle="tab">Поля</a></li>
                    <li><a href="#product-filters" data-toggle="tab">Фильтры</a></li>
                    <li><a href="#product-more-fields" data-toggle="tab">Доп. поля</a></li>
                    <li>
                        <a href="#empty">
                            <?=  Html::checkbox('images', false, [
                                'label' => 'Картинки',
                                'value' => 'images',
                                'class' => 'dvizh-mass-edit-images'
                            ]) ?>
                        </a>
                    </li>
                    <li>
                        <a href="#empty">
                            <?=  Html::checkbox('prices', false, [
                                'label' => 'Цены',
                                'value' => 'prices',
                                'class' => 'dvizh-mass-edit-prices'
                            ]) ?>
                        </a>
                    </li>
                </ul>
                <div class="tab-content product-updater">
                    <div class="tab-pane active" id="product-fields">
                        <?php if(!empty($model)) { ?>
                            <div class="row dvizh-mass-edit-filds">
                                <?php foreach ($model->attributeLabels() as $nameAttribute => $labelAttribute) { ?>
                                    <?php if(ArrayHelper::isIn($nameAttribute, $ignoreAttribute)) continue; ?>
                                    <div class="col-sm-4">
                                        <?=  Html::checkbox($nameAttribute, true, ['label' => $labelAttribute, 'value' => $nameAttribute,]) ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <p class="cm-check-items-group">
                                <a class="cm-check-items cm-on" data-type="filds">Выбрать все</a> |
                                <a class="cm-check-items cm-off" data-type="filds">Снять выделение со всех</a>
                            </p>
                        <?php } ?>
                    </div>
                    <div class="tab-pane" id="product-filters">
                        <?php if(!empty($filters)) { ?>
                            <div class="row dvizh-mass-edit-filters">
                                <?php foreach ($filters as $filter) { ?>
                                    <div class="col-sm-4">
                                        <?=  Html::checkbox($filter->slug, false, [
                                            'label' => $filter->name,
                                            'value' => $filter->id,
                                        ]) ?>
                                    </div>
                                <?php } ?>
                                <div class="col-sm-12">
                                    <p class="cm-check-items-group">
                                        <a class="cm-check-items cm-on" data-type="filters">Выбрать все</a> |
                                        <a class="cm-check-items cm-off" data-type="filters">Снять выделение со всех</a>
                                    </p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="tab-pane" id="product-more-fields">
                        <div class="row dvizh-mass-edit-more-fields">
                            <?php if (!empty($model)){ ?>
                                <?php foreach ($model->getFields() as $filter) { ?>
                                    <div class="col-sm-4">
                                        <?=  Html::checkbox($filter->slug, false, [
                                            'label' => $filter->name,
                                            'value' => $filter->id,
                                        ]) ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <div class="col-sm-12">
                                <p class="cm-check-items-group">
                                    <a class="cm-check-items cm-on" data-type="more-fields">Выбрать все</a> |
                                    <a class="cm-check-items cm-off" data-type="more-fields">Снять выделение со всех</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="product-images">
                        <div class="row dvizh-mass-edit-images">
                            <div class="col-sm-4">
                                <?=  Html::checkbox('images', false, ['label' => 'Картинки', 'value' => 'images']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="product-prices">
                        <div class="row dvizh-mass-edit-prices">
                            <div class="col-sm-12">
                                <b>Цены</b>
                            </div>
                            <div class="col-sm-4">
                                <?=  Html::checkbox('prices', false, ['label' => 'Цены', 'value' => 'prices']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <?= Html::a(null, ['product/mass-update'], [
                    'data-method' => 'POST',
                    'data-params' => null,
                    'data-role' => 'link-mass-update'
                ]) ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary dvizh-mass-update-btn">Редактировать выбранные</button>

            </div>
        </div>
    </div>
</div>

-->