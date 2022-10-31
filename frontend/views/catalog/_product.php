<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\helpers\HtmlPurifier;
?>

    <div class="card bg-transparent border-0 product">
        <div class="card-body px-sm-1 px-md-2 px-lg-3 px-xl-4 px-xxl-5">
            <a href="<?= Url::to(['/product/' . $product->slug]) ?>">
                <?php
                    $image = $product->getImage();
                    $cachedImage = '/images/cache/Products/Product' . $image->itemId . '/' . $image->urlAlias . '_x1000.jpg';
                ?>
                <img src="<?= file_exists(Yii::getAlias('@frontend') . '/web' . $cachedImage) ? $cachedImage : $image->getUrl('x1000') ?>" class="img-fluid" alt="<?= $image->alt ? $image->alt : $productName ?>" loading="lazy">
            </a>
            <p class="text-center mt-1_5 mb-0_5">
                <?= $productName ?>
            </p>
            <p class="price text-center">
            <?php if ($oldPrice) { ?>
                <del class="text-muted d-none"><?= Yii::$app->formatter->asCurrency($oldPrice, Yii::$app->params['currency']) ?></del>&nbsp;
            <?php } ?>
            <?php if ($price) { ?>
                <?= Yii::$app->formatter->asCurrency($price, Yii::$app->params['currency']) ?>
            <?php } ?>
            </p>
        </div>
    </div>
