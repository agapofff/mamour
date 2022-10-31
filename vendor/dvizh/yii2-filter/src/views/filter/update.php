<?php
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\DetailView;
use dosamigos\grid\columns\EditableColumn;

/* @var $this yii\web\View */

$this->title = 'Обновление фильтра';
$this->params['breadcrumbs'][] = ['label' => 'Фильтры', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="filter-update">
    <div class="row">
        <div class="col-lg-6">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-10 col-lg-offset-1">
                    <?= $this->render('_form', [
                            'model' => $model,
                        ]) 
                    ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-11 col-lg-offset-0">
                    <h3>
                        <?= Yii::t('back', 'Варианты') ?>
                    </h3>
                    <div class="variants">
                    <?php Pjax::begin(); ?>
                        <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'summary' => false,
                                'tableOptions' => [
                                    'class' => 'table table-striped table-bordered' . ($sort ? ' sortable ' . $sort : ''),
                                    'data-sort' => Url::to(['/filter/filter-variant/sort']),
                                ],
                                'columns' => [
                                    //['class' => 'yii\grid\SerialColumn'],
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
                                            ], [
                                                'class' => 'form-control',
                                                'prompt' => Yii::t('back', 'Все'),
                                            ]
                                        ),
                                        'value' => function ($model) {
                                            return Html::a(
                                                Html::tag('big', 
                                                    Html::tag('span', '', [
                                                        'class' => 'glyphicon ' . ( $model->active ? 'glyphicon-ok text-success' : 'glyphicon-remove text-danger')
                                                    ])
                                                ), [
                                                    '/filter/filter-variant/active',
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
                                        'class' => EditableColumn::className(),
                                        'attribute' => 'value',
                                        // 'filter' => false,
                                        'url' => ['edit-variant'],
                                        // 'type' => 'address',
                                        'editableOptions' => [
                                            'mode' => 'popup',
                                        'id' => 'filterVariant' . $model->id,
                                        ],
                                        'filterInputOptions' => [
                                            'class' => 'form-control text-center',
                                            'placeholder' => 'Поиск...'
                                        ],
                                    ],
                                    [
                                        'class' => EditableColumn::className(),
                                        'attribute' => 'latin_value',
                                        // 'filter' => false,
                                        'url' => ['edit-variant'],
                                        'editableOptions' => [
                                            'mode' => 'popup',
                                        ],
                                        'label' => Yii::t('back', 'Алиас'),
                                    ],
                                    // [
                                        // 'attribute' => 'image',
                                        // 'filter' => false,
                                        // 'content' => function($model) {
                                            // $form = '<div class="modal-dialog"><div class="modal-content"><div class="modal-body">'.$this->render('_form_variant', ['model' => $model]).'</div></div></div>';
                                            
                                            // if($model->hasImage()) {
                                                // $anchor = Html::img($model->getImage()->getUrl('x50'), ['width' => '50']);
                                            // } else {
                                                // $anchor = 'Загрузить картинку';
                                            // }
                                            
                                            // $link = Html::a($anchor, "#variantForm{$model->id}", ['data-toggle' => 'modal', 'data-target' => "#variantForm{$model->id}"]);
                                            
                                            // $window = Html::tag(
                                                // 'div',
                                                // $form,
                                                // [
                                                    // 'class' => 'modal fade',
                                                    // 'id' => "variantForm{$model->id}",
                                                    // 'role' => 'dialog'
                                                // ]
                                            // );
                                        
                                            // return $link.$window;
                                        // }
                                    // ],
                                    ['class' => 'yii\grid\ActionColumn',
                                    'template' => '{delete}',
                                    'buttons' => [
                                        'delete' => function ($url, $model) {
                                                return Html::a('', [
                                                    '/filter/filter-variant/delete', 
                                                    'id' => $model->id
                                                ], [
                                                    'class' => 'glyphicon glyphicon-trash btn btn-danger btn-xs',
                                                    'title' =>'Удалить',
                                                    'data' => [
                                                        'pjax' => 0,
                                                        'confirm' => Yii::t('back', 'Вы уверены, что хотите удалить этот элемент?'),
                                                        'method' => 'post'
                                                    ]
                                                ]);
                                            },
                                    ],
                                    'buttonOptions' => ['class' => 'btn btn-default'], 'options' => ['style' => 'width: 90px;']],
                                ],
                            ]); 
                        ?>
                    <?php Pjax::end(); ?>
                    </div>
                    <hr>
                    <h4>
                        <?= Yii::t('back', 'Добавить вариант') ?>
                    </h4>
                    <?= $this->render('_form_variant', [
                            'model' => $variantModel,
                            'languages' => $languages,
                        ]) 
                    ?>          
                </div>
            </div>
        </div>
    </div>
</div>
