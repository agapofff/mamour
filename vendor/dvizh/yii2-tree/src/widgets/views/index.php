<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
// echo \yii\helpers\VarDumper::dump($model::buildTree(), 99, true);

function buildCategoriesList($categories, $settings) {
    echo '<ul class="sortable" style="margin:5px 0 0 0" data-sort="' . Url::to(['sort']) . '">';
    foreach ($categories as $category) {
        echo '<li data-key="' . $category['id'] . '" style="padding:5px 0">';
            echo '<span class="fa fa-sort text-info sort-handler pull-left" style="padding: 5px 10px"></span>';
            
            echo Html::button('', [
                'class' => 'glyphicon glyphicon-trash btn btn-danger btn-xs pull-right',
                'data-role' => 'delete-tree',
                'data-id' => $category[$settings['idField']],
                'data-pjax' => 0,
                'style' => 'margin-left: 5px',
            ]);
            
        if ($settings['updateUrl']) {
            echo Html::a('', [
                    $settings['updateUrl'],
                    'id' => $category[$settings['idField']]
                ], [
                    'class' => 'glyphicon glyphicon-pencil btn btn-primary btn-xs pull-right',
                    'data-pjax' => 0,
                    'title' => 'Редактировать',
                    'style' => 'margin-left: 5px;'
                ]);
        }
        
        echo Html::a('', [
                'copy',
                'id' => $category[$settings['idField']]
            ], [
                'class' => 'glyphicon glyphicon-duplicate btn btn-info btn-xs pull-right',
                'data-pjax' => 0,
                'title' => 'Копировать',
                'style' => 'margin-left: 5px;'
            ]);
            
        if ($settings['viewUrl']) {
            if ($settings['viewUrlToSearch']) {
                echo Html::a('', [
                        $settings['viewUrl'],
                        $settings['viewUrlModelName'] => [
                            $settings['viewUrlModelField'] => $category[$settings['idField']]
                        ]
                    ], [
                        'class' => 'glyphicon glyphicon-eye-open btn btn-success btn-xs btn-products pull-right',
                        'title' => 'Товары в этой категории',
                        'style' => 'margin-left: 5px',
                        'data-pjax' => 0,
                    ]);
            } else {
                echo Html::a('', [
                        $settings['viewUrl'],
                        'id' => $category[$settings['idField']]
                    ], [
                        'class' => 'glyphicon glyphicon-eye-open btn btn-success btn-xs btn-products pull-right',
                        'title' => 'Товары в этой категории',
                        'style' => 'margin-left: 5px',
                        'data-pjax' => 0,
                    ]);
            }
        }
            
                echo '<big>
                        <a href="' . Url::to(['/shop/category/update', 'id' => $category['id']]) . '" data-pjax="0">' . json_decode($category['name'])->{Yii::$app->language} . '</a>
                    </big>';
                    
            echo Html::a('', [
                    'active',
                    'id' => $category['id']
                ], [
                    'class' => 'pjax glyphicon glyphicon-' . ($category['active'] ? 'ok text-success' : 'remove text-danger') . ' pull-right',
                    'style' => '
                        margin-left: 5px;
                        padding: 4px 5px;
                    ',
                ]);
            
        if ($category['childs']) {
            buildCategoriesList($category['childs'], $settings);
        }
        echo '</li>';
    }
    echo '</ul>';
}

?>

<?php Pjax::begin(); ?>
    <div class="tree-index">
        <div class="categories-tree"
             data-role="tree"
             data-action-expand="<?= Url::to([$expandUrl]) ?>"
             data-model="<?= $model ?>"
             data-action-delete="<?= Url::to([$deleteUrl]) ?>">
                <?php buildCategoriesList($model::buildTree(), $settings); ?>
        </div>
    </div>
<?php Pjax::end(); ?>
