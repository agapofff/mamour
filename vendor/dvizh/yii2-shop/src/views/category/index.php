<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use dvizh\shop\models\Category;
use kartik\export\ExportMenu;
use kartik\alert\AlertBlock;

use yii\web\View;

$this->title = 'Категории';
$this->params['breadcrumbs'][] = ['label' => 'Магазин', 'url' => ['/shop/default/index']];
$this->params['breadcrumbs'][] = $this->title;

\dvizh\shop\assets\BackendAsset::register($this);

?>

<?= AlertBlock::widget([
        'type' => 'growl',
        'useSessionFlash' => true,
        'delay' => 1,
    ]);
?>

<div class="category-index">

    <?= Html::a(Html::tag('span', '', [
        'class' => 'glyphicon glyphicon-plus'
    ]) . '&nbsp;' . Yii::t('back', 'Создать'), [
        'create'
    ], [
        'class' => 'btn btn-success'
    ]) ?>
    
    <!--
    <div class="row">
        <?php if(Yii::$app->request->get('view') == 'list') { ?>
            <div class="col-md-1">
                <?= Html::tag('button', 'Удалить', [
                    'class' => 'btn btn-success dvizh-mass-delete',
                    'disabled' => 'disabled',
                    'data' => [
                        'model' => $dataProvider->query->modelClass,
                    ],
                ]) ?>
            </div>
        <?php } ?>
        <div class="col-md-12">
            <?= Html::a(Yii::t('back', 'Создать'), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-md-4">
            <?php
            $gridColumns = [
                'id',
                'name',
            ];
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns
            ]);
            ?>
        </div>
    </div>
    -->
    
    <!--
    <br style="clear: both;"></div>

    <ul class="nav nav-pills">
        <li role="presentation" <?php if(Yii::$app->request->get('view') == 'tree' | Yii::$app->request->get('view') == '') echo ' class="active"'; ?>><a href="<?=Url::toRoute(['category/index', 'view' => 'tree']);?>">Деревом</a></li>
        <li role="presentation" <?php if(Yii::$app->request->get('view') == 'list') echo ' class="active"'; ?>><a href="<?=Url::toRoute(['category/index', 'view' => 'list']);?>">Списком</a></li>
    </ul>
    -->
    
    <br>
    <br>
    
    <?php
    if(Yii::$app->request->get('view') == 'list') {
        $categories = \kartik\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => '\kartik\grid\CheckboxColumn'],
                ['class' => 'yii\grid\SerialColumn'],
                ['attribute' => 'id', 'filter' => false, 'options' => ['style' => 'width: 55px;']],
                'name',
                /*
                [
                    'attribute' => 'image',
                    'format' => 'image',
                    'filter' => false,
                    'content' => function ($image) {
                        if($image = $image->getImage()->getUrl('50x50')) {
                            return "<img src=\"{$image}\" class=\"thumb\" />";
                        }
                    }
                ],
                */
                [
                    'attribute' => 'parent_id',
                    'filter' => Html::activeDropDownList(
                        $searchModel,
                        'parent_id',
                        Category::buildTextTree(),
                        ['class' => 'form-control', 'prompt' => 'Категория']
                    ),
                    'value' => 'parent.name'
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'buttons' => [
                        'update' => function($url, $model){
                            return Html::a('', $url, [
                                'class' => 'glyphicon glyphicon-pencil btn btn-primary btn-xs',
                                'title' => Yii::t('back', 'Изменить'),
                                'data-pjax' => 0,
                            ]);
                        },
                        'delete' => function($url, $model){
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
                ]
            ],
        ]);
    } else {
        $categories = \dvizh\tree\widgets\Tree::widget([
            'model' => '\dvizh\shop\models\Category',
        ]);
    }
?>
    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            <?= $categories ?>
        </div>
    </div>

</div>

<?php // раскрыть родительские категории при загрузке страницы
    $this->registerJs(
        "$('.expand-tree').click();",
        View::POS_READY,
        'expand-tree'
    );
?>

<?php // клик по подкатегории открывает список товаров в этой подкатегории
    // $this->registerJs(
        // "$(document).on('click', '.view-products', function(){
            // location.href = $(this).parent().find('.btn-products').attr('href');
        // });",
        // View::POS_READY,
        // 'view-products'
    // );
?>
