<?php

use yii\helpers\Url;
use yii\helpers\Html;
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

if (!$this->title) {
    $this->title = $productName . ' - ' . Yii::t('front', 'Купить в интернет-магазине') . ' ' . Yii::$app->name;
}
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
                            <div class="position-relative mb-0_5 cursor-pointer" onclick="owlGoTo('#product-gallery', <?= $key ?>)">
                                <img src="/images/product_back_small.jpg" class="img-fluid">
                                <div class="position-absolute top-0 left-0 w-100 h-100" style="background: url('<?= $imageSrc ?>') center center / contain no-repeat"></div>
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
                <div class="col-1">
                    <?= $this->render('@frontend/views/wishlist/product', [
                            'product_id' => $product->id,
                            'action' => $wishlist,
                        ])
                    ?>
                </div>
                <div class="col-11">
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
                if ($price && $product->available) {
            ?>
                    <div class="product-price mb-2 mt-1_5" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                        <meta itemprop="price" content="<?= $price ?>">
                        <meta itemprop="priceCurrency" content="<?= Yii::$app->params['currency'] ?>">
                        <?= ShowPrice::widget([
                                'htmlTag' => 'p',
                                'cssClass' => 'h1 font-weight-light montserrat',
                                'cssClassOld' => 'h4 font-weight-light montserrat text-muted',
                                'model' => $product,
                                'price' => $price,
                                'priceOld' => $priceOld,
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
                    <p class="mb-2">
                        <button type="button" data-toggle="lightbox" data-title="<?= Yii::t('front', 'Таблица размеров') ?>" data-remote="<?= Url::to(['/sizes'], true) ?> #page-content" data-modal-dialog-class="modal-dialog-scrollable modal-xl" class="btn btn-link p-0 font-weight-light text-warning text-decoration-underline">
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
                    <div class="product-text font-weight-light mt-2 mb-2">
                        <?= $productText ?>
                    </div>
            <?php
                }
            ?>
            
                    <div class="mt-2 mb-2">
                        <div class="row">
                            
                        </div>
                    </div>
            
            <?php
                if ($price && $product->available) {
            ?>
                    <div class="product-buy mb-2" data-id="<?= $product->id ?>">
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
    
<?php
    if ($relations = $product->getRelations()) {
?>
    <div class="row mt-12">
        <div class="col-12">
            <hr>
            <h4 class="h1 ttfirsneue text-uppercase mb-6">
                <?= Yii::t('front', 'Сопутствующие товары') ?>
            </h4>
        </div>
        <div class="owl-carousel owl-theme" data-items="2-2-3-3-4-4" data-nav="true" data-dots="true" data-margin="0">
    <?php
        foreach ($relations->all() as $related) {
    ?>
        <div class="col-12">
            <div class="card bg-transparent border-0 product">
                <div class="card-body px-0">
                    <a href="<?= Url::to(['/product/'.$related->slug]) ?>">
                        <?php
                            $image = $related->getImage();
                            $cachedImage = '/images/cache/Products/Product' . $image->itemId . '/' . $image->urlAlias . '_x1000.jpg';
                        ?>
                        <img src="<?= file_exists(Yii::getAlias('@frontend') . '/web' . $cachedImage) ? $cachedImage : $image->getUrl('x1000') ?>" class="img-fluid" alt="<?= $image->alt ? $image->alt : $product_name ?>" loading="lazy">
                    </a>
                    <p class="text-center mt-1_5 mb-0_5">
                        <?= $product_name ?>
                    </p>
                    <p class="price text-center">
                    <?php if (isset($prices_old[$related->id]) && (int)$prices_old[$related->id] > 0) { ?>
                        <del class="text-muted"><?= Yii::$app->formatter->asCurrency((int)$prices_old[$related->id], Yii::$app->params['currency']) ?></del>&nbsp;
                    <?php } ?>
                    <?php if (isset($prices[$related->id]) && (int)$prices[$related->id] > 0) { ?>
                        <?= Yii::$app->formatter->asCurrency((int)$prices[$related->id], Yii::$app->params['currency']) ?>
                    <?php } ?>
                    </p>
                </div>
            </div>
        </div>
    <?php
        }
    ?>
        </div>
    </div>
<?php
    }
?>

</div>


<?php
    if ($sizes) {
?>
        <div id="sizes" class="modal p-0 fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog max-vw-50 border-0 mx-auto my-0" role="document">
                <div class="modal-content m-0 border-0 vh-100 vw-50">
                    <div class="modal-header align-items-center flex-nowrap py-md-2 px-md-1 px-lg-2 px-xl-3">
                        <span class="ttfirsneue h5 m-0 font-weight-light">
                            <?= Yii::t('front', 'Размерная сетка') ?> <small class="text-muted font-weight-light text-nowrap">(<?= Yii::t('front', 'в сантиметрах') ?>)</small>
                        </span>
                        <button type="button" class="close p-0 float-none" data-dismiss="modal">
                            <svg width='53' height='53' viewBox='0 0 53 53' fill='none' xmlns='http://www.w3.org/2000/svg'><line x1='13.7891' y1='12.3744' x2='39.9521' y2='38.5373' stroke='black' stroke-width='2'></line><line x1='12.3749' y1='38.5379' x2='38.5379' y2='12.3749' stroke='black' stroke-width='2'></line></svg>
                        </button>
                    </div>
                    <div class="modal-body h-100 overflow-y-scroll py-0 px-md-1 px-lg-2 px-xl-3 hide-h1">
                        <div id="size-grid" class="table-responsive">
                            <?= str_replace('<table>', '<table class="table product-sizes">', $sizes) ?>
                        </div>
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

