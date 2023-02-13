<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use dvizh\shop\models\Category;
    
if (!$this->title) {
    $this->title = json_decode($category->name)->{Yii::$app->language};
}
?>

<div class="container-fluid mt-3 mt-sm-4 mt-md-5 mt-lg-6 mt-xl-7">    
    <div class="row justify-content-center justify-content-lg-start">
        <div id="category-menu" class="col-md-3 pl-lg-2 pl-xl-6 d-none d-md-block">
            <h1 class="montserrat font-weight-bold text-uppercase headline mb-4 mb-lg-6">
                <?= $this->title ?>
            </h1>
        <?php 
            $menu = ArrayHelper::index(Yii::$app->params['menu'], 'id');
            foreach ($menu as $key => $item) {
                if (strpos($item['url'], 'catalog') === false) {
                    unset($menu[$key]);
                }
            }
            $rootID = 0;
            foreach ($menu as $item) {
                if ($item['current']) {
                    $rootID = $item['parent_id'] == '0' ? $item['id'] : $item['parent_id'];
                }
            }
        ?>
        
    <?php
        if (!$isSearch) {
    ?>
            <div class="ml-n2 mb-2">
                <?php Category::renderMenu(Category::buildTreeArray($menu, $rootID), 'list-unstyled pl-2', null, 'd-inline-block montserrat font-weight-bold text-uppercase fs15px mb-1', 'text-decoration-underline') ?>
            </div>
    <?php
        }
    ?>
    
        <?= \frontend\widgets\FilterPanel\FilterPanel::widget([
                // 'itemId' => $collection['collection']->id,
                'blockCssClass' => 'col-12 mb-2',
                'productsSizes' => $productsSizes,
                'productsPrices' => $productsPrices,
                'products' => $products,
                // 'actionRoute' => explode('?', Url::to())[0],
                'isSearch' => $isSearch,
            ]);
        ?>
        </div>
        <div class="col-sm-11 col-md-9 col-lg-8 col-xl-6 pt-0_5">
    <?php
        if ($isSearch) {
    ?>
            <div class="row align-items-baseline mb-3 mt-n1">
                <div class="col-auto">
                    <h4>
                        <?= Yii::t('front', 'Найти') ?>:
                    </h4>
                </div>
                <div class="col position-relative">
                    <?= Html::beginForm(['/search'], 'get') ?>
                        <?= Html::input('text', 'search', Yii::$app->request->get('search'), [
                                'id' => 'catalog-search-field',
                                'class' => 'form-control form-control-lg pt-1 pb-0_5 px-0 bg-transparent border-top-0 border-left-0 border-right-0 border-dark outline-0 shadow-none montserrat font-weight-bold',
                                'autofocus' => 'autofocus',
                                'placeholder' => Yii::t('front', 'Введите Ваш запрос'),
                            ]) 
                        ?>
                            <button type="submit" class="btn btn-link position-absolute top-0 right-0 text-gray-500 pt-0_5 pr-1 mt-0_5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                </svg>
                            </button>
                    <?= Html::endForm() ?>
                </div>
            </div>
    <?php
        }
    ?>
        
    <?php
        if ($categoryDescription = json_decode($category->text)->{Yii::$app->language}) {
    ?>
            <div class="mb-3 mb-lg-5 lead courier letter-spacing-10 line-height-150">
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
