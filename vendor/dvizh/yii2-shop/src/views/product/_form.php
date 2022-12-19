<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dvizh\shop\models\Category;
use dvizh\shop\models\Producer;
use agapofff\gallery\widgets\Gallery;
use kartik\select2\Select2;
use dvizh\seo\widgets\SeoForm;
use kartik\alert\AlertBlock;
use kartik\switchinput\SwitchInput;
use kartik\file\FileInput;
use yii\grid\GridView;
use dosamigos\grid\columns\EditableColumn;
use yii\bootstrap\Modal;
use yii\web\View;
use backend\widgets\MultilangField;
use PELock\ImgOpt\ImgOpt;

\dvizh\shop\assets\BackendAsset::register($this);

?>

<div style="
    position: fixed;
    left: -99999px;
    pointer-events: none;
">
<?php
    $images = $model->getImages();
    foreach ($images as $image) {
        foreach (Yii::$app->params['productImageSizes'] as $key => $size) {
            echo Html::img($image->getUrl($size));
            // echo ImgOpt::widget([
                // 'src' => $image->getUrl($size), 
            // ]);
        }
    }
?>
</div>

<div class="product-form">

    <?= AlertBlock::widget([
            'type' => 'growl',
            'useSessionFlash' => true,
            'delay' => 1,
        ]);
    ?>

    <?php
        $form = ActiveForm::begin([
            'id' => 'product-form',
            'options' => [
                'enctype' => 'multipart/form-data',
                // 'class' => 'form-horizontal',
            ]
        ]);
    ?>
    
<?php 
    if (!$model->isNewRecord){
?>
        <div class="row">
            <div class="col-xs-6 col-sm-4 col-md-2">
                <?= $form
                        ->field($model, 'active')
                        ->widget(SwitchInput::classname(), [
                            'pluginOptions' => [
                                'onText' => Yii::t('back', 'Да'),
                                'offText' => Yii::t('back', 'Нет'),
                                'onColor' => 'success',
                                'offColor' => 'danger',
                            ],
                        ]);
                ?>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-2">
                <?= $form
                        ->field($model, 'available')
                        ->widget(SwitchInput::classname(), [
                            'pluginOptions' => [
                                'onText' => Yii::t('back', 'Да'),
                                'offText' => Yii::t('back', 'Нет'),
                                'onColor' => 'success',
                                'offColor' => 'danger',
                            ],
                        ]);
                ?>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-2">
                <?= $form
                        ->field($model, 'new')
                        ->widget(SwitchInput::classname(), [
                            'pluginOptions' => [
                                'onText' => Yii::t('back', 'Да'),
                                'offText' => Yii::t('back', 'Нет'),
                                'onColor' => 'success',
                                'offColor' => 'danger',
                            ],
                        ]);
                ?>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-2">
                <?= $form
                        ->field($model, 'popular')
                        ->widget(SwitchInput::classname(), [
                            'pluginOptions' => [
                                'onText' => Yii::t('back', 'Да'),
                                'offText' => Yii::t('back', 'Нет'),
                                'onColor' => 'success',
                                'offColor' => 'danger',
                            ],
                        ]);
                ?>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-2">
                <?= $form
                        ->field($model, 'promo')
                        ->widget(SwitchInput::classname(), [
                            'pluginOptions' => [
                                'onText' => Yii::t('back', 'Да'),
                                'offText' => Yii::t('back', 'Нет'),
                                'onColor' => 'success',
                                'offColor' => 'danger',
                            ],
                        ]);
                ?>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-2">
                <?= $form
                        ->field($model, 'sale')
                        ->widget(SwitchInput::classname(), [
                            'pluginOptions' => [
                                'onText' => Yii::t('back', 'Да'),
                                'offText' => Yii::t('back', 'Нет'),
                                'onColor' => 'success',
                                'offColor' => 'danger',
                            ],
                        ]);
                ?>
            </div>
        </div>
<?php
    }
?>
        
        <div class="row">
            <div class="col-md-6">
                <?= MultilangField::widget([
                        'model' => $model,
                        'form' => $form,
                        'field' => 'name',
                        'languages' => ArrayHelper::getColumn($languages, 'code'),
                    ]);
                ?>
                
                <?= $form
                        ->field($model, 'slug')
                        ->textInput()
                ?>
            </div>
            
            <div class="col-md-6">
                    <?= $form
                            ->field($model, 'category_id')
                            ->widget(Select2::classname(), [
                                'data' => Category::buildTextTree(),
                                'language' => 'ru',
                                'options' => [
                                    'placeholder' => Yii::t('back', 'Выберите категорию')
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                    ?>
                    
                <?php // предустановка категории, из которой сделан переход
                    // if (!$model->id && Yii::$app->request->get('category_id')){
                        // $model->category_ids = [Yii::$app->request->get('category_id')];
                    // }
                ?>
                <?php /*
                    echo $form
                        ->field($model, 'category_ids')
                        ->label(Yii::t('back', 'Категории'))
                        ->widget(Select2::classname(), [
                            'data' => Category::buildTextTree(),
                            'theme' => Select2::THEME_DEFAULT,
                            // 'maintainOrder' => true,
                            'language' => 'ru',
                            'options' => [
                                'multiple' => true,
                                'placeholder' => Yii::t('back', 'Выберите категорию')
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    */
                ?>
                
                <div class="hidden">
                    <?= $form
                            ->field($model, 'producer_id')
                            ->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(Producer::find()->all(), 'id', 'name'),
                                'language' => 'ru',
                                'options' => [
                                    'placeholder' => Yii::t('back', 'Выберите бренд')
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                    ?>
                </div>
        
                <?= $form
                        ->field($model, 'code')
                        ->hiddenInput()
                        ->label(false)
                ?>
                
                <?= $form
                        ->field($model, 'sku')
                        ->textInput([
                            'maxlength' => true
                        ])
                ?>
                
                <?= $form
                        ->field($model, 'barcode')
                        ->textInput([
                            'maxlength' => true
                        ])
                ?>
                
                <?= $form
                        ->field($model, 'amount')
                        ->hiddenInput()
                        ->label(false)
                ?>
            </div>
        </div>
        
<?php 
    if (!$model->isNewRecord) {
?>
        <hr>
        <div class="row">
            <div class="col-lg-6">
                <label>
                    <?= Yii::t('back', 'Изображения') ?>
                </label>
                <?php Pjax::begin(); ?>
                    <?= Gallery::widget([
                            'model' => $model,
                            'previewSize' => '200x200',
                            'fileInputPluginOptions' => [
                                'showPreview' => false,
                            ],
                            'containerClass' => 'row',
                            'elementClass' => 'col-xs-6 col-sm-4 col-md-3 col-lg-4',
                            // 'deleteButtonClass' => 'btn btn-sm btn-danger position-absolute top-0 right-0',
                            'deleteButtonText' => Html::tag('i', '', ['class' => 'fa fa-trash']),
                            // 'editButtonClass' => 'btn btn-sm btn-info position-absolute bottom-0 right-0',
                            'editButtonText' => Html::tag('i', '', ['class' => 'fa fa-edit']),
                        ]);
                    ?>
                <?php Pjax::end(); ?>
                <br>
            </div>
            <div class="col-lg-6">
                <?= $form
                        ->field($model, 'video')
                        ->textInput()
                        ->hint(Yii::t('back', 'Ссылка на видео с Youtube'))
                ?>
        <?php 
            if ($model->video) {
                parse_str(parse_url($model->video, PHP_URL_QUERY), $videoVars);
                $this->registerJs("
                    $('.video').append('<iframe src=\"https://www.youtube.com/embed/" . $videoVars['v'] . "?controls=0&showinfo=0&rel=0&autoplay=0&loop=1&mute=1&playlist=" . $videoVars['v'] . "&modestbranding=1&iv_load_policy=3&autohide=1&fs=0&cc_load_policy=0&disablekb=0&origin=" . Url::home(true)  . "\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen\"></iframe>');
                ", View::POS_LOAD);
        ?>
                <div style="max-width: 400px">
                    <div class="video"></div>
                </div>
        <?php
            }
        ?>
            </div>
        </div>
        
        <hr>
        <div class="row">
            <div class="col-lg-6">
                <?= MultilangField::widget([
                        'model' => $model,
                        'form' => $form,
                        'field' => 'short_text',
                        'type' => 'html',
                        'languages' => ArrayHelper::getColumn($languages, 'code'),
                    ]);
                ?>
            </div>
            <div class="col-lg-6">
                <?= MultilangField::widget([
                        'model' => $model,
                        'form' => $form,
                        'field' => 'text',
                        'type' => 'html',
                        'languages' => ArrayHelper::getColumn($languages, 'code'),
                    ]);
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <?= MultilangField::widget([
                        'model' => $model,
                        'form' => $form,
                        'field' => 'compound',
                        'type' => 'html',
                        'languages' => ArrayHelper::getColumn($languages, 'code'),
                    ]);
                ?>
            </div>
            <div class="col-lg-6">
                <?= MultilangField::widget([
                        'model' => $model,
                        'form' => $form,
                        'field' => 'howtouse',
                        'type' => 'html',
                        'languages' => ArrayHelper::getColumn($languages, 'code'),
                    ]);
                ?>
            </div>
        </div>

        <hr>
        
        <div class="modifications">
            <label>Цены и наличие</label>
            <?php
                Pjax::begin([
                    'id' => 'product-modifications',
                    'enablePushState' => false
                ]);
            ?>
                <?php
                    if (isset($modificationDataProvider)){
                ?>

                    <?= GridView::widget([
                            'dataProvider' => $modificationDataProvider,
                            // 'filterModel' => $searchModificationModel,
                            'summary' => false,
                            'tableOptions' => [
                                'class' => 'table table-bordered',
                                'style' => 'margin-bottom: 0;'
                            ],
                            'columns' => [
                                [
                                    'attribute' => 'available',
                                    'format' => 'raw',
                                    'contentOptions' => [
                                        'class' => 'text-center'
                                    ],
                                    'headerOptions' => [
                                        'class' => 'text-center',
                                        'style' => 'min-width: 90px'
                                    ],
                                    'filter' => Html::activeDropDownList(
                                        $searchModel,
                                        'available',
                                        [
                                            0 => Yii::t('back', 'Нет'),
                                            1 => Yii::t('back', 'Да'),
                                        ], [
                                            'class' => 'form-control',
                                            'prompt' => Yii::t('back', 'Все'),
                                        ]
                                    ),
                                    'value' => function ($model) {
                                        return Html::a(
                                            Html::tag('big', 
                                                Html::tag('span', '', [
                                                    'class' => 'glyphicon ' . ( $model->available ? 'glyphicon-ok text-success' : 'glyphicon-remove text-danger')
                                                ])
                                            ), [
                                                '/shop/modification/publish',
                                                'id' => $model->id
                                            ], [
                                                'class' => 'pjax'
                                            ]
                                        );
                                    },
                                ],
                                [
                                    'attribute' => 'synchro',
                                    'format' => 'raw',
                                    'contentOptions' => [
                                        'class' => 'text-center'
                                    ],
                                    'headerOptions' => [
                                        'class' => 'text-center',
                                        'style' => 'min-width: 90px'
                                    ],
                                    'filter' => Html::activeDropDownList(
                                        $searchModificationModel,
                                        'synchro',
                                        [
                                            0 => Yii::t('back', 'Нет'),
                                            1 => Yii::t('back', 'Да'),
                                        ], [
                                            'class' => 'form-control',
                                            'prompt' => Yii::t('back', 'Все'),
                                        ]
                                    ),
                                    'value' => function ($model) {
                                        return Html::a(
                                            Html::tag('big', 
                                                Html::tag('span', '', [
                                                    'class' => 'glyphicon ' . ( $model->synchro ? 'glyphicon-ok text-success' : 'glyphicon-remove text-danger')
                                                ])
                                            ), [
                                                '/shop/modification/synchro',
                                                'id' => $model->id
                                            ], [
                                                'class' => 'pjax'
                                            ]
                                        );
                                    },
                                ],
                                [
                                    'class' => EditableColumn::className(),
                                    'attribute' => 'name',
                                    'url' => ['/shop/modification/edit-field'],
                                    'type' => 'text',
                                    'label' => Yii::t('back', 'Размер'),
                                    'editableOptions' => [
                                        'mode' => 'popup',
                                    ],
                                    'contentOptions' => [
                                        'class' => 'text-center',
                                    ],
                                    'headerOptions' => [
                                        'class' => 'text-center',
                                    ],
                                ],
                                [
                                    'class' => EditableColumn::className(),
                                    'attribute' => 'sku',
                                    // 'format' => 'html',
                                    'url' => ['/shop/modification/edit-field'],
                                    'type' => 'number',
                                    'label' => Yii::t('back', 'Артикул'),
                                    'editableOptions' => [
                                        'mode' => 'popup',
                                    ],
                                    'contentOptions' => [
                                        'class' => 'text-center',
                                    ],
                                    'headerOptions' => [
                                        'class' => 'text-center',
                                    ],
                                ],
                                [
                                    'class' => EditableColumn::className(),
                                    'attribute' => 'store_id',
                                    'url' => ['/shop/modification/edit-field'],
                                    'type' => 'select',
                                    'label' => Yii::t('back', 'Магазин'),
                                    'format' => 'text',
                                    'value' => function ($model) use ($stores) {
                                        return ArrayHelper::getValue(ArrayHelper::map($stores, 'id', 'name'), $model->store_id);
                                    },
                                    'editableOptions' => [
                                        'mode' => 'popup',
                                        'source' => ArrayHelper::map($stores, 'id', 'name'),
                                    ],
                                    'contentOptions' => [
                                        'class' => 'text-center',
                                    ],
                                    'headerOptions' => [
                                        'class' => 'text-center',
                                    ],
                                ],
                                // [
                                    // 'class' => EditableColumn::className(),
                                    // 'attribute' => 'code',
                                    // 'url' => ['/shop/modification/edit-field'],
                                    // 'type' => 'text',
                                    // 'label' => Yii::t('back', 'Vendor Code'),
                                    // 'editableOptions' => [
                                        // 'mode' => 'popup',
                                    // ],
                                    // 'headerOptions' => [
                                        // 'class' => 'text-center',
                                    // ],
                                // ],
                                [
                                    'class' => EditableColumn::className(),
                                    'attribute' => 'price',
                                    'format' => 'integer',
                                    'label' => Yii::t('back', 'Цена'),
                                    'url' => ['/shop/modification/edit-field'],
                                    'type' => 'number',
                                    'editableOptions' => [
                                        'mode' => 'popup',
                                    ],
                                    'headerOptions' => [
                                        'class' => 'text-center',
                                    ],
                                    'contentOptions' => [
                                        'class' => 'text-right text-nowrap',
                                    ],
                                ],
                                
                                [
                                    'class' => EditableColumn::className(),
                                    'attribute' => 'oldPrice',
                                    'format' => 'integer',
                                    'url' => ['/shop/modification/edit-field'],
                                    'type' => 'number',
                                    'label' => Yii::t('back', 'Старая цена'),


                                    'editableOptions' => [
                                        'mode' => 'popup',
                                        'emptytext' => ' ',
                                        'valueIfNull' => '<em>empty</em>',
                                        'displayValue' => function ($attribute) {
                                            return $attribute;
                                            // foreach ($stores as $store) {
                                                // if ($store->id == $model->store_id) {
                                                    // return Yii::$app->formatter->asCurrency($model->oldPrice, $store->country->currency);
                                                // }
                                            // }
                                        },
                                    ],
                                    'headerOptions' => [
                                        'class' => 'text-center',
                                    ],
                                    'contentOptions' => [
                                        'class' => 'text-right text-nowrap',
                                    ],
                                    //'options' => ['style' => 'width: 40px;']
                                ],
                                
                                [
                                    'class' => EditableColumn::className(),
                                    'attribute' => 'amount',
                                    'format' => 'integer',
                                    'label' => Yii::t('back', 'Кол-во'),
                                    'url' => ['/shop/modification/edit-field'],
                                    'type' => 'number',
                                    'editableOptions' => [
                                        'mode' => 'popup',
                                    ],
                                    'headerOptions' => [
                                        'class' => 'text-center',
                                    ],
                                    'contentOptions' => [
                                        'class' => 'text-right text-nowrap',
                                    ],
                                ],
                                
                                [
                                    'label' => Yii::t('back', 'Валюта'),
                                    'format' => 'raw',
                                    'value' => function ($model) use ($stores) {
                                        foreach ($stores as $store) {
                                            if ($store->id == $model->store_id) {
                                                return $store->country->currency;
                                            }
                                        }
                                    },
                                    'headerOptions' => [
                                        'class' => 'text-center',
                                    ],
                                    'contentOptions' => [
                                        'class' => 'text-center',
                                    ],
                                ],
                                

                                
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'controller' => 'modification',
                                    'template' => '{delete}',
                                    'contentOptions' => [
                                        'class' => 'text-center'
                                    ],
                                    'buttons' => [
                                        'delete' => function($url, $model){
                                            return Html::a('', $url, [
                                                'class' => 'glyphicon glyphicon-trash btn btn-danger btn-xs',
                                                'title' => Yii::t('back', 'Удалить'),
                                                'data' => [
                                                    'pjax' => 1,
                                                    'confirm' => Yii::t('back', 'Вы уверены, что хотите удалить этот элемент?'),
                                                    'method' => 'post',
                                                    'pjax' => '#product-modifications'
                                                ]
                                            ]);
                                        },
                                    ]
                                ],
                            ],
                        ]); 
                    ?>
                <?php
                    }
                ?>
                
                <?php
                    Modal::begin([
                        'id' => 'modification-add-modal',
                        'header' => Html::tag('h4', Yii::t('back', 'Добавить опцию'), [
                            'class' => 'text-center',
                        ]),
                        'toggleButton' => [
                            'id' => 'modification-add-btn',
                            'label' => Html::tag('span', '', [
                                'class' => 'glyphicon glyphicon-plus',
                            ]) . '&nbsp;' . Yii::t('back', 'Добавить'),
                            'class' => 'btn btn-success text-right'
                        ]
                    ]);
                ?>
                    <iframe src="<?= Url::toRoute([
                        '/shop/modification/add-popup',
                        'productId' => $model->id
                    ]) ?>" id="modification-add-window"></iframe>
                <?php
                    Modal::end();
                ?>
            <?php Pjax::end() ?>
        </div>   

        <hr>
        
        <label>
            <?= Yii::t('back', 'Фильтры') ?>
        </label>
        <?php
            if ($filterPanel = \dvizh\filter\widgets\Choice::widget([
                'model' => $model
            ])){
                echo $filterPanel;
            } else {
                echo Html::a(Yii::t('back', 'Фильтры'), [
                    '/filter/filter/index'
                ], [
                    'class' => 'btn btn-info'
                ]);
            }
        ?>
        
        <hr>
        
        <label>
            <?= Yii::t('back', 'Связанные продукты') ?>
        </label>
        <div id="related-products" class="related-products-block">
            <?= \dvizh\relations\widgets\Constructor::widget([
                    'model' => $model
                ])
            ?>
        </div>
        
        <hr>
        
        <?php 
            echo SeoForm::widget([
                'model' => $model, 
                'form' => $form,
                'languages' => ArrayHelper::getColumn($languages, 'code'),
            ]);
        ?>
<?php
    }
?>

        <?= $form
                ->field($model, 'sort')
                ->hiddenInput()
                ->label(false)
        ?>

        <br>
        
        <?= Html::input('hidden', 'saveAndExit', 0) ?>

        <br>
        <div class="form-group text-center">
            <?= Html::submitButton(Html::tag('span', '', [
                    'class' => 'glyphicon glyphicon-floppy-saved'
                ]) . '&nbsp;' . Yii::t('back', 'Сохранить'), [
                    'class' => 'btn btn-success btn-lg'
                ]) 
            ?>
            
            <?= Html::submitButton(Html::tag('span', '', [
                    'class' => 'glyphicon glyphicon-floppy-remove'
                ]) . '&nbsp;' . Yii::t('back', 'Сохранить и закрыть'), [
                    'class' => 'btn btn-default btn-lg saveAndExit' . ($model->id ? '' : ' hidden')
                ])
            ?>
        </div>

    <?php ActiveForm::end(); ?>
    
    
    <?php
        // echo $this->render('price/_form', [
            // 'model' => $priceModel,
            // 'productModel' => $model,
        // ])
    ?> 

</div>
