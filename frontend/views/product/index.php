<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use dvizh\shop\widgets\ShowPrice;
use dvizh\cart\widgets\BuyButton;
use dvizh\cart\widgets\ChangeCount;
use dvizh\cart\widgets\ChangeOptions;
use yii\web\View;
use yii\widgets\Pjax;
use PELock\ImgOpt\ImgOpt;

$images = $product->getImages();

// if ($images) {
    // $image = $images[0];
    // $cachedImage = '/images/cache/Product/Product' . $image->itemId . '/' . $image->urlAlias . '_400x600.' . $image->extension;
    // $mainImage = Url::to(file_exists(Yii::getAlias('@frontend') . '/web' . $cachedImage) ? $cachedImage : $image->getUrl('400x600'), true);
    // $this->registerMetaTag([
        // 'property' => 'og:image',
        // 'content' => $mainImage
    // ]);
// }

$productName = json_decode($product->name)->{Yii::$app->language};
$productDescription = json_decode($product->short_text)->{Yii::$app->language};
$productText = json_decode($product->text)->{Yii::$app->language};
$productCompound = json_decode($product->compound)->{Yii::$app->language};
$productHoToUse = json_decode($product->howtouse)->{Yii::$app->language};

// if (!$this->title) {
    // $this->title = $productName . ' - ' . Yii::t('front', 'Купить в интернет-магазине') . ' ' . Yii::$app->name;
// }

// if (!Yii::$app->params['description'] && !$product->seo->description && $productDescription) {
    // $this->registerMetaTag([
        // 'name' => 'description',
        // 'content' => $productDescription
    // ]);
// }

?>

<div class="product-content container-xl" itemscope itemtype="http://schema.org/Product">
    <div class="row">
        <div class="col-md-6 mt-1">
            <div class="row overflow-hidden">
                <div class="col-8 pr-0_5">
                    <div id="product-gallery" class="owl-carousel owl-theme owl-fade" data-dots="true">
                <?php
                    foreach ($images as $key => $image) {
                        $cachedImage = '/images/cache/Product/Product' . $image->itemId . '/' . $image->urlAlias . '_' . Yii::$app->params['productImageSizes']['M'] . '.' . $image->extension;
                        $imageSrc = Url::to(file_exists(Yii::getAlias('@frontend') . '/web' . $cachedImage) ? $cachedImage : $image->getUrl(Yii::$app->params['productImageSizes']['M']), true);
                ?>
                        <div class="product-bg">
                            <?= ImgOpt::widget([
                                    'src' => $imageSrc, 
                                    'alt' => $image->alt ?: $productName,
                                    'loading' => 'lazy',
                                ])
                            ?>
                        </div>
                <?php
                    }
                ?>
                    </div>
                </div>
                <div class="col-4 pl-0_5">
                    <div class="position-relative h-100">
                <?php
                    /*
                    foreach ($images as $key => $image) {
                        if ($key < 3) {
                            $cachedImage = '/images/cache/Product/Product' . $image->itemId . '/' . $image->urlAlias . '_200x295.' . $image->extension;
                            $imageSrc = Url::to(file_exists(Yii::getAlias('@frontend') . '/web' . $cachedImage) ? $cachedImage : $image->getUrl('200x295'), true);
                ?>
                        <div class="col-12 overflow-hidden align-self-<?= $key ? ($key == 1 ? 'center' : 'end') : 'start' ?>">
                            <img data-src="<?= $imageSrc ?>" class="img-fluid lazyload" alt="<?= $image->alt ?: $productName ?>" onclick="owlGoTo('#product-gallery', <?= $key ?>)">
                        </div>
                <?php
                        }
                    }
                    */
                ?>
                        <div class="product-thumbnails position-absolute top-0 left-0 bottom-0 roght-0">
                    <?php
                        foreach ($images as $key => $image) {
                            $cachedImage = '/images/cache/Product/Product' . $image->itemId . '/' . $image->urlAlias . '_' . Yii::$app->params['productImageSizes']['S'] . '.' . $image->extension;
                            $imageSrc = Url::to(file_exists(Yii::getAlias('@frontend') . '/web' . $cachedImage) ? $cachedImage : $image->getUrl(Yii::$app->params['productImageSizes']['S']), true);
                    ?>
                            <div class="position-relative mb-0_5 overflow-hidden cursor-pointer" onclick="owlGoTo('#product-gallery', <?= $key ?>)">
                                <img src="/images/product_back_small.jpg" class="img-fluid">
                                <img data-src="<?= $imageSrc ?>" class="product-thumbnail lazyload cursor-pointer" alt="<?= $image->alt ?: $productName ?>" >
                            </div>
                    <?php
                        }
                    ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <div class="col-md-6">
            <div class="row">
                <div class="col-sm-1">
                    <?= $this->render('@frontend/views/wishlist/product', [
                            'product_id' => $product->id,
                            'action' => in_array($product->id, $wishlist) ? 'remove' : 'add',
                        ])
                    ?>
                </div>
                <div class="col-sm-11">
                    <h1 class="montserrat font-weight-bold text-uppercase mt-0_5" itemprop="name">
                        <?= $productName ?>
                    </h1>
            <?php
                if ($product->sku) {
            ?>
                    <p class="montserrat font-weight-light text-muted fs15">
                        <?= $product->sku ?>
                    </p>
            <?php
                }
            ?>
            
            <?php
                if ($prices[$product->id] && $product->available) {
            ?>
                    <div class="product-price mb-1_5 mt-1" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                        <meta itemprop="price" content="<?= $price ?>">
                        <meta itemprop="priceCurrency" content="<?= Yii::$app->params['currency'] ?>">
                        <?= ShowPrice::widget([
                                'htmlTag' => 'p',
                                'cssClass' => 'h1 font-weight-light montserrat',
                                'cssClassOld' => 'h4 font-weight-light montserrat text-muted',
                                'model' => $product,
                                'price' => $prices[$product->id],
                                'priceOld' => $oldPrices[$product->id],
                            ])
                        ?>
                    </div>
                    
                    <div class="price-options" data-id="<?= $product->id ?>">
                        <?= ChangeOptions::widget([
                                'model' => $product,
                                'type' => 'radio',
                                'disabledItems' => $disabledItems,
                                // 'cssClass' => 'd-none'
                            ]);
                        ?>
                    </div>
                    <p class="mb-1_5">
                        <button type="button" data-toggle="lightbox" data-title="<?= Yii::t('front', 'Таблица размеров') ?>" data-remote="<?= Url::to(['/sizes'], true) ?> #page-content" data-modal-dialog-class="modal-dialog-centered modal-dialog-scrollable" data-max-height="450" class="btn btn-link p-0 font-weight-light text-warning text-decoration-underline">
                            <small>
                                <?= Yii::t('front', 'Показать таблицу размеров') ?>
                            </small>
                        </button>
                    </p>
            <?php
                }
            ?>
            
            <?php
                if ($productText) {
            ?>
                    <div class="product-text font-weight-light mb-1">
                        <?= $productText ?>
                    </div>
            <?php
                }
            ?>
            
                    <div class="mb-1_5">
                        <div class="row">
                    <?php
                        if ($productCompound) {
                    ?>
                            <div class="col-auto">
                                <button type="button" class="btn btn-link px-0 text-decoration-underline" data-toggle="popover" title="<?= Yii::t('front', 'Состав') ?>" data-content="<?= Html::encode($productCompound) ?>">
                                    <?= Yii::t('front', 'Состав') ?>
                                </button>
                            </div>
                    <?php
                        }
                    ?>
                    
                    <?php
                        if ($productHoToUse) {
                    ?>
                            <div class="col-auto">
                                <button type="button" class="btn btn-link px-0 text-decoration-underline" data-toggle="popover" title="<?= Yii::t('front', 'Уход') ?>" data-content="<?= str_replace('"', "'", Html::encode($productHoToUse)) ?>">
                                    <?= Yii::t('front', 'Уход') ?>
                                </button>
                            </div>
                    <?php
                        }
                    ?>
                            <div class="col-auto">
                                <a href="<?= Url::to(['/delivery-and-return']) ?>" target="_blank" class="btn btn-link px-0 text-decoration-underline">
                                    <?= Yii::t('front', 'Доставка и возврат') ?>
                                </a>
                            </div>
                        </div>
                    </div>
            
            <?php
                if ($prices[$product->id] && $product->available) {
            ?>
                    <div class="product-buy mb-1_5" data-id="<?= $product->id ?>">
                        <?= BuyButton::widget([
                                'model' => $product,
                                'htmlTag' => 'button',
                                'cssClass' => 'btn btn-primary btn-sm-block montserrat px-3 py-0_5 py-sm-1',
                            ]);
                        ?>
                    </div>
            <?php
                }
            ?>
                </div>
            </div>
        </div>
    </div>
</div>
    
<?php
    if ($relations = $product->getRelations()) {
        $this->registerCss("
            #product-relations .owl-stage {
                display: flex;
            }
        ");
?>
        <div class="container-xl">
            <div class="row mt-7 mt-md-10">
                <div class="col-12">
                    <h4 class="h1 montserrat text-uppercase text-center mb-3">
                        <?= Yii::t('front', 'Вам также понравится') ?>
                    </h4>
                </div>
                <div class="col-12">
                    <div id="product-relations" class="owl-carousel owl-theme owlArrows" data-items="2-2-3-4-5-5" data-nav="true" data-dots="true" data-margin="20" data-loop="true" data-autoheight="true">
                <?php
                    foreach ($relations->all() as $related) {
                ?>
                        <?= $this->render('@frontend/views/catalog/_product', [
                                'product' => $related,
                                'productName' => json_decode($related->name)->{Yii::$app->language},
                                'oldPrice' => $oldPrices[$related->id],
                                'price' => $prices[$related->id],
                                'wishlist' => in_array($related->id, $wishlist) ? 'remove' : 'add',
                            ])
                        ?> 
                <?php
                    }
                ?>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
?>

<?php
    $this->registerJs("
        outOfStock = function () {
            toastr.error('" . Yii::t('front', 'Нет в наличии') . "');
        }
    ");
?>

<?php
    $this->registerJS("
            var id,
                options = {};
                
            $('.dvizh-option').each(function () {
                var option = $(this).find('.dvizh-option-values-before:not(:disabled)').eq(0),
                    optionId = $(option).data('filter-id'),
                    optionVal = $(option).val();
                options[optionId] = optionVal;
            });

// console.log(options);
            $('.dvizh-cart-buy-button').data('options', options);
            $('.dvizh-cart-buy-button').attr('data-options', options);
            
            $(this).find('.dvizh-option-values-before:not(:disabled)').eq(0).trigger('click');
        ",
        View::POS_READY,
        'set-product-options-on-load'
    );
?>

