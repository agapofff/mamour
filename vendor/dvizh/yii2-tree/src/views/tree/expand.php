<?php
use yii\helpers\Html;

$idField = $settings['idField'];
?>
<li class="main">
    <div class="row">
        <div class="col-lg-6 col-xs-6 <?php if ($category['childs']){ ?>expand-tree" data-role="expand-tree<?php } else { ?>view-products<?php } ?>" data-id="<?= $category[$idField] ?>">
            
            <p>
                <?= $settings['showId'] ? $category[$idField] . '.' : null ?>
                <big>
                    <?= json_decode($category[$settings['nameField']])->{Yii::$app->language} ?>
                </big>
                <span class="glyphicon <?= $category['childs'] ? 'glyphicon-chevron-down' : null ?>"></span>
            </p>
            
        </div>
        <div class="col-lg-6 col-xs-6 dvizh-tree-right-col">
            
            <div class="buttons">
            
                <?php if ($settings['viewUrl']) { ?>
                
                    <?php if ($settings['viewUrlToSearch']) { ?>
                    
                        <?= Html::a('', [
                                $settings['viewUrl'],
                                $settings['viewUrlModelName'] => [
                                    $settings['viewUrlModelField'] => $category[$idField]
                                ]
                            ], [
                                'class' => 'glyphicon glyphicon-eye-open btn btn-info btn-xs btn-products',
                                'title' => 'Смотреть'
                            ]);
                        ?>
                        
                    <?php } else { ?>
                    
                        <?= Html::a('', [
                                $settings['viewUrl'],
                                'id' => $category[$idField]
                            ], [
                                'class' => 'glyphicon glyphicon-eye-open btn btn-info btn-xs btn-products',
                                'title' => 'Смотреть'
                            ]);
                        ?>
                        
                    <?php } ?>
                    
                <?php } ?>
                
                <?php if ($settings['updateUrl']) { ?>
                
                    <?= Html::a('', [
                            $settings['updateUrl'],
                            'id' => $category[$idField]
                        ], [
                            'class' => 'glyphicon glyphicon-pencil btn btn-primary btn-xs',
                            'title' => 'Редактировать'
                        ]);
                    ?>
                    
                <?php } ?>
                
                <?= Html::tag('button', '', [
                        'class' => 'glyphicon glyphicon-trash btn btn-danger btn-xs',
                        'data-role' => 'delete-tree',
                        'data-id' => $category[$idField]
                    ]);
                ?>
                
            </div>
        </div>
    </div>
</li>