<?php

use yii\helpers\Url;
use yii\helpers\Html;
use PELock\ImgOpt\ImgOpt;
use dvizh\shop\models\Category;

$this->title = Yii::$app->name;

?>

<?php
    if ($slidesDesktop) {
?>
        <div class="owl-carousel owl-theme mainpage-slider desktop d-none d-md-block" data-nav="true" data-dots="true" data-loop="true" data-autoplay="true" data-animatein="fadeIn" data-animateout="fadeOut" data-hoverstop="true">
        <?php
        foreach ($slidesDesktop as $slide) {
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
                <a href="<?= Url::to([$slide->link]) ?>" class="d-md-none btn btn btn-primary position-absolute right-0 bottom-0 mr-1 mb-1 montserrat font-weight-light px-2 py-0_5">
                    <?= json_decode($slide->button_text)->{Yii::$app->language} ?>
                </a>
                <a href="<?= Url::to([$slide->link]) ?>" class="d-none d-md-inline-block d-lg-none btn btn-primary position-absolute right-0 bottom-0 mr-1 mb-1 montserrat font-weight-light px-3 py-1">
                    <?= json_decode($slide->button_text)->{Yii::$app->language} ?>
                </a>
                <a href="<?= Url::to([$slide->link]) ?>" class="d-none d-lg-inline-block btn btn-lg btn-primary position-absolute right-0 bottom-0 mr-3 mb-3 px-8 py-1_5 montserrat font-weight-light">
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
<?php
    }
?>

<?php
    if ($slidesMobile) {
?>
        <div class="owl-carousel owl-theme mainpage-slider mobile d-md-none" data-nav="true" data-dots="true" data-loop="true" data-autoplay="true" data-animatein="fadeIn" data-animateout="fadeOut" data-hoverstop="true">
        <?php
        foreach ($slidesMobile as $slide) {
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
                <a href="<?= Url::to([$slide->link]) ?>" class="d-md-none btn btn btn-primary position-absolute right-0 bottom-0 mr-1 mb-1 montserrat font-weight-light px-2 py-0_5">
                    <?= json_decode($slide->button_text)->{Yii::$app->language} ?>
                </a>
                <a href="<?= Url::to([$slide->link]) ?>" class="d-none d-md-inline-block d-lg-none btn btn-primary position-absolute right-0 bottom-0 mr-1 mb-1 montserrat font-weight-light px-3 py-1">
                    <?= json_decode($slide->button_text)->{Yii::$app->language} ?>
                </a>
                <a href="<?= Url::to([$slide->link]) ?>" class="d-none d-lg-inline-block btn btn-lg btn-primary position-absolute right-0 bottom-0 mr-3 mb-3 px-8 py-1_5 montserrat font-weight-light">
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
<?php
    }
?>

<div class="container-xxl mt-8 mt-lg-11">
    <div class="row mt-5">
        <div class="col-lg-4 mb-3">
            <h1 class="montserrat font-weight-bold ml-5 text-uppercase text-howrap">“<span class="headline">Mamour</span>”?</h1>
        </div>
        <div class="col-lg-7 col-xl-6 lead courier letter-spacing-10 line-height-150">
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
            if (!$category->parent_id) {
                $image = $category->getImage();
                $imageCachePath = '/images/cache/Slides/Slides' . $image->itemId . '/' . $image->urlAlias . '.' . $image->extension;
                $imageSrc = file_exists(Yii::getAlias('@frontend') . '/web' . $imageCachePath) ? $imageCachePath : $image->getUrl();
?>
                <a href="<?= Url::to(['/catalog/' . $category->slug]) ?>">
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
        }
?>
        </div>

        <div class="owl-carousel owl-theme" data-items="2-2-3-3-4-4" data-margin="20" data-nav="true" data-dots="true" data-loop="true" data-random="true">
<?php
        foreach ($categories as $category) {
            if ($category->parent_id) {
                $image = $category->getImage();
                $imageCachePath = '/images/cache/Slides/Slides' . $image->itemId . '/' . $image->urlAlias . '.' . $image->extension;
                $imageSrc = file_exists(Yii::getAlias('@frontend') . '/web' . $imageCachePath) ? $imageCachePath : $image->getUrl();
                $url = Category::getAllParents($categories, $category->id, 'slug', true);
// print_r($url);
?>
                <a href="<?= Url::to(['/catalog/' . join('/', array_reverse($url))]) ?>">
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
        }
?>
        </div>
<?php
    }
?>
</div>
