<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\helpers\HtmlPurifier;
    
    $size = Yii::$app->params['productImageSizes']['M'];
?>

<div class="card bg-transparent border-0 product mb-1 mb-lg-1_5 category-product">
    <div class="card-body position-relative p-0 text-center">
        <a href="<?= Url::to(['/product/' . $product->slug]) ?>">
            <?php
                $image = $product->getImage();
                $cachedImage = '/images/cache/Product/Product' . $image->itemId . '/' . $image->urlAlias . '_' . $size . '.' . $image->extension;
            ?>
            <img data-src="<?= file_exists(Yii::getAlias('@frontend') . '/web' . $cachedImage) ? $cachedImage : $image->getUrl($size) ?>" class="img-fluid lazyload" alt="<?= $image->alt ? $image->alt : $productName ?>" loading="lazy">
        </a>
        <p class="text-center montserrat font-weight-light mt-1_5 mb-1">
            <?= $productName ?>
        </p>
        <?= $this->render('@frontend/views/wishlist/product', [
                'product_id' => $product->id,
                'action' => $wishlist
            ])
        ?>
    </div>
    <div class="card-footer bg-transparent border-0 p-0">
        <p class="price text-center montserrat font-weight-bold">
        <?php if ($oldPrice) { ?>
            <del class="text-muted d-none"><?= Yii::$app->formatter->asCurrency($oldPrice, Yii::$app->params['currency']) ?></del>&nbsp;
        <?php } ?>
        <?php if ($price) { ?>
            <?= Yii::$app->formatter->asCurrency($price, Yii::$app->params['currency']) ?>
        <?php } ?>
        </p>
        
        <div class="text-center small">
            <a href="#" class="courier font-weight-light text-warning text-decoration-underline small">
                <?= Yii::t('front', 'Просмотр') ?>
            </a>
        </div>
    </div>
</div>
