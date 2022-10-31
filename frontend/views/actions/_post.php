<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\helpers\ImageHelper;

$image = $action->getImage();
$isDark = ImageHelper::isDark(ImageHelper::getMainColor($image->getPath()));
$cachedImage = '/images/cache/Actions/Actions' . $image->itemId . '/' . $image->urlAlias . '_800x400.' . $image->getExtension();
$name = json_decode($action->name)->{Yii::$app->language};
?>

<a href="<?= Url::to(['/actions/' . $action->slug]) ?>" class="card rounded-0 border-0 mb-2 text-dark overflow-hidden">
    <img src="<?= file_exists(Yii::getAlias('@frontend') . '/web' . $cachedImage) ? $cachedImage : $image->getUrl('800x400') ?>" alt="<?= $image->alt ?: $name ?>" class="card-img rounded-0" loading="lazy">
    <div class="card-img-overlay p-1_5">
        <div class="row">
            <div class="col-7 col-sm-7 col-md-8 col-lg-6">
                <p class="blog-post-date mb-1 opacity-50 small">
                    <?= Yii::$app->formatter->asDatetime($action->published, 'php:d.m.Y') ?>
                </p>
                <h5 class="font-weight-500 mb-1 text-black">
                    <?= Yii::$app->params['actionsTypes'][$action->type] ?>
                </h5>
                <p class="mb-0_5 font-weight-bold text-uppercase text-<?= $isDark ? 'white' : 'orange' ?>">
                    <?= $name ?>
                </p>
                <p class="mb-0 font-weight-bold text-black d-none d-sm-block d-md-none d-lg-block">
                    <?= json_decode($action->description)->{Yii::$app->language} ?>
                </p>
            </div>
        </div>
    </div>
</a>
