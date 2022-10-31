<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use dosamigos\grid\columns\EditableColumn;

$this->title = json_decode($model->name)->{Yii::$app->language};
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('back', 'Товары'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = Yii::t('back', 'Обновить');
\dvizh\shop\assets\BackendAsset::register($this);
?>

<div class="row">
    <div class="col-xs-12">
        <div class="product-update">

            <ul class="nav nav-tabs hidden">
                <li class="active product-tab-label">
                    <a href="#product-product" data-toggle="tab">
                        <?= Yii::t('back', 'Инфо') ?>
                    </a>
                </li>
                <li class="options-tab-label hidden">
                    <a href="#product-modifications" data-toggle="tab">
                        <?= Yii::t('back', 'Опции') ?>
                    </a>
                </li>
                <li class="prices-tab-label">
                    <a href="#product-prices" data-toggle="tab">
                        <?= Yii::t('back', 'Цены') ?>
                    </a>
                </li>
                <li class="filters-tab-label">
                    <a href="#product-filters" data-toggle="tab">
                        <?= Yii::t('back', 'Фильтры') ?>
                    </a>
                </li>
                <li class="addfileds-tab-label">
                    <a href="#product-fields" data-toggle="tab">
                        <?= Yii::t('back', 'Дополнительные поля') ?>
                    </a>
                </li>
            </ul>

            <div class="tab-content product-updater">
                <div class="tab-pane active" id="product-product">
                    <?php 
                        echo $this->render('_form', [
                            'model' => $model,
                            'searchModel' => $searchModel,
                            'modificationDataProvider' => $modificationDataProvider,
                            'searchModificationModel' => $searchModificationModel,                        
                            'languages' => $languages,
                            'priceModel' => $priceModel,
                            'dataProvider' => $dataProvider,
                            'stores' => $stores,
                        ])
                    ?>
                </div>

                <div class="tab-pane hidden" id="product-modifications">
                    <?php if (Yii::$app->session->hasFlash('modification-success-added')){ ?>
                        <div class="alert alert-success" role="alert">
                            <?= Yii::$app->session->getFlash('modification-success-added') ?>
                        </div>
                    <?php } ?>

                    <?php
                        // echo GridView::widget([
                        // 'dataProvider' => $modificationDataProvider,
                        // 'filterModel' => $searchModificationModel,
                        // 'columns' => [
                            // ['class' => 'yii\grid\SerialColumn', 'options' => ['style' => 'width: 20px;']],
                            // ['attribute' => 'id', 'filter' => false, 'options' => ['style' => 'width: 25px;']],
                            // [
                                // 'class' => EditableColumn::className(),
                                // 'attribute' => 'name',
                                // 'url' => ['/shop/modification/edit-field'],
                                // 'type' => 'text',
                                // 'editableOptions' => [
                                    // 'mode' => 'inline',
                                // ],
                                // 'options' => ['style' => 'width: 75px;']
                            // ],
                            // [
                                // 'class' => EditableColumn::className(),
                                // 'attribute' => 'sort',
                                // 'url' => ['/shop/modification/edit-field'],
                                // 'type' => 'text',
                                // 'editableOptions' => [
                                    // 'mode' => 'inline',
                                // ],
                                // 'options' => ['style' => 'width: 49px;']
                            // ],
                            // [
                                // 'class' => EditableColumn::className(),
                                // 'attribute' => 'available',
                                // 'url' => ['/shop/modification/edit-field'],
                                // 'type' => 'select',
                                // 'editableOptions' => [
                                    // 'mode' => 'inline',
                                    // 'source' => ['yes', 'no'],
                                // ],
                                // 'filter' => Html::activeDropDownList(
                                    // $searchModel,
                                    // 'available',
                                    // ['no' => 'Нет', 'yes' => 'Да'],
                                    // ['class' => 'form-control', 'prompt' => 'Наличие']
                                // ),
                                // 'contentOptions' => ['style' => 'width: 27px;']
                            // ],
                            // [
                                // 'class' => EditableColumn::className(),
                                // 'attribute' => 'price',
                                // 'label' => 'Цена',
                                // 'url' => ['/shop/modification/edit-field'],
                                // 'type' => 'text',
                                // 'editableOptions' => [
                                    // 'mode' => 'inline',
                                // ],
                                // 'options' => ['style' => 'width: 40px;']
                            // ],
                            // [
                                // 'class' => EditableColumn::className(),
                                // 'attribute' => 'oldPrice',
                                // 'url' => ['/shop/modification/edit-field'],
                                // 'type' => 'text',
                                // 'label' => 'Старая цена',
                                // 'editableOptions' => [
                                    // 'mode' => 'inline',
                                // ],
                                // 'options' => ['style' => 'width: 40px;']
                            // ],
                            // ['class' => 'yii\grid\ActionColumn', 'controller' => 'modification', 'template' => '{update} {delete}'],
                        // ],
                    // ]);
                    ?>
                    <!--
                    <p><a href="#modificationModal" data-toggle="modal" data-target="#modificationModal" class="btn btn-success add-product-modification">Добавить <span class="glyphicon glyphicon-plus add-price"></span></a></p>
                    <div class="modal fade" id="modificationModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Товары</h4>
                                </div>
                                <div class="modal-body">
                                    <iframe src="<?=Url::toRoute(['/shop/modification/add-popup', 'productId' => $model->id]);?>" id="modification-add-window"></iframe>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    -->
                </div>

                <div class="tab-pane" id="product-prices">
                    <?php if ($dataProvider->getCount()) { ?>
                        <?php 
                            echo GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => null,
                                'summary' => false,
                                'columns' => [
                                    // ['class' => 'yii\grid\SerialColumn', 'options' => ['style' => 'width: 20px;']],
                                    // ['attribute' => 'id', 'filter' => false, 'options' => ['style' => 'width: 25px;']],
                                    [
                                        // 'class' => EditableColumn::className(),
                                        'attribute' => 'name',
                                        // 'url' => ['price/edit-field'],
                                        // 'type' => 'text',
                                        'filter' => false,
                                        // 'editableOptions' => [
                                            // 'mode' => 'inline',
                                        // ],
                                        'headerOptions' => [
                                            'class' => 'text-center',
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-center text-nowrap',
                                        ],
                                    ],
                                    [
                                        'class' => EditableColumn::className(),
                                        'attribute' => 'code',
                                        'url' => ['price/edit-field'],
                                        'type' => 'text',
                                        'filter' => false,
                                        'editableOptions' => [
                                            'mode' => 'inline',
                                        ],
                                        'headerOptions' => [
                                            'class' => 'text-center',
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-center text-nowrap',
                                        ],
                                    ],
                                    [
                                        'attribute' => 'available',
                                        'format' => 'raw',
                                        'filter' => false,
                                        'value' => function ($price) {
                                            return Html::tag('big', 
                                                Html::tag('span', '', [
                                                    'class' => 'glyphicon ' . ($price->available == 'yes' ? 'glyphicon-ok text-success' : 'glyphicon-remove text-danger')
                                                ])
                                            );
                                        },
                                        'headerOptions' => [
                                            'class' => 'text-center',
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-center text-nowrap',
                                        ],
                                    ],
                                    [
                                        // 'class' => EditableColumn::className(),
                                        'attribute' => 'price',
                                        // 'url' => ['price/edit-field'],
                                        // 'type' => 'text',
                                        'filter' => false,
                                        // 'editableOptions' => [
                                            // 'mode' => 'inline',
                                        // ],
                                        'headerOptions' => [
                                            'class' => 'text-center',
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-center text-nowrap',
                                        ],
                                    ],
                                    [
                                        // 'class' => EditableColumn::className(),
                                        'attribute' => 'price_old',
                                        // 'url' => ['price/edit-field'],
                                        // 'type' => 'text',
                                        'filter' => false,
                                        // 'editableOptions' => [
                                            // 'mode' => 'inline',
                                        // ],
                                        'headerOptions' => [
                                            'class' => 'text-center',
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-center text-nowrap',
                                        ],
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn', 
                                        'controller' => 'price', 
                                        // 'filter' => false,
                                        'template' => '{delete}',
                                        'buttonOptions' => [
                                            'class' => 'btn btn-sm btn-danger'
                                        ], 
                                    ],
                                ],
                            ]);
                        ?>
                    <?php } else { ?>
                        <p style="color: red;">У товара нет цен.</p>
                    <?php } ?>
                    <?php
                        echo $this->render('price/_form', [
                            'model' => $priceModel,
                            'productModel' => $model,
                        ])
                    ?> 
                </div>

                <div class="tab-pane" id="product-filters">
                    <?php 
                        if ($filterPanel = \dvizh\filter\widgets\Choice::widget(['model' => $model])) {
                            echo $filterPanel;
                        } else {
                    ?>
                        <p>В настоящий момент к категории данного товара не привязан ни один фильтр. Управлять фильтрами можно <?=Html::a('здесь', ['/filter/filter/index']);?>.</p>
                    <?php } ?>
                </div>


                <div class="tab-pane" id="product-fields">
                    <?php if($fieldPanel = \dvizh\field\widgets\Choice::widget(['model' => $model])) { ?>
                        <?php // echo $fieldPanel;?>
                    <?php } else { ?>
                        <p>Поля не заданы. Задать можно <?=Html::a('здесь', ['/field/field/index']);?>.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>