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

<div class="container-xxl mt-3">    
    <div class="row justify-content-center justify-content-lg-start">
        <div id="category-menu" class="col-sm-3 col-md-3 col-lg-2 col-xl-3 d-none d-md-block">
        <?php 
            $menu = ArrayHelper::index(Yii::$app->params['menu'], 'id');
            $rootID = 0;
            foreach ($menu as $item) {
                if ($item['current']) {
                    $rootID = $item['parent_id'];
                    // foreach ($menu as $parentItem) {
                        // if ($parentItem['id'] == $item['parent_id']) {
                            // $rootID = $parentItem['parent_id'];
                            // break;
                        // }
                    // }
                    // if ($parents = Category::getAllParents($menu, (int)$item['id'])) {
                        // foreach ($parents as $parent) {
                            // if (!$parent['parent_id']) {
                                // $rootID = $parent['id'];
                            // }
                        // }
                    // }
                }
            }

            Category::renderMenu(Category::buildTreeArray($menu, $rootID), 'list-unstyled pl-2', null, 'd-inline-block montserrat font-weight-bold text-uppercase mb-0_5', 'text-decoration-underline') ?>
        </div>
        <div class="col-sm-11 col-md-9 col-lg-8 col-xl-6">
            <div class="row justify-content-center">
                <div class="col-xxl-10">
                    <h1 class="gotham font-weight-bold text-uppercase headline mb-3 mb-md-5">
                        <?= json_decode($category->name)->{Yii::$app->language} ?>
                    </h1>
                </div>
            </div>
    <?php
        if ($products) {
    ?>
            <div class="row justify-content-center">
        <?php
            foreach ($products as $product) {
        ?>
                <div class="col-sm-6 col-md-12 col-lg-6 col-xl-4">
                    <?= $this->render('@frontend/views/catalog/_product', [
                            'product' => $product['model'],
                            'productName' => $product['name'],
                            'oldPrice' => $product['oldPrice'],
                            'price' => $product['price'],
                            'sizes' => $product['sizes'],
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
