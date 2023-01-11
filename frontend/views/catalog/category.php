<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use dvizh\shop\models\Category;
    
if (!$this->title) {
    $this->title = json_decode($category->name)->{Yii::$app->language};
}
?>

<div class="container-xxl mt-2">    
    <div class="row justify-content-center justify-content-lg-start">
        <div id="category-menu" class="col-sm-3 col-md-3 col-lg-2 col-xl-3 d-none d-md-block">
        <?php 
            $menu = ArrayHelper::index(Yii::$app->params['menu'], 'id');
            $rootID = 0;
            foreach ($menu as $item) {
                if ($item['current']) {
                    $rootID = $item['parent_id'] == '0' ? $item['id'] : $item['parent_id'];
                }
            }

            Category::renderMenu(Category::buildTreeArray($menu, $rootID), 'list-unstyled pl-2', null, 'd-inline-block montserrat font-weight-bold text-uppercase fs15px mb-0_5', 'text-decoration-underline') ?>
        </div>
        <div class="col-sm-11 col-md-9 col-lg-8 col-xl-6">
            <h1 class="gotham font-weight-bold text-uppercase headline mb-3 mb-lg-5">
                <?= json_decode($category->name)->{Yii::$app->language} ?>
            </h1>
    <?php
        if ($categoryDescription = json_decode($category->text)->{Yii::$app->language}) {
    ?>
            <div class="mb-3 mb-lg-5 lead">
                <?= json_decode($category->text)->{Yii::$app->language} ?></p>
            </div>
    <?php
        }
    ?>
    <?php
        if ($products) {
    ?>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 justify-content-center">
        <?php
            foreach ($products as $product) {
        ?>
                <div class="col mb-3">
                    <?= $this->render('@frontend/views/catalog/_product', [
                            'product' => $product['model'],
                            'productName' => $product['name'],
                            'oldPrice' => $product['oldPrice'],
                            'price' => $product['price'],
                            'sizes' => $product['sizes'],
                            'wishlist' => $product['wishlist'],
                        ])
                    ?> 
                </div>
        <?php
            }
        ?>
            </div>
    <?php
        }
    ?>
        </div>
    </div>
</div>
