<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\helpers\HtmlPurifier;
?>

    <div class="card bg-transparent border-0 product">
        <div class="card-body position-relative p-0">
            <a href="<?= Url::to(['/product/' . $product->slug]) ?>">
                <?php
                    $image = $product->getImage();
                    $cachedImage = '/images/cache/Product/Product' . $image->itemId . '/' . $image->urlAlias . '_x500.' . $image->extension;
                ?>
                <img data-src="<?= file_exists(Yii::getAlias('@frontend') . '/web' . $cachedImage) ? $cachedImage : $image->getUrl('x500') ?>" class="img-fluid lazyload" alt="<?= $image->alt ? $image->alt : $productName ?>" loading="lazy">
            </a>
            <p class="text-center montserrat font-weight-bold mt-1_5 mb-0_5">
                <?= $productName ?>
            </p>
            <p class="price text-center montserrat font-weight-light">
            <?php if ($oldPrice) { ?>
                <del class="text-muted d-none"><?= Yii::$app->formatter->asCurrency($oldPrice, Yii::$app->params['currency']) ?></del>&nbsp;
            <?php } ?>
            <?php if ($price) { ?>
                <?= Yii::$app->formatter->asCurrency($price, Yii::$app->params['currency']) ?>
            <?php } ?>
            </p>
        </div>
    </div>
