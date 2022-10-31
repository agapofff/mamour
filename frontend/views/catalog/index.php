<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\web\View;
use frontend\widgets\FilterPanel\FilterPanel;

$this->title = Yii::$app->params['title'] ?: $title;
$h1 = Yii::$app->params['h1'] ?: $this->title;

// \yii\web\YiiAsset::register($this);
?>

<div class="container-fluid mb-1 mb-md-2 px-lg-2 px-xl-3 px-xxl-5">
    <div class="row">
        <div class="col-12">
            <h1 class="ttfirsneue text-uppercase display-2 position-relative d-inline-block mb-0 red_dot">
                <?= Yii::t('front', 'Каталог') ?>
            </h1>
        </div>
    </div>
</div>

<div class="container-fluid px-lg-2 px-xl-3 px-xxl-5">
<?php
    foreach ($collections as $collection) {
?>
        <div class="row mb-1 mb-md-3">
            <div class="col-auto">
                <h2 class="display-3 ttfirsneue text-uppercase font-weight-light mb-0">
                    <?= Html::a(json_decode($collection['collection']->text)->{Yii::$app->language}, [
                            '/catalog/' . $collection['collection']->slug
                        ], [
                            'class' => 'text-decoration-none'
                        ])
                    ?>
                </h2>
            </div>
        </div>
        
        <?= $collectionSlug ?>        
        
    <?php
        if ($collection['subCategories']) {
    ?>
            <div class="row justify-content-between flex-nowrap overflow-x-auto overflow-y-hidden product-types pb-0_5">
                <div class="col-auto">
                    <?= Html::a(Yii::t('front', 'Все'), [
                            '/catalog' . ($collectionSlug ? '/' . $collectionSlug : '')
                        ], [
                            'class' => 'ttfirsneue text-uppercase text-decoration-none pb-1 ' . (($collectionSlug && $collection['collection']->slug == $collectionSlug) || (!$collectionSlug && !$categorySlug) ? 'text-dark' : ' text-gray-500')
                        ])
                    ?>
                </div>
            <?php
                foreach ($collection['subCategories'] as $subCategory) {
            ?>
                <div class="col-auto">
                    <?= Html::a(json_decode($subCategory->name)->{Yii::$app->language}, [
                            '/catalog/' . ($collectionSlug ? $collectionSlug . '/' : '') . $subCategory->slug
                        ], [
                            'class' => 'ttfirsneue text-uppercase text-decoration-none py-1 ' . ($categorySlug && $subCategory->slug == $categorySlug ? 'text-dark' : 'text-gray-500'),
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
    
        <hr class="d-none d-md-block mt-0">
        <?php Pjax::begin(); ?>

            <?= FilterPanel::widget([
                    'itemId' => $collection['collection']->id,
                    'blockCssClass' => 'col-12 col-md-6 col-lg-4 col-xl-3 mb-1',
                    'productsSizes' => $collection['productsSizes'],
                    'productsPrices' => $collection['productsPrices'],
                    'actionRoute' => explode('?', Url::to())[0],
                ]);
            ?>
            
            <?php
                if ($collection['products']) {
            ?>
                    
                    <div class="row list-products justify-content-center mt-1 mt-md-3">
                    <?php
                        foreach ($collection['products'] as $product) {
                    ?>
                            <div class="col-12 col-md-6">
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
            
            <?php
                $this->registerJs("
                    $('.select2-search__field').addClass('form-control');
                ", View::POS_READY);
            ?>

        <?php Pjax::end(); ?>
        
<?php
    }
?>

</div>

