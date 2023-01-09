<?php

use yii\helpers\Url;
use yii\helpers\Html;
use PELock\ImgOpt\ImgOpt;

$this->title = Yii::$app->name;

?>

<div id="mainpage-slider" class="owl-carousel owl-theme" data-nav="true" data-dots="true" data-loop="true" data-autoplay="true" data-animatein="fadeIn" data-animateout="fadeOut" data-hoverstop="true">
<?php
foreach ($slides as $slide) {
?>
    <div class="position-relative">
<?php
    if ($slide->link) {
        $image = $slide->getImage();
        $cachedImage = '/images/cache/Slides/Slide' . $image->itemId . '/' . $image->urlAlias . '.' . $image->extension;
        $imageUrl = file_exists(Yii::getAlias('@frontend') . '/web' . $cachedImage) ? $cachedImage : $image->getUrl();
?>
        <a href="<?= Url::to([$slide->link]) ?>">
            <?= ImgOpt::widget([
                    'src' => $imageUrl, 
                    'alt' => $image->alt ?: $this->title,
                    'loading' => 'lazy',
                ])
            ?>
        </a>
<?php
    } else {
?>
    <?= ImgOpt::widget([
            'src' => $imageUrl, 
            'alt' => $image->alt ?: $this->title,
            'loading' => 'lazy',
        ])
    ?>
<?php
    }
?>
<?php
    if ($slide->show_button){
?>
        <a href="<?= Url::to([$slide->link]) ?>" class="d-md-none btn btn btn-primary position-absolute right-0 bottom-0 mr-1 mb-1 gotham font-weight-light px-2 py-0_5">
            <?= json_decode($slide->button_text)->{Yii::$app->language} ?>
        </a>
        <a href="<?= Url::to([$slide->link]) ?>" class="d-none d-md-inline-block d-lg-none btn btn-primary position-absolute right-0 bottom-0 mr-1 mb-1 gotham font-weight-light px-3 py-1">
            <?= json_decode($slide->button_text)->{Yii::$app->language} ?>
        </a>
        <a href="<?= Url::to([$slide->link]) ?>" class="d-none d-lg-inline-block btn btn-lg btn-primary position-absolute right-0 bottom-0 mr-3 mb-3 gotham font-weight-light px-8 py-1_5">
            <?= json_decode($slide->button_text)->{Yii::$app->language} ?>
        </a>
<?php
    }
?>
    </div>
<?php
}
?>
</div>

<div class="container-xxl mt-8 mt-lg-11">
    <div class="row mt-5">
        <div class="col-lg-4 mb-3">
            <h1 class="gotham font-weight-bold ml-5 text-uppercase text-howrap">“<span class="headline">Mamour</span>”?</h1>
        </div>
        <div class="col-lg-7 col-xl-6 h5 courier letter-spacing-10">
            <?= Yii::t('front', 'Французское слово “Mamour” означает «моя любовь, любимый, любимая». Это сокращенная форма известного всем “mon amour”. Однако, “Mamour” отличается более «теплым» звучанием, нежностью. “Mamour” выбирают только для самых дорогих, близких, любимых людей. Долго выбирая среди множества вариантов, мы решили, что это самое подходящее название для нашего бренда, выражающее в полной мере концепцию марки.') ?>
        </div>
    </div>
</div>

<div class="container mt-8 mt-lg-11">
<?php
    if ($categories) {
?>
        <div class="owl-carousel owl-theme mb-1_25" data-items="2-2-2-2-2-2" data-margin="20" data-nav="true" data-dots="true" data-loop="true">
<?php
        foreach ($categories as $category) {
            $image = $category->getImage();
            $imageCachePath = '/images/cache/Slides/Slides' . $image->itemId . '/' . $image->urlAlias . '.' . $image->extension;
            $imageSrc = file_exists(Yii::getAlias('@frontend') . '/web' . $imageCachePath) ? $imageCachePath : $image->getUrl();
?>
            <a href="<?= Url::to([$category->link]) ?>">
                <?= ImgOpt::widget([
                        'src' => $imageSrc, 
                        'alt' => $this->title,
                        'loading' => 'lazy',
                        'css' => 'img-fluid',
                    ])
                ?>
            </a>
<?php
        }
?>
        </div>
<?php
    }

    if ($subCategories) {
?>
        <div class="owl-carousel owl-theme" data-items="2-2-3-3-4-4" data-margin="20" data-nav="true" data-dots="true" data-loop="true">
<?php
        foreach ($subCategories as $subCategory) {
            $image = $subCategory->getImage();
            $imageCachePath = '/images/cache/Slides/Slides' . $image->itemId . '/' . $image->urlAlias . '.' . $image->extension;
            $imageSrc = file_exists(Yii::getAlias('@frontend') . '/web' . $imageCachePath) ? $imageCachePath : $image->getUrl();
?>
            <a href="<?= Url::to([$subCategory->link]) ?>">
                <?= ImgOpt::widget([
                        'src' => $imageSrc, 
                        'alt' => $this->title,
                        'loading' => 'lazy',
                        'css' => 'img-fluid',
                    ])
                ?>
            </a>
<?php
        }
?>
        </div>
<?php
    }
?>
</div>

<div class="container mt-6 mt-lg-8">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-9 col-xl-9">
            <h2 class="h1 gotham font-weight-bold text-uppercase text-center letter-spacing-10 mb-3">
                <?= Yii::t('front', 'Заголовок') ?>
            </h2>
            <p class="text-center lead courier">
                <?= Yii::t('front', 'Текст поменяется на актуальный. Долго выбирая среди множества вариантов, мы решили, что это самое подходящее название для нашего бренда, выражающее в полной мере концепцию марки.') ?>
            </p>
        </div>
    </div>
</div>

<div class="container mt-4 mt-lg-6">
    <div class="row justify-content-center">
        <div class="col-6 col-md-3 text-center mb-1">
            <a href="<?= Url::to(['/service/delivery']) ?>" class="text-decoration-none">
                <img src="/images/main/mainpage_icon_delivery.png" class="mb-2">
                <p class="gotham font-weight-bold text-uppercase text-center letter-spacing-10 mb-1_5">
                    <?= Yii::t('front', 'Курьерская доставка') ?>
                </p>
            </a>
        </div>
        <div class="col-6 col-md-3 text-center mb-1">
            <a href="<?= Url::to(['/service/payment']) ?>" class="text-decoration-none">
                <img src="/images/main/mainpage_icon_payment.png" class="mb-2">
                <p class="gotham font-weight-bold text-uppercase text-center letter-spacing-10 mb-1_5">
                    <?= Yii::t('front', 'Оплата') ?>
                </p>
            </a>
        </div>
        <div class="col-6 col-md-3 text-center mb-1">
            <a href="<?= Url::to(['/service/package']) ?>" class="text-decoration-none">
                <img src="/images/main/mainpage_icon_package.png" class="mb-2">
                <p class="gotham font-weight-bold text-uppercase text-center letter-spacing-10 mb-1_5">
                    <?= Yii::t('front', 'Упаковка') ?>
                </p>
            </a>
        </div>
        <div class="col-6 col-md-3 text-center mb-1">
            <a href="<?= Url::to(['/service']) ?>" class="text-decoration-none">
                <img src="/images/main/mainpage_icon_service.png" class="mb-2">
                <p class="gotham font-weight-bold text-uppercase text-center letter-spacing-10 mb-1_5">
                    <?= Yii::t('front', 'Клиентский сервис') ?>
                </p>
            </a>
        </div>
    </div>
</div>
